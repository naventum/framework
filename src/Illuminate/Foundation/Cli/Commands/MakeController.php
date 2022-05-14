<?php

namespace Naventum\Framework\Illuminate\Foundation\Cli\Commands;

use Naventum\Framework\Illuminate\Foundation\Cli\Command;
use Naventum\Framework\Illuminate\Foundation\Cli\Stub;

class MakeController extends Command
{
    public $command = 'make:controller';

    public function handle($argv)
    {
        if(!isset($argv[2]) && empty($argv[2])) {
            die('  Not enough arguments (missing: "name").');
        }

        $fullPath = app_path('/Http/Controllers/' . $argv[2] . '.php');

        if(file_exists($fullPath)) {
            die('  Controller already exists!');
        }
        
        $classname = $argv[2];
        $stub = file_get_contents(__DIR__ . '/../stubs/controller.stub');

        Stub::make($stub, ['___CLASSNAME___' => $classname])->put(
            $fullPath
        );

        return $this->successMessage();
    }

    public function successMessage()
    {
        echo 'Controller created successfully.';
    }
}