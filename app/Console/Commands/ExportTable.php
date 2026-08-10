<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ExportTable extends Command
{
    protected $signature = 'table:export {table} {--path=}';
    protected $description = 'Export a table to an SQL file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $table = $this->argument('table');
        $path = $this->option('path') ?: storage_path("exports/{$table}.sql");

        // Get the database details from the config
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        // Prepare the mysqldump command
        $command = [
            'mysqldump',
            "--user={$username}",
            "--password={$password}",
            "--host={$host}",
            $database,
            $table,
            "--result-file={$path}",
        ];

        // Create and run the process
        $process = new Process($command);
        $process->run();

        // Check if the process was successful
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info("Table {$table} exported successfully to {$path}");
    }
}
