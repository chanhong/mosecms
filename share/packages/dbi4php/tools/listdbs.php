#!/usr/local/bin/php -q
<?php
/* $Id$
 *
 * Description:
 * 	This is a tool for listing which databases that dbi4php supports
 * 	are supported by your PHP installation.
 *
 * 	This tool is part of the dbi4php package:
 * 	  http://dbi4php.sourceforge.net
 *
 * Usage:
 * 	php listdbs.php
 *
 * ******************************************************************* */

// This is a list of all databases that dbi4php supports.
$dbs = array(
    'MySQL (mysql)' => 'mysql_pconnect',
    'MySQL (mysqli)' => 'mysqli_connect',
    'MS SQL Server (mssql)' => 'mssql_pconnect',
    'Oracle (oracle)' => 'mssql_pconnect',
    'PostgreSQL (postgresql)' => 'pg_pconnect',
    'ODBC (odbc)' => 'odbc_pconnect',
    'IBM DB2 (ibm_db2)' => 'db2_pconnect',
    'Interbase (ibase)' => 'ibase_pconnect',
    'SQLite (sqlite)' => 'sqlite_popen',
);

Header("Content-type: text/plain"); // in case you run this from a URL

print "Your PHP installation supports the following databases:\n\n";

foreach ($dbs as $dbname => $function) {
    if (function_exists($function)) {
        print "  - " . $dbname . "\n";
    }
}
?>
