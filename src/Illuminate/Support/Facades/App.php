<?php

namespace Naventum\Framework\Illuminate\Support\Facades;

use Naventum\Framework\Illuminate\Foundation\Support\Debug\Debugger;
use Naventum\Framework\Illuminate\Foundation\Support\Init;
use Naventum\Framework\Illuminate\Foundation\Support\Middleware;
use Naventum\Framework\Illuminate\Foundation\Support\Provider;

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

            if (!$this->setModelBindings()) {
                return abort(404);
            }

            $this->setClassBindings();

            return $this->run();
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

    private function runMiddlewares()
    {
        return Middleware::runAllBy($this->activeRoute['_middlewares']);
    }

    private function setClassBindings()
    {
        foreach ($this->activeRoute['_classBindings'] as $name => $model) {
            if (isset($this->activeRouteParams[$name])) {
                $model = new $model($this->activeRouteParams);

                $this->activeRouteParams[$name] = $model;
            }
        }

        return true;
    }

    private function setModelBindings()
    {
        foreach ($this->activeRoute['_modelBindings'] as $name => $model) {
            if (isset($this->activeRouteParams[$name])) {
                $model = new $model;
                $model = $model::where($model->getRouteKeyName(), $this->activeRouteParams[$name])->first();

                if (!$model) {
                    return abort(404);
                }

                $this->activeRouteParams[$name] = $model;
            }
        }

        return true;
    }

    private function setDefaultParams()
    {
        $params = $this->activeRoute;

        foreach (['_controller', '_middlewares', '_modelBindings', '_route', '_method', '_classBindings'] as $name) {
            if (isset($params[$name])) {
                unset($params[$name]);
            }
        }

        return $this->activeRouteParams = $params;
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

        return $activeController->$activeMethod(...$this->getActiveRouteParams());
    }

    private function getActiveRouteParams()
    {
        return array_values($this->activeRouteParams);
    }

    private function getRequestPath()
    {
        return $this->parseURI()['path'];
    }
}
