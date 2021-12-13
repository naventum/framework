<?php

namespace Naventum\Framework\Illuminate\Support\Facades;

use Naventum\Framework\Illuminate\Foundation\Support\Debug\Debugger;
use Symfony\Component\ErrorHandler\ErrorHandler;

class App extends Route
{
    private $activeRoute;

    private $activeRouteParams = [];

    private $activeController;

    private $activeMethod;

    public function make()
    {
        Debugger::check();

        return ErrorHandler::call(function () {
            Auth::start();

            $this->registerDefaultSessions();

            static::__startRouting();
            $this->runProviders();

            $this->setActiveRoute(Route::getRoute($this->getRequestPath()));

            if (isset($this->activeRoute['_controller']) && isset($this->activeRoute['_method'])) {
                $this->setActiveControllerMethod();

                if (!$this->runMiddlewares()) {
                    return;
                }

                if (!$this->setModelBindings()) {
                    return abort(404);
                }

                $this->setClassBindings();

                return $this->run();
            }
        });
    }

    private function registerDefaultSessions()
    {
        if (!isset($_SESSION['flash_data'])) {
            $_SESSION['flash_data'] = [];
        }
    }

    private function runProviders()
    {
        $config = $this->getConfig();

        foreach ($config->providers as $provider) {
            $provider = new $provider;
            $provider->boot();
        }
    }

    private function getConfig()
    {
        return config('app');
    }

    private function runMiddlewares()
    {
        foreach ($this->activeRoute['_middlewares'] as $middleware) {

            $middleware = new $middleware;

            if (!$middleware->handle()) {
                return false;
            }
        }

        return true;
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
            unset($params[$name]);
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

        return $activeController->$activeMethod(...array_values($this->activeRouteParams));
    }

    private function getRequestPath()
    {
        return $this->parseURI()['path'];
    }
}
