<?php

namespace Naventum\Framework\Illuminate\Foundation\Support;

class BaseController
{
    public function view(string $view, $data = [])
    {
        return view($view, $data);
    }

    public function middleware(array $middlewares)
    {
        return Middleware::runAllBy($middlewares);
    }
}
