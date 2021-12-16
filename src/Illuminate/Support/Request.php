<?php

namespace Naventum\Framework\Illuminate\Support;

use Ryodevz\Validator\Support\Facade\Validator;

class Request
{
    private $validator;

    public function __construct()
    {
        foreach ($this->all() as $key => $value) {
            $this->$key = trim($value);
        }
    }

    public function validate(array $rules)
    {
        $this->validator = Validator::make($this->all(), $rules)->validate();

        return $this;
    }

    public function only(array $keys, $default = null)
    {
        foreach ($keys as $key) {
            $only[$key] = $this->$key ?? $default;
        }

        return $only;
    }

    public function fails($redirect)
    {
        if ($this->validator->fails()) {
            return redirect($redirect);
        }

        return true;
    }

    public function handle($closesurce)
    {
        return $closesurce($this->validator);
    }

    public function all()
    {
        $all = [];

        foreach ($_REQUEST as $key => $value) {
            $all[$key] = (is_string($value) ? trim($value) : $value);
        }

        return $all;
    }

    public function get(string $key, $default = null)
    {
        return (isset($_GET[$key]) ? trim($_GET[$key]) : $default);
    }

    public function post(string $key, $default = null)
    {
        return (isset($_POST[$key]) ? trim($_POST[$key]) : $default);
    }
}
