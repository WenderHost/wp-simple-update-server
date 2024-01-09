<?php
require_once dirname( __DIR__ ) . '/lib/fns/bootstrap.php';
use function UpdateServer\utilities\{getLatestPackage};

/**
 * Initialize our JSON data variable
 */
$result = [];

if( ! isset( $_ENV['API_URL'] ) || empty( $_ENV['API_URL'] ) )
  $result['errors'][] = 'API_URL not found!';

if( ! isset( $_ENV['PACKAGE_SLUG'] ) || empty( $_ENV['PACKAGE_SLUG'] ) )
  $result['errors'][] = 'PACKAGE_SLUG not found!';

$package_info = getLatestPackage();
// Step 3: Return the first filename in a JSON object
header('Content-Type: application/json');
if( is_array( $package_info ) && ! array_key_exists( 'errors', $result ) ){
  echo json_encode( $package_info, JSON_PRETTY_PRINT );
} else {
  echo json_encode( $result, JSON_PRETTY_PRINT );
}
