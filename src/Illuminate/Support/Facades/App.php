<?php

namespace Naventum\Framework\Illuminate\Support\Facades;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Naventum\Framework\Illuminate\Foundation\Support\Debug\Debugger;
use Naventum\Framework\Illuminate\Foundation\Support\Init;
use Naventum\Framework\Illuminate\Foundation\Support\Middleware;
use Naventum\Framework\Illuminate\Foundation\Support\Provider;
use ReflectionClass;

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
        $this->setActiveRoute($this->getRoute($this->getRequestPath()));

        if (isset($this->activeRoute['_controller']) && isset($this->activeRoute['_method'])) {
            $this->setActiveControllerMethod();

            $runMiddlewares = $this->runMiddlewares();

            if (!$runMiddlewares) {
                return $runMiddlewares;
            }

            if (!$this->setClassBindings()) {
                return abort(404);
            }

            return $this->run();
        }
    }

    private function setClassBindings()
    {
        $params = [];

        foreach ((new ReflectionClass($this->activeController))->getMethod($this->activeMethod)->getParameters() as $param) {
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

                    $params[$paramPosition] = $model;
                }
            } else {
                $params[$paramPosition] = $this->activeRouteParams[$paramPosition];
            }
        }

        $this->activeRouteParams = $params;

        return true;
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

    private function runMiddlewares()
    {
        return Middleware::runAllBy($this->activeRoute['_middlewares']);
    }

    private function setDefaultParams()
    {
        $params = $this->activeRoute;

        foreach (['_controller', '_middlewares', '_modelBindings', '_route', '_method', '_classBindings'] as $name) {
            if (isset($params[$name])) {
                unset($params[$name]);
            }
        }

        return $this->activeRouteParams = $this->values($params);
    }

    private function setActiveRoute($route)
    {
        $this->activeRoute = $route;

        return $this->setDefaultParams();
    }

    private function setActiveControllerMethod()
    {
        $this->activeController = new $this->activeRoute['_controller'];
        $this->activeMethod = $this->activeRoute['_method'];
    }

    private function run()
    {
        $activeController = $this->activeController;
        $activeMethod = $this->activeMethod;

        return $activeController->$activeMethod(...$this->activeRouteParams);
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
