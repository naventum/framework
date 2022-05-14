<?php

namespace Naventum\Framework\Illuminate\Foundation\Cli;

use HaydenPierce\ClassFinder\ClassFinder;
use Naventum\Framework\Illuminate\Foundation\Support\Init;
use Naventum\Framework\Illuminate\Support\Facades\App;

class Cli
{
    private $command;

    private $commandClasses;
    
    private $argv;

    private $class = 'Naventum\Framework\Illuminate\Foundation\Cli\Commands';

    public function run(array $argv)
    {
        $this->commandClasses = ClassFinder::getClassesInNamespace($this->class, ClassFinder::RECURSIVE_MODE);
        $this->argv = $argv;
        $this->command = $argv[1] ?? null;

        foreach($this->commandClasses as $class) {
            $class = new $class;
            $command = $class->command;
            
            if($this->command == $command) {
                return $class->handle($this->argv);
            }
        }

        return $this->help();
    }

    public function init(string $basePath)
    {
        $init = new Init;
        
        $init->paths( $basePath )->dotEnv()->db()->routing();

        return $this;
    }

    public function help()
    {
        foreach($this->commandClasses as $class) {
            $command = (new $class)->command;
            $commands[ explode(':', $command, 2)[0] ][] = $command;
        }

        $OtherCommands = [];

        foreach($commands as $key => $childCommands) {
            if(count($childCommands) > 1) {
                
                echo "{$key}\n";

                foreach($childCommands as $childCommand) {
                    echo "  {$childCommand}\n";
                }
            } else {
                $OtherCommands[] = $childCommands[0];
            }
        }

        echo "Other\n";
        foreach($OtherCommands as $command) {
            echo "  {$command}\n";
        }
    }
}