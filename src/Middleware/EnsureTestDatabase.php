<?php

namespace TestTools\TestDatabaseManager\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TestTools\TestDatabaseManager\TestDatabaseService;

class EnsureTestDatabase
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_test_user) {
            $service = new TestDatabaseService();

            if (!$service->isDatabaseInitialized()) {
                $service->resetDatabase();
            }

            config(['database.connections.mysql.database' => config('testdb.name')]);
            DB::purge('mysql');
            DB::reconnect('mysql');
        }

        return $next($request);
    }
}
