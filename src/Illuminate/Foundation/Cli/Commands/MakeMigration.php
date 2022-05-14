<?php

namespace Naventum\Framework\Illuminate\Foundation\Cli\Commands;

use Naventum\Framework\Illuminate\Foundation\Cli\Command;
use Naventum\Framework\Illuminate\Foundation\Cli\Stub;

class MakeMigration extends Command
{
    public $command = 'make:migration';

    public function handle($argv)
    {
        if(!isset($argv[2]) || empty($argv[2])) {
            die('  Not enough arguments (missing: "name").');
        }

        $parse = explode('_', $argv[2]);

        if($parse[0] == 'create' && $parse[2] == 'table') {
            $classname = $this->camelCase($argv[2], true);
            $filename = $this->getMigrationName($argv[2]);
            $table = $parse[1];
            
            $stub = file_get_contents(__DIR__ . '/../stubs/migration.stub');

            Stub::make($stub, ['___CLASSNAME___' => $classname, '___TABLENAME___' => $table])->put(
                base_path('/database/migrations/') . $filename . '.php'
            );

            return $this->successMessage($filename);
        }
    }

    private function successMessage(string $filename)
    {
        echo 'Created Migration: ' . $filename;
    }

    private function getMigrationName($name)
    {
        return $this->formatedDate() . '_' . $name;
    }

    private function formatedDate()
    {
        return now()->addMinutes()->format('Y_d_m_his');
    }
}