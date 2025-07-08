<?php

return [
    'name' => env('TEST_DB_NAME', 'test_temp_db'),
    'backup_path' => env('TEST_DB_BACKUP', storage_path('app/backups/test_db_backup.sql')),
];
