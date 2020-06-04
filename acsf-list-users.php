#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use ACSF\AcsfUsers;

$acsf = new AcsfUsers();
// Get list of sites.
$list_sites = $acsf->getAllSites();

// Iterate on them to execute the drush command.
foreach ($list_sites as $site) {
    $acsf->execDrushUserInfo($site['alias'], $argv[1]);
}
