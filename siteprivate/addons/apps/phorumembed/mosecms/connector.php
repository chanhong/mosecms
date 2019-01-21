<?php

// ======================================================================
// Connector: mosecms 
// Author: Maurice Makaay, modified by Chanh Ong for MoseCMS
//
// This is a phorum embedding connector for the mosecms CMS system.
// ======================================================================

class PhorumConnector extends PhorumConnectorBase {

    var $name = "MoseCMS";

    // URL rewriting to fit Phorum into mosecms. The default rewriting
    // method does not work, because mosecms needs the option and Itemid
    // parameters in the URL to function. We pass on the query string
    // that Phorum needs using an extra parameter "phorum_query".
    function get_url($page, $query_items, $suffix) {
        global $option, $Itemid, $my;
        if (isset($GLOBALS["PHORUM"]["mosecms_url"])) {
            $url = $GLOBALS["PHORUM"]["mosecms_url"] . "&" .
                    "phorum_query={$page}";
            if (count($query_items))
                $url .= "," . implode(",", $query_items);
            if (!empty($suffix))
                $url .= $suffix;

            return $url;
        }
    }

    // URL parsing to handle the non-standard query string, that
    // was produced by get_url().
    function parse_request($query) {
        if (isset($_GET["phorum_query"])) {
            // We can't use $_REQUEST["phorum_query"], because that
            // one is decoded by PHP automatically. We need the 
            // urlencoded value of this field, because else
            // = and , characters will confuse Phorum's request parser.
            $parts = explode("&", $query);
            foreach ($parts as $part) {
                if (substr($part, 0, 13) == "phorum_query=") {
                    $query = substr($part, 13);
                    return parent::parse_request($query);
                }
            }

            // Shouldn't happen, but keep it as a defensive fallback mechanism.
            return parent::parse_request($_REQUEST["phorum_query"]);
        } elseif (isset($_REQUEST["page"])) {
            return parent::parse_request($_SERVER["QUERY_STRING"]);
        } else {
            return "index";
        }
    }

    function hook_common_pre() {
        // Find the path in which mosecms is running.
        $parsed_url = parse_url($_SERVER["PHP_SELF"]);
        $mosecms_path = isset($parsed_url["path"]) ? $parsed_url["path"] : "/";
        // Store the mosecms script URL< which can be used for building URL's.
        // not very pretty but just a proof of concept
        $do = "run";
        $page = isset($_GET['app']) ? $_GET['app'] : "";
        // not very pretty but just a proof of concept
        $mosecms_url = ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https://" : "http://") .
                $_SERVER["HTTP_HOST"] .
                $mosecms_path . "?do=" . $do . "&app=" . $page;
        $GLOBALS["PHORUM"]["mosecms_url"] = $mosecms_url;

        // Add mosecms data to POST_VARS (to make for example the
        // search form work).
        $GLOBALS["PHORUM"]["DATA"]["POST_VARS"] .= "<input type=\"hidden\" name=\"do\" value=\"$do\"/>\n";
        "<input type=\"hidden\" name=\"page\" value=\"$page\"/>\n";
    }

    // Return the template that we want to use.
    function get_template() {
        return "embed_phorum";
    }

    // Return the user_id for the logged in user.
    function get_user_id() {
        // not pretty but just a proof of concept
        return 1; // just hard code the admin for illustration
    }

    function process_page_elements($elements) {
        ?>
        <html>
            <head>
                <title><?php print $elements["title"] ?></title>
                <?php
                print $elements["redirect_meta"];
                print $elements["style"];
                print $elements["head_data"];
                print $elements["rss_link"];
                ?>
            </head>
            <body onload="<?php print $elements["body_onload"] ?>">
                <?php print $elements["body_data"] ?>
                <hr/>
            </body>
        </html> <?php
            }

            // Setup Phorum's page elements in the master templating system.
            function xprocess_page_elements($elements) {
                global $mainframe;

                $mainframe->_head['title'] = $elements['title'];
                $mainframe->_head['custom'][] = $elements["style"];
                $mainframe->_head['custom'][] = $elements["rss_link"];
                $mainframe->_head['custom'][] = $elements["redirect_meta"];
                $mainframe->_head['custom'][] = $elements["head_data"];

                if (isset($elements["unexpected_output"])) {
                    ?>
            <div style="border:1px solid darkred;font-size:11px;padding:10px">
                <strong>There was unexpected output from Phorum. This might 
                    indicate a problem:</strong><br/>
                <?php print htmlspecialchars($elements["unexpected_output"]) ?>
            </div><br/> <?php
            }

            print $elements["body_data"];

            // We cant injec't a body onload event in the mosecms template,
            // so we put the onload script after the body.
            print '<script type="text/javascript">';
            print $elements["body_onload"];
            print '</script>';
        }

        // Return a redirect page for certain events or return NULL in 
        // case no redirection has to take place.
        function get_redirect_page($event) {
            switch ($event) {
                case "admin-only":
                case "disabled":
                    return "/";
                    break;

                default:
                    return NULL;
            }
        }

        // Return an array of user fields that may be edited through
        // Phorum's user control center.
        function get_slave_fields() {
            return array
                (
                #"real_name",
                "signature",
                #"email",
                "hide_email",
                "hide_activity",
                "moderation_email",
                "tz_offset",
                "is_dst",
                "user_language",
                "user_template",
                "threaded_list",
                "threaded_read",
                "email_notify",
                "show_signature",
                "pm_email_notify",
            );
        }

    }
    ?>
