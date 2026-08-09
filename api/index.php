<?php

// Check if running on Vercel and create necessary storage directories in /tmp
if (isset($_ENV['VERCEL'])) {
    $_ENV['APP_DEBUG'] = 'true';
    $_SERVER['APP_DEBUG'] = 'true';
    
    $storageDirs = [
        '/tmp/storage/app/public',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/views',
        '/tmp/storage/logs',
    ];
    foreach ($storageDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}

// Forward Vercel request to Laravel public/index.php
require __DIR__ . '/../public/index.php';
