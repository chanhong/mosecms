<?php

/**
 * addons\extensions\adodb\index.php
 *
 * A extension demo to show case ADOdb features
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

// use this in content to call this function
//echo adodb_demo();

function adodb_demo() {
    global $mosecms;
    global $db;

// Db Settings
    $server = $mosecms->config['dbhost'];
    $driver = $mosecms->config['dbtype'];
    $user = $mosecms->config['dblogin'];
    $password = $mosecms->config['dbpw'];
    $database = $mosecms->config['dbname'];

    $db = ADONewConnection($driver); # eg. 'mysql' or 'oci8' 
    $db->debug = true;
    $db->Connect($server, $user, $password, $database);

    $rs = $db->Execute('show tables');
    if ($rs)
        rs2html($rs);
}

?>