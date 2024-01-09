<?php
namespace UpdateServer\utilities;

function getFileModificationTime($filePath) {
    if (!file_exists($filePath)) {
        return "File does not exist";
    }

    $fileModTime = filemtime($filePath);
    $timeDiff = time() - $fileModTime;

    $units = array(
        'year'   => 60 * 60 * 24 * 365,
        'month'  => 60 * 60 * 24 * 30,
        'day'    => 60 * 60 * 24,
        'hour'   => 60 * 60,
        'minute' => 60
    );

    foreach ($units as $unit => $value) {
        if ($timeDiff >= $value) {
            $numberOfUnits = floor($timeDiff / $value);
            return $numberOfUnits.' '.$unit.(($numberOfUnits>1)?'s':'').' ago';
        }
    }

    return 'Just now';
}

function getLatestPackage(){
  global $webroot_dir;

  if( ! isset( $_ENV['API_URL'] ) || empty( $_ENV['API_URL'] ) )
    return false;

  if( ! isset( $_ENV['PACKAGE_SLUG'] ) || empty( $_ENV['PACKAGE_SLUG'] ) )
    return false;

  $files = glob( $webroot_dir . '/packages/' . $_ENV['PACKAGE_SLUG'] . '_*.zip' );

  usort($files, function ($a, $b) {
      // Extract version numbers from filenames
      preg_match('/(\d+\.\d+\.\d+)/', $a, $versionA);
      preg_match('/(\d+\.\d+\.\d+)/', $b, $versionB);

      // Compare versions
      return version_compare($versionB[1], $versionA[1]);
  });

  $latestPackage = false;
  if( ! empty( $files ) )
    $latestPackage = $files[0];

  // Extract the version number from the latest package filename:
  $pattern = '/' . $_ENV['PACKAGE_SLUG'] . '_(\d+\.\d+\.\d+)\.zip$/';
  preg_match( $pattern, $latestPackage, $matches );

  if( $matches ){
    $package_info = [
      'version'     => $matches[1],
      'package'     => str_replace( 'update.php', '', $_ENV['API_URL'] ) . 'packages/' . $matches[0],
      'last_update' => getFileModificationTime( $webroot_dir . '/packages/' . $_ENV['PACKAGE_SLUG'] . '_' . $matches[1] . '.zip' ),
    ];
  } else {
    $package_info = [
      'version'     => '0.0.0',
      'package'     => null,
      'last_update' => '---',
    ];
  }

  return $package_info;
}