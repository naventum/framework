<?php

namespace Naventum\Framework\Illuminate\Foundation\Support;

use Jenssegers\Blade\Blade;

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
        $view = $this->initView($this->view . '.blade.php')->render();

        $_SESSION['flash_data'] = [];

        echo $view;

        return $view;
    }

    private function initView(string $viewWithExtension)
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
        return $blade->make($this->view, $this->data);;
    }

    private function makeBlade(string $viewPath)
    {
        return $this->makeView(new Blade($viewPath, base_path() . '/storage/framework/views'));
    }
}
