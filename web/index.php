<?php
global $allow_download, $webroot_dir;
require_once dirname( __DIR__ ) . '/lib/fns/bootstrap.php';

use function UpdateServer\utilities\{getLatestPackage,getReadme};
$package_info = getLatestPackage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CMC Video Player WordPress Plugin</title>
  <link rel="stylesheet" href="assets/flexboxgrid.min.css" />
  <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12"><img src="assets/banner-1544x500.jpg" style="width: auto; max-width: 100%;" /></div>
    </div>
    <div class="row">
      <div class="col-xs-2 hide-mobile"><img src="assets/icon-256x256.jpg" /></div>
      <div class="col-xs">
        <h1 class="plugin-title">CMC Video Player</h1>
        <span class="byline">By a <a href="https://mwender.com">Michael Wender</a></span>
      </div>
      <div class="col-md-3 end-md"><?php if( $allow_download ){ ?><a href="<?= $package_info['package']; ?>" class="button">Download</a><?php } ?></div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <?= getReadme( $webroot_dir . '/packages/' . $_ENV['PACKAGE_SLUG'] . '_' . $package_info['version'] . '.zip' ) ?>
      </div>
      <div class="col-md-4 col-xs-12">
        <table>
          <tr>
            <th style="width: 50%">Version:</th>
            <td style="width: 50%"><?= $package_info['version'] ?></td>
          </tr>
          <tr>
            <th>Last updated:</th>
            <td><?= $package_info['last_update'] ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="footer">&copy; <?= date('Y') ?>. All rights reserved.</div>
</body>
</html>