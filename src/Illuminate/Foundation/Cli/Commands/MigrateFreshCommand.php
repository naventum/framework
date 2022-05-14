<?php

namespace Naventum\Framework\Illuminate\Foundation\Cli\Commands;

use Database\Migrations\CreateUsersTable;
use HaydenPierce\ClassFinder\ClassFinder;
use Naventum\Framework\Illuminate\Foundation\Cli\Command;
use Naventum\Framework\Illuminate\Support\Facades\Schema;

class MigrateFreshCommand extends Command
{
    public $command = 'migrate:fresh';

    public function handle($argv)
    {
        echo 'This command is under development.';
        return;
        
        $files = glob(base_path('/database/migrations/*.php'));

        foreach ($files as $file) {
            require_once $file;
        }

        $classes = ClassFinder::getClassesInNamespace('Database\Migrations');

        Schema::schema()->dropAllTables();
    }
}