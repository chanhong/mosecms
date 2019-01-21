<?php

/**
 * addons\connectors\dbi4php\index.php
 *
 *  A connector to preload DBi4PHP library
 *
 * @copyright  (C) 2008 ongetc.com
 * @license    GNU/GPL http://ongetc.com/gpl.html.
 * @info       ongetc@ongetc.com http://ongetc.com
 * @version    $Id:$
 * @since      File available since Release 0.1
 */
?>
<?php

defined('_MOSECMS') or die('NOT allowed!');
global $mosecms;
// Global settings for dbi4php
$GLOBALS['db_type'] = $mosecms->config['dbtype'];
$packageDir = "dbi4php";
$package = "dbi4php.php";
//  $dbi4php_core = "dbi4php".DIRECTORY_SEPARATOR."dbi4php.php";
$mosecms->usingPackage($packageDir, $package); // Include dbi4php core
if (!function_exists('dbi_connect')) {
    $mosecms->usingPackageFromCore($packageDir, $package);
} // try load from core for final attempt
?>