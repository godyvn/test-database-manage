<?php

namespace TestTools\TestDatabaseManager;

use Illuminate\Support\Facades\DB;

class TestDatabaseService
{
    protected string $dbName;
    protected string $backupPath;

    public function __construct()
    {
        $this->dbName = config('testdb.name');
        $this->backupPath = config('testdb.backup_path');
    }

    public function isDatabaseInitialized(): bool
    {
        $tables = DB::connection('mysql')->select("SHOW TABLES FROM `$this->dbName`");
        return count($tables) > 0;
    }

    public function resetDatabase(): void
    {
        $this->dropAllTables();
        $this->importFromBackup();
    }

    protected function dropAllTables(): void
    {
        $tables = DB::select("SHOW TABLES FROM `$this->dbName`");
        $key = "Tables_in_{$this->dbName}";

        DB::statement("USE `$this->dbName`");
        DB::statement("SET FOREIGN_KEY_CHECKS=0");

        foreach ($tables as $table) {
            DB::statement("DROP TABLE IF EXISTS `{$table->$key}`");
        }

        DB::statement("SET FOREIGN_KEY_CHECKS=1");
    }

    protected function importFromBackup(): void
    {
        if (!file_exists($this->backupPath)) {
            throw new \Exception("Backup file not found at {$this->backupPath}");
        }

        $cmd = sprintf(
            'mysql -u%s -p%s -h%s %s < %s',
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_HOST', '127.0.0.1')),
            escapeshellarg($this->dbName),
            escapeshellarg($this->backupPath)
        );

        exec($cmd, $output, $result);
        if ($result !== 0) {
            throw new \Exception("Failed to import DB: " . implode("\n", $output));
        }
    }
}
