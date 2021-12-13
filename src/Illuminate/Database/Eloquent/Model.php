<?php

namespace Naventum\Framework\Illuminate\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Naventum\Framework\Illuminate\Database\Connection;

class Model extends EloquentModel
{
    public function __construct()
    {
        $this->conn()->___conn();
    }

    private function conn()
    {
        return new Connection;
    }
}
