<?php

/**
 * addons\connectors\adodb\index.php
 *
 * A connector to preload ADOdb library
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
$mosecms->usingPackage("adodb5", "adodb-errorhandler.inc.php");
$mosecms->usingPackage("adodb5", "adodb.inc.php");
$mosecms->usingPackage("adodb5", "adodb-xmlschema.inc.php");
$mosecms->usingPackage("adodb5", "tohtml.inc.php");
$mosecms->usingPackage("adodb5", "adodb-pager.inc.php");
?>