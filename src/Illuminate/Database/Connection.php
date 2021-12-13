<?php

namespace Naventum\Framework\Illuminate\Database;

use Illuminate\Database\Capsule\Manager;
use Naventum\Framework\Path;

class Connection
{
    private $__driver = 'mysql';

    private $__host = 'localhost';

    private $__database = 'database';

    private $__username = 'username';

    private $__password = 'password';

    private $__charset = 'utf8';

    private $__collation = 'utf8_unicode_ci';

    private $__prefix;

    private $__path;

    public function __construct()
    {
        $this->setConfig();
    }

    public function ___conn()
    {
        $capsule = $this->capsule();

        $capsule->addConnection([
            'driver' => $this->__driver,
            'host' => $this->__host,
            'database' => $this->__database,
            'username' => $this->__username,
            'password' => $this->__password,
            'charset' => $this->__charset,
            'collation' => $this->__collation,
            'prefix' => $this->__prefix,
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    }

    private function __setPath()
    {
        if (file_exists(config_path() . '/database.php')) {
            return $this->__path = config_path() . '/database.php';
        }

        return $this->__path = Path::all()['config_path'] . '/database.php';
    }

    private function setConfig()
    {
        $this->__setPath();

        $config = require $this->__path;

        $this->__driver = $config['driver'];
        $this->__host = $config['host'];
        $this->__database = $config['database'];
        $this->__username = $config['username'];
        $this->__password = $config['password'];
        $this->__charset = $config['charset'];
        $this->__collation = $config['collation'];
        $this->__prefix = $config['prefix'];

        return $config;
    }

    private function capsule()
    {
        return new Manager;
    }
}
