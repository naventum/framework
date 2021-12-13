<?php

namespace Naventum\Framework\Illuminate\Support;

class Session
{
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;

        return $_SESSION[$key];
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function setFlashData($key, $value)
    {
        $_SESSION['flash_data'][$key] = $value;

        return $_SESSION['flash_data'][$key];
    }

    public function getFlashData($key, $default = null)
    {
        return $_SESSION['flash_data'][$key] ?? $default;
    }
}
