<?php

if (isset($_GET['_test_db'])) {
    header('Content-Type: text/plain');
    $dbConnection = getenv('DB_CONNECTION') ?: 'pgsql';
    $dbHost = getenv('DB_HOST');
    $dbPort = getenv('DB_PORT');
    $dbDatabase = getenv('DB_DATABASE');
    $dbUsername = getenv('DB_USERNAME');
    $dbPassword = getenv('DB_PASSWORD');

    echo "Testing Database Connection:\n";
    echo "Connection: $dbConnection\n";
    echo "Host: $dbHost\n";
    echo "Port: $dbPort\n";
    echo "Database: $dbDatabase\n";
    echo "Username: $dbUsername\n";
    
    try {
        $dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbDatabase;sslmode=require";
        echo "Connecting with DSN: $dsn\n";
        $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);
        echo "SUCCESS: Connection established successfully!\n\n";
        
        echo "Tables in database:\n";
        $stmt = $pdo->query("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (empty($tables)) {
            echo "No tables found in public schema!\n";
        } else {
            foreach ($tables as $table) {
                echo "- $table\n";
            }
        }
    } catch (\Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        
        echo "\nRetrying without SSL...\n";
        try {
            $dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbDatabase";
            $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5
            ]);
            echo "SUCCESS (No SSL): Connection established successfully!\n\n";
            
            echo "Tables in database:\n";
            $stmt = $pdo->query("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (empty($tables)) {
                echo "No tables found in public schema!\n";
            } else {
                foreach ($tables as $table) {
                    echo "- $table\n";
                }
            }
        } catch (\Throwable $e2) {
            echo "ERROR (No SSL): " . $e2->getMessage() . "\n";
        }
    }
    exit;
}

// Prepare writable directories and cache paths for Vercel read-only filesystem
if (getenv('VERCEL')) {
    if (!is_dir('/tmp/views')) {
        mkdir('/tmp/views', 0755, true);
    }
    putenv('APP_SERVICES_CACHE=/tmp/services.php');
    putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
}

// Forward Vercel requests to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
