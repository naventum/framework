<?php

namespace Naventum\Framework\Illuminate\Foundation\Support\Http;

class Response
{
    public static function make($response)
    {
        if (is_string($response)) {
            echo $response;

            return $response;
        }

        if (is_array($response)) {
            echo json_encode($response);

            return $response;
        }

        if (is_object($response)) {
            echo json_encode($response);

            return $response;
        }

        return $response;
    }
}
