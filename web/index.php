<?php
global $allow_download;
require_once dirname( __DIR__ ) . '/lib/fns/bootstrap.php';

use function UpdateServer\utilities\{getLatestPackage};
$package_info = getLatestPackage();
?>
<html>
<head>
  <title>Updates Server - Test</title>
  <link rel="stylesheet" href="assets/flexboxgrid.min.css" />
  <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12"><img src="assets/banner-1544x500.jpg" style="width: auto; max-width: 100%;" /></div>
    </div>
    <div class="row">
      <div class="col-md-2"><img src="assets/icon-256x256.jpg" /></div>
      <div class="col-md">
        <h1 class="plugin-title">Your Plugin</h1>
        <span class="byline">By a Plugin Developer</span>
      </div>
      <div class="col-md-3 end-md"><?php if( $allow_download ){ ?><a href="<?= $package_info['package']; ?>" class="button">Download</a><?php } ?></div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <h2>Description</h2>
        <p>Describe your plugin here. Be sure to include any pertinent details. Oftentimes this description is the first thing users will read about your plugin.</p>
        <h2>Changelog</h2>
        <h3>1.0.0</h3>
        <ul>
          <li>First release.</li>
        </ul>
      </div>
      <div class="col-md-4">
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