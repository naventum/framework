<?php

namespace Naventum\Framework\Illuminate\Foundation\Cli\Commands;

use Naventum\Framework\Illuminate\Foundation\Cli\Command;
use Naventum\Framework\Illuminate\Foundation\Cli\Stub;

class MakeModel extends Command
{
    public $command = 'make:model';

    public function handle($argv)
    {
        if(!isset($argv[2]) && empty($argv[2])) {
            die('  Not enough arguments (missing: "name").');
        }

        $fullPath = app_path('/Models/' . $argv[2] . '.php');

        if(file_exists($fullPath)) {
            die('  Model already exists!');
        }
        
        $classname = $argv[2];
        $stub = file_get_contents(__DIR__ . '/../stubs/model.stub');

        Stub::make($stub, ['___CLASSNAME___' => $classname])->put(
            $fullPath
        );

        return $this->successMessage();
    }

    public function successMessage()
    {
        echo 'Model created successfully.';
    }
}