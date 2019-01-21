<?php

/**
 * addons\connectors\mambo\index.php
 *
 *  A connector to bridge with Mambo articles
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
global $database;
global $mosConfig_absolute_path;
global $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix;
global $mosConfig_offset;
$curr_dir = getcwd();

//$mambo_dir = $_SERVER["DOCUMENT_ROOT"] . "/mos463";
$mambo_dir = "mos463";
if (file_exists($mambo_dir)) {
    chdir($mambo_dir);
//echo getcwd();
    require_once( 'configuration.php' );
    require_once( $mosConfig_absolute_path . "/includes/database.php" );
    chdir($curr_dir);
//echo getcwd();
}
?>