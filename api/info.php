<?php
header('Content-Type: application/json');
echo json_encode([
    'php_version' => PHP_VERSION,
    'extensions' => get_loaded_extensions(),
]);
