<?php

namespace Naventum\Framework\Illuminate\Support;

class View
{
    public static function make(string $view, string $viewDefaultPath = null, $data = [])
    {
        $viewWithExtension = $view . '.php';
        $fullPath = $viewDefaultPath . '/' . $viewWithExtension;

        if (file_exists(view_path() . '/' . $viewWithExtension)) {
            $response = require view_path() . '/' . $viewWithExtension;
        } else {
            $response = require $fullPath;
        }

        $_SESSION['flash_data'] = [];

        return $response;
    }
}
