<?php

namespace Naventum\Framework\Illuminate\Foundation\Support;

use HaydenPierce\ClassFinder\ClassFinder;
use Jenssegers\Blade\Blade;
use Illuminate\Support\Str;

class View
{
    private $view;

    private $viewDefaultPath;

    private $data;

    public function __construct(string $view, string $viewDefaultPath = null, $data = [])
    {
        $this->view = $view;
        $this->viewDefaultPath = $viewDefaultPath;
        $this->data = $data;
    }

    public function make()
    {
        $view = $this->getView(str_replace('.', '/', $this->view) . '.blade.php')->render();

        $_SESSION['flash_data'] = [];

        echo $view;

        return $view;
    }

    public function makeWithoutEcho()
    {
        $view = $this->getView(str_replace('.', '/', $this->view) . '.blade.php')->render();

        $_SESSION['flash_data'] = [];

        return $view;
    }

    private function getView(string $viewWithExtension)
    {
        if (file_exists(view_path() . '/' . $viewWithExtension)) {
            return $this->makeBlade(view_path());
        }

        if ($this->viewDefaultPath) {
            return $this->makeBlade($this->viewDefaultPath);
        }

        return $this->makeBlade(view_path() . '/' . $viewWithExtension);
    }

    private function makeView($blade)
    {
        return $blade->make($this->view, $this->data);
    }

    private function makeBlade(string $viewPath)
    {
        $blade = new Blade($viewPath, base_path() . '/storage/framework/views');

        $blade->compiler()->components($this->getComponents());

        $app = \Illuminate\Container\Container::getInstance();

        $app->bind(
            'Illuminate\Contracts\View\Factory',
            function ($app) use ($blade) {
                return $blade;
            }
        );

        return $this->makeView($blade);
    }

    private function getName($class)
    {
        return Str::lower(str_replace(['App\View\Components\\', '\\'], ['', '.'], $class));
    }

    private function getComponents()
    {
        $classes = [];
        $getClasses = ClassFinder::getClassesInNamespace('App\View\Components', ClassFinder::RECURSIVE_MODE);

        foreach ($getClasses as $class) {
            $classes[$this->getName($class)] = $class;
        }

        return $classes;
    }
}
