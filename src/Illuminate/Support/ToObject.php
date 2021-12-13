<?php

namespace Naventum\Framework\Illuminate\Support;

class ToObject
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function data()
    {
        return $this->data;
    }

    public function object()
    {
        return $this->make($this->data);
    }

    public function make(array $data)
    {
        return json_decode(json_encode((object) $data), false);
    }
}
