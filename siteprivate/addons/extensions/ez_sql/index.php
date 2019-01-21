<?php

/**
 * addons\extensions\ez_sql\index.php
 *
 * A extension demo to show case ez_sql features
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
//echo ezsql_demo();

function ezsql_demo() {
    global $mosecms;
    global $db;
    $server = $mosecms->config['dbhost'];
    $user = $mosecms->config['dblogin'];
    $password = $mosecms->config['dbpw'];
    $database = $mosecms->config['dbname'];

    // Initialise database object and establish a connection
    // at the same time - db_user / db_password / db_name / db_host
    $db = new ezSQL_mysql($user, $password, $database, $server);

    /*     * ********************************************************************
     *  ezSQL demo for mySQL database
     */

    // Demo of getting a single variable from the db
    // (and using abstracted function sysdate)
    $current_time = $db->get_var("SELECT " . $db->sysdate());
    print "ezSQL demo for mySQL database run @ $current_time";

    // Print out last query and results..
    $db->debug();

    // Get list of tables from current database..
    $my_tables = $db->get_results("SHOW TABLES", ARRAY_N);

    // Print out last query and results..
    $db->debug();

    if (!empty($my_tables)) {
        // Loop through each row of results..
        foreach ($my_tables as $table) {
            // Get results of DESC table..
            $db->get_results("DESC $table[0]");

            // Print out last query and results..
            $db->debug();
        }
    }
}

?>