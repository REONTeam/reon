<?php
/**
 * Phinx Configuration File
 *
 * This file configures Phinx to use the database credentials from config.json
 */

// Load configuration from config.json
$configFile = __DIR__ . '/config.json';
if (!file_exists($configFile)) {
    throw new RuntimeException('config.json not found. Please copy config.example.json to config.json and update with your settings.');
}

$config = json_decode(file_get_contents($configFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    throw new RuntimeException('Failed to parse config.json: ' . json_last_error_msg());
}

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'production',
        'production' => [
            'adapter' => 'mysql',
            'host' => $config['mysql_host'],
            'name' => $config['mysql_database'],
            'user' => $config['mysql_user'],
            'pass' => $config['mysql_password'],
            'port' => $config['mysql_port'] ?? 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
        ]
    ],
    'version_order' => 'creation'
];
