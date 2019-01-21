<?php

/**
 * addons\connectors\ez_sql\index.php
 *
 * A connector to preload ez_sql library
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
// change to different type of db here!
$dbtype = $mosecms->config['dbtype'];
; //  available type: mysql, mssql, oracle8_9, pdo, postgresql, sqlite
$packageDir = "ez_sql";
//	$ez_sql_core = "ez_sql".DIRECTORY_SEPARATOR."shared".DIRECTORY_SEPARATOR."ez_sql_core.php";
//	$ez_sql_db = "ez_sql".DIRECTORY_SEPARATOR.$dbtype.DIRECTORY_SEPARATOR."ez_sql_".$dbtype.".php";
$ez_sql_core = "shared" . DIRECTORY_SEPARATOR . "ez_sql_core.php";
$ez_sql_db = $dbtype . DIRECTORY_SEPARATOR . "ez_sql_" . $dbtype . ".php";
$mosecms->usingPackage($packageDir, $ez_sql_core); // Include ezSQL core
$mosecms->usingPackage($packageDir, $ez_sql_db); // Include ezSQL database specific component
// try load from core
if (!class_exists('ezSQLcore')) {
    $mosecms->usingPackageFromCore($packageDir, $ez_sql_core);
}
if (!class_exists('ezSQL_mysql')) {
    $mosecms->usingPackageFromCore($packageDir, $ez_sql_db);
}
?>