<?php
require_once dirname(__DIR__) . '/../vendor/autoload.php';
require_once dirname(__DIR__) . '/fns/utilities.php';

use function Env\env;

/**
 * Directory containing all of the site's files
 *
 * @var string
 */
$root_dir = dirname(__DIR__) . '/../';
//echo $root_dir . "\n";

/**
 * Document Root
 *
 * @var string
 */
$webroot_dir = $root_dir . '/web';

/**
 * Use Dotenv to set required environment variables and load .env file in root
 * .env.local will override .env if it exists
 */
if (file_exists($root_dir . '/.env')) {
    $env_files = file_exists($root_dir . '/.env.local')
        ? ['.env', '.env.local']
        : ['.env'];

    $dotenv = Dotenv\Dotenv::createImmutable($root_dir, $env_files, false);

    $dotenv->load();

    $dotenv->required(['API_URL', 'PACKAGE_SLUG']);
    $dotenv->ifPresent('ALLOW_DOWNLOAD')->isBoolean();
}

$allow_download = false;
if( isset( $_ENV['ALLOW_DOWNLOAD'] ) )
  $allow_download = isset($_ENV['ALLOW_DOWNLOAD']) && strtolower($_ENV['ALLOW_DOWNLOAD']) === 'true';
