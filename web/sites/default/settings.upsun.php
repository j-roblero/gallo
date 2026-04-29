<?php

/**
 * Upsun (Platform.sh) environment settings.
 */

use Platformsh\ConfigReader\Config;

$platformConfig = new Config();

if (!$platformConfig->isValidPlatform()) {
  return;
}

// Database
if ($platformConfig->hasRelationship('database')) {
  $creds = $platformConfig->credentials('database');
  $databases['default']['default'] = [
    'driver'    => 'mysql',
    'database'  => $creds['path'],
    'username'  => $creds['username'],
    'password'  => $creds['password'],
    'host'      => $creds['host'],
    'port'      => $creds['port'],
    'prefix'    => '',
    'collation' => 'utf8mb4_general_ci',
    'init_commands' => [
      'isolation_level' => 'SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED',
    ],
  ];
}

// Redis
if ($platformConfig->hasRelationship('redis')) {
  $creds = $platformConfig->credentials('redis');
  $settings['redis.connection']['interface'] = 'PhpRedis';
  $settings['redis.connection']['host']      = $creds['host'];
  $settings['redis.connection']['port']      = $creds['port'];
  $settings['cache']['default']              = 'cache.backend.redis';
  $settings['cache']['bins']['bootstrap']    = 'cache.backend.chainedfast';
}

// Trusted host patterns
if ($platformConfig->isValidPlatform()) {
  $routes = $platformConfig->routes();
  foreach ($routes as $url => $route) {
    if ($route['type'] === 'upstream' && str_contains($route['upstream'], 'gallopinto')) {
      $host = parse_url($url, PHP_URL_HOST);
      if ($host) {
        $settings['trusted_host_patterns'][] = '^' . preg_quote($host, '/') . '$';
      }
    }
  }
}

// File paths
$settings['file_public_path']  = 'sites/default/files';
$settings['file_private_path'] = '/app/private';
$settings['file_temp_path']    = '/tmp';

// Hash salt desde variable de entorno de Upsun
if ($platformConfig->isValidPlatform()) {
  $settings['hash_salt'] = getenv('PLATFORM_PROJECT_ENTROPY') ?: '';
}
