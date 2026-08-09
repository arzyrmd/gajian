<?php
header('Content-Type: application/json');

$env = [];
foreach ($_ENV as $key => $value) {
    if (str_contains(strtolower($key), 'password') || str_contains(strtolower($key), 'key') || str_contains(strtolower($key), 'secret')) {
        $env[$key] = '********';
    } else {
        $env[$key] = $value;
    }
}

echo json_encode([
    'php_version' => PHP_VERSION,
    'extensions' => get_loaded_extensions(),
    'env' => $env,
]);
