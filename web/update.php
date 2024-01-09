<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use function Env\env;

/**
 * Initialize our JSON data variable
 */
$result = [];

/**
 * Directory containing all of the site's files
 *
 * @var string
 */
$root_dir = dirname(__DIR__);

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
}

if( ! isset( $_ENV['API_URL'] ) || empty( $_ENV['API_URL'] ) ){
  $result['errors'][] = 'API_URL not found!';
} else {
  $package_url = $_ENV['API_URL'] . '/packages/';
}

if( ! isset( $_ENV['PACKAGE_SLUG'] ) || empty( $_ENV['PACKAGE_SLUG'] ) ){
  $result['errors'][] = 'PACKAGE_SLUG not found!';
} else {
  $pattern = '/' . $_ENV['PACKAGE_SLUG'] . '_(\d+\.\d+\.\d+)\.zip$/';
}

if( isset( $_ENV['API_URL'] ) && isset( $_ENV['PACKAGE_SLUG'] ) ){
  // Step 1: Retrieve all files in the /plugin/ directory matching the pattern
  $files = glob( $webroot_dir . '/packages/' . $_ENV['PACKAGE_SLUG'] . '_*.zip' );

  // Step 2: Sort the filenames by version number, descending
  usort($files, function ($a, $b) {
      // Extract version numbers from filenames
      preg_match('/(\d+\.\d+\.\d+)/', $a, $versionA);
      preg_match('/(\d+\.\d+\.\d+)/', $b, $versionB);

      // Compare versions
      return version_compare($versionB[1], $versionA[1]);
  });
}

// Step 3: Return the first filename in a JSON object
header('Content-Type: application/json');
if( ! empty( $files ) && ! array_key_exists( 'errors', $result ) ){
  $firstFile = $files[0];

  preg_match($pattern, $firstFile, $matches);

  $version = $matches[1];

  $result = [
      'version' => $version,
      'package' => $package_url . $matches[0],
  ];

  echo json_encode($result, JSON_PRETTY_PRINT);
} else {
  echo json_encode( $result, JSON_PRETTY_PRINT );
}
