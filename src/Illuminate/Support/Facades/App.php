<?php

namespace Naventum\Framework\Illuminate\Support\Facades;

use Illuminate\Database\Eloquent\Model;
use Naventum\Framework\Illuminate\Foundation\Support\Debug\Debugger;
use Naventum\Framework\Illuminate\Foundation\Support\Http\Response;
use Naventum\Framework\Illuminate\Foundation\Support\Init;
use Naventum\Framework\Illuminate\Foundation\Support\Middleware;
use Naventum\Framework\Illuminate\Foundation\Support\Provider;
use ReflectionClass;
use ReflectionObject;

class App extends Route
{
    private $activeRoute;

    private $activeRouteParams = [];

    private $activeController;

    private $activeMethod;

    public function make()
    {
        $this->debug();

        $this->registerDefaultFlashData();
        $this->runProviders();

        $route = $this->getRoute($this->getRequestPath());

        if (is_string($route)) {
            return Response::make($route);
        }

        $this->setActiveRoute($route);

        if (!$this->setRouteClassBinding()) {
            return abort(404);
        }

        return $this->run();
    }

    private function setRouteClassBinding()
    {
        if (isset($this->activeRoute['_controller']) && isset($this->activeRoute['_method'])) {
            return $this->setRouteClassBindingForClass();
        }

        return $this->setClassBindingForClosure();
    }

    private function setRouteClassBindingForClass()
    {
        $params = [];

        foreach ((new ReflectionClass($this->activeController))->getMethod($this->activeMethod)->getParameters() as $param) {
            $param = $this->getParamClass($param);

            if (!$param) {
                return false;
            }

            $params[$param['paramPosition']] = $param['value'];
        }

        $this->activeRouteParams = $params;

        return true;
    }

    private function setClassBindingForClosure()
    {
        $params = [];

        foreach ((new ReflectionObject($this->activeMethod))->getMethod('__invoke')->getParameters() as $param) {
            $param = $this->getParamClass($param);

            if (!$param) {
                return false;
            }

            $params[$param['paramPosition']] = $param['value'];
        }

        $this->activeRouteParams = $params;

        return true;
    }

    private function getParamClass($param)
    {
        $paramType = $param->getType();
        $paramPosition = $param->getPosition();

        // class
        if ($paramType) {
            $paramName = $paramType->getName();
            $routeClassBinding = new $paramName;

            // Model
            if ($routeClassBinding instanceof Model) {
                $model = $routeClassBinding::where($routeClassBinding->getRouteKeyName(), $this->activeRouteParams[$paramPosition])->first();

                if (!$model) {
                    return false;
                }

                return [
                    'paramPosition' => $paramPosition,
                    'value' => $model
                ];
            }
        } else {
            return [
                'paramPosition' => $paramPosition,
                'value' => $this->activeRouteParams[$paramPosition]
            ];
        }
    }

    public function debug()
    {
        Debugger::register();

        return new Init;
    }

    private function registerDefaultFlashData()
    {
        if (!isset($_SESSION['flash_data'])) {
            return $_SESSION['flash_data'] = [];
        }
    }

    private function runProviders()
    {
        $config = $this->getConfig();

        return Provider::runAllBy($config->providers);
    }

    private function getConfig()
    {
        return config('app');
    }

    private function runMiddlewares($closure)
    {
        return Middleware::runAllBy($this->activeRoute['_middlewares'], $closure);
    }

    private function setDefaultParams()
    {
        $params = $this->activeRoute;

        foreach (['_controller', '_middlewares', '_route', '_method'] as $name) {
            if (isset($params[$name])) {
                unset($params[$name]);
            }
        }

        return $this->activeRouteParams = $this->values($params);
    }

    private function setActiveRoute($route)
    {
        $this->activeRoute = $route;

        $this->setDefaultParams();
        $this->setActiveControllerMethod();

        return $this->activeRoute;
    }

    private function setActiveControllerMethod()
    {
        if (isset($this->activeRoute['_controller'])) {
            $this->activeController = new $this->activeRoute['_controller'];
        }

        $this->activeMethod = $this->activeRoute['_method'];
    }

    private function run()
    {
        $activeController = $this->activeController;
        $activeMethod = $this->activeMethod;

        $closure = function () use ($activeController, $activeMethod) {
            return $this->getResponse($activeMethod, $activeController);
        };

        $next = $this->runMiddlewares($closure);

        return Response::make($next);
    }

    private function getResponse($activeMethod, $activeController = null)
    {
        if (isset($activeController)) {
            return $activeController->$activeMethod(...$this->activeRouteParams);
        }

        return $activeMethod(...$this->activeRouteParams);
    }

    private function values(array $array)
    {
        return array_values($array);
    }

    private function getRequestPath()
    {
        return $this->parseURI()['path'];
    }
}
