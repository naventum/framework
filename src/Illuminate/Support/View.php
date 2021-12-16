<?php

namespace Naventum\Framework\Illuminate\Support;

use Naventum\Framework\Illuminate\Foundation\Support\View as SupportView;

class View
{
    public static function make(string $view, string $viewDefaultPath = null, $data = [])
    {
        $blade = new SupportView($view, $viewDefaultPath, $data);

        return $blade->make();
    }

    public static function makeWithoutEcho(string $view, string $viewDefaultPath = null, $data = [])
    {
        $blade = new SupportView($view, $viewDefaultPath, $data);

        return $blade->makeWithoutEcho();
    }
}
