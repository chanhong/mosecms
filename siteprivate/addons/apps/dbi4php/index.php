<?php
/**
 * addons\apps\dbi4php\index.php
 *
 * A sample addons app to show case the use of DBi4PHP
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
echo dbi4php_demo();

function dbi4php_demo() {
    global $mosecms;
    /*
     * This is a simple example of how you can use the dbi4php tool.
     * It demonstrates a query with dbi_query and an insert with dbi_execute.
     */

// Global settings for dbi4php
    $GLOBALS['db_type'] = $mosecms->config['dbtype'];

// Db Settings
    $db_host = $mosecms->config['dbhost'];
    $db_login = $mosecms->config['dblogin'];
    $db_password = $mosecms->config['dbpw'];
    $db_database = $mosecms->config['dbname'];
    ?>
    <?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
    <!DOCTYPE html 
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
        <head>
            <title>dbi4php: Package Tracking Demo</title>
        </head>
        <body>

            <h1>Package Tracking</h1>

            <?php
            $c = dbi_connect($db_host, $db_login, $db_password, $db_database);
            if (!$c) {
                fatal_error(dbi_error());
            }

// First handle new package add (if there is one)
            $descr = $_REQUEST['descr'];
            $code = $_REQUEST['code'];
            $type = $_REQUEST['type'];
            if (!empty($code) && !empty($type)) {
                // Remove any slashes add automatically by PHP if magic_quotes_gpc is
                // enabled.  Only remove slashes when calling dbi_execute.  If you
                // were to insert with dbi_query, you would still want to escape certain
                // characters.  It's always more secure to use dbi_execute for any query
                // that contains data from a URL or submitted form since it will prevent
                // SQL Injection.
                if (get_magic_quotes_gpc()) {
                    $descr = stripslashes($descr);
                    $code = stripslashes($code);
                    $type = stripslashes($type);
                }
                if (empty($descr))
                    $descr = $type . ' ' . $descr;
                $date = date('Ymd');
                $sql = 'INSERT INTO package ( descr, type, date_entered, code ) ' .
                        ' VALUES ( ?, ?, ?, ? )';
                if (!dbi_execute($sql, array($descr, $type, $date, $code))) {
                    // Error on INSERT
                    fatal_error(dbi_error());
                }
                // Success!
            }

            $sql = "SELECT descr, type, date_entered, code FROM package " .
                    "WHERE received = 'N' ORDER BY date_entered DESC";

// make query
            $res = dbi_query($sql);

            if (!$res) {
                fatal_error(dbi_error());
            }
            ?>
            <table><tr><th>Description</th><th>Date Entered</th><th>Shipper</th><th>Tracking No.</th></tr>
                <?php
// Loop through each entry
                $cnt = 0;
                while ($row = dbi_fetch_row($res)) {
                    $cnt++;
                    printf(
                            "<tr><td>%s</td><td valign=\"top\">%s</td><td valign=\"top\">%s</td>", htmlspecialchars($row[0]), $row[2], $row[1]);
                    if ($row[1] == "UPS") {
                        $url = ups_tracking_url($row[3]);
                    } else if ($row[1] == "FedEx") {
                        $url = fedex_tracking_url($row[3]);
                    } else {
                        $url = "";
                    }
                    printf("</td><td valign=\"top\"><a href=\"%s\">%s</a></td></tr>\n", $url, $row[3]);
                    print "</tr>\n";
                }
                print "</table>\n";

                if ($cnt == 0) {
                    print "No packages.<br/>\n";
                }

// Free up query resource
                dbi_free_result($res);
                ?>
                <br />
                <br />
                <br />

                <h3>Add New Package</h3>

                <form action="?do=run&app=dbi4php" method="post">
                    <table>
                        <tr><td>Description:</td>
                            <td><input name="descr" size="50"></td></tr>
                        <tr><td>Method:</b></td>
                            <td><select name="type"><option value="UPS">UPS</option>
                                    <option value="FedEx">FedEx</option>
                                </select></td></tr>
                        <tr><td>Tracking No.:</td>
                            <td><input name="code" size="25"></td></tr>
                        <tr><td colspan="2" align="center"><input type="submit" value="Add Package" /></td></tr>
                    </table>
                </form>

                </td></tr></table>

        </body></html>
    <?php
    dbi_close($c);
}

function fatal_error($msg) {
    echo "<h2>Error</h2><p>" . $msg . "</p></body></html>\n";
    if (!empty($c))
        dbi_close($c);
    exit;
}

function ups_tracking_url($code) {
    $ret = "http://wwwapps.ups.com/etracking/tracking.cgi?" .
            "tracknums_displayed=5&TypeOfInquiryNumber=T&HTMLVersion=4.0" .
            "&sort_by=status&InquiryNumber1=" .
            $code . "&track.x=16&track.y=9";

    return $ret;
}

function fedex_tracking_url($code) {
    $ret = "http://www.fedex.com/cgi-bin/tracking?tracknumbers=" .
            $code . "&action=track&language=english&cntry_code=us";
    return $ret;
}
?>