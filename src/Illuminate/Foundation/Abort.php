<?php

namespace Naventum\Framework\Illuminate\Foundation;

use Naventum\Framework\Illuminate\Support\View;
use Naventum\Framework\Path;

class Abort
{
    private $code;

    private $message;

    public function __construct(int $code, string $message = null)
    {
        $this->code = $code;

        if ($message) {
            $this->message = $message;
        }
    }

    public function response()
    {
        if (!$this->message) {
            $this->setMessageFromErrorCode();
        }

        $this->setHttpResponse($this->code);

        return View::make('error', Path::all()['view_path'], ['code' => $this->code, 'message' => $this->message]);
    }

    private function setHttpResponse(int $code)
    {
        return http_response_code($code);
    }

    private function setMessageFromErrorCode()
    {
        switch ($this->code) {
            case 404:
                $this->message = 'Not Found';
                break;
            case 405:
                $this->message = 'Method Not Allowed';
                break;
            default:
                $this->code = 500;
                $this->message = 'Server Error';
        }

        return $this;
    }
}
