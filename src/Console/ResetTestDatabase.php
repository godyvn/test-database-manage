<?php

namespace TestTools\TestDatabaseManager\Console;

use Illuminate\Console\Command;
use TestTools\TestDatabaseManager\TestDatabaseService;

class ResetTestDatabase extends Command
{
    protected $signature = 'db:reset-test';
    protected $description = 'Reset lại database test tạm thời';

    public function handle()
    {
        $service = new TestDatabaseService();
        $this->info("Resetting database: " . config('testdb.name'));
        $service->resetDatabase();
        $this->info("✅ Done");
    }
}
