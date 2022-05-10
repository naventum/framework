<?php

namespace Naventum\Framework\Illuminate\Support;

interface Middleware
{
    public function handle($next, $closure);
}
