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

  <!-- Essential Meta Tags -->
  <title>Sellers.json Editor WordPress Plugin</title>
  <meta name="description" content="The Sellers.json Editor for WordPress provides a backend interface for editing your site's sellers.json.">
  <meta name="keywords" content="sellers.json,wordpress plugin">
  <meta name="author" content="Wenmark Digital Solutions">
  <meta name="robots" content="index, follow">
  
  <!-- Open Graph Meta Tags (for social media sharing) -->
  <meta property="og:title" content="Sellers.json Editor WordPress Plugin">
  <meta property="og:description" content="The Sellers.json Editor is the first and only plugin for editing your sellers.json directly inside the WordPress admin.">
  <meta property="og:image" content="https://sellers-json.wenmarkdigital.com/assets/og-image_1880x984.png">
  <meta property="og:url" content="https://sellers-json.wenmarkdigital.com">
  <meta property="og:type" content="website">

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
        <h1 class="plugin-title">Sellers.json Editor for WordPress</h1>
        <span class="byline">By <a href="https://wenmarkdigital.com">Wenmark Digital Solutions</a></span>
      </div>
      <div class="col-md-3 end-md"><?php if( $allow_download ){ ?><a href="<?= $package_info['package']; ?>" class="button">Download</a><?php } ?></div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <p style="background-color: #eee; padding: .5em; border-radius: 5px;">If you need this plugin for your website, please <a href="https://wenmarkdigital.com/contact/">contact Wenmark Digital Solutions</a>.</p>
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
  <div class="footer">&copy; <?= date('Y') ?> Wenmark Digital Solutions. All rights reserved.</div>
</body>
</html>