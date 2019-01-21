<?php
/**
 * bootstrap.php
 * main entry to load core class
 * @copyright  (C) 2008 ongetc.com
 * @license    GNU/GPL http://ongetc.com/gpl.html.
 * @info       ongetc@ongetc.com http://ongetc.com
 * @version    $Id:$
 * @since      File available since Release 0.1
 */
?>
<?php
namespace Libs;
defined('_MOSECMS') or die('NOT allowed!');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
session_start();
global $mosecms;
$iarray = explode(":", getcwd());
$sdir = array_pop($iarray);
include_once($sdir.DS.'vendor'.DS.'autoload.php');
$path = $sdir . DS ."share".DS."core";
//echo "<br />path:".$path;
$loader = $path.DS."libs".DS."NsClassLoader.php";
//echo "<br />loader".$loader;
include_once($path.DS."libs".DS."NsClassLoader.php");
NsClassLoader::$classFolders = array($path.DS."libs"
    ,$path.DS."controller",$path.DS."model");

//echo "<br />load from:";
//print_r(NsClassLoader::$classFolders);    
$autoloader = new NsClassLoader();
//include_once("class.main.php");
if (!isset($mosecms))
    $mosecms = new MosecmsClass();
$mosecms->main();
?>