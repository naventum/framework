<?php

namespace Naventum\Framework\Illuminate\Foundation\Cli\Commands;

use Illuminate\Support\Str;
use Naventum\Framework\Illuminate\Foundation\Cli\Command;

class Serve extends Command
{
    public $command = 'serve';

    public function handle($argv)
    {
        $options = [];

        foreach($argv as $option) {
            $isContains = Str::contains($option, '--');

            if($isContains) {
                $parse = explode('=', $option, 2);

                $options[$parse[0]] = $parse[1];
            }
        }
        
        $port = isset($options['--port']) && !empty($options['--port']) ? $options['--port'] : 8000;
        $host = isset($options['--host']) && !empty($options['--host']) ? $options['--host'] : 'localhost';

        return system("php -S {$host}:{$port} -t public");
    }
}