<?php

/**
 * class.helpers.base.php
 *
 * MoseCMS helper class provides a set of helper API for MoseCMS
 *
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
// helpers
//include_once("class.helpers.base.php");

class MosecmsHelperClass extends MosecmsBaseClass {

    function __construct() {
        parent::__construct();
        $this->debug = true;
        $this->version = "0.1.2";
    }

    function getActiveInfo($info) {
        if (empty($info))
            return array();
        $vArray = array();
        foreach ($info as $key => $value) {
            if ($value['statusto'] == 'hide')
                $vArray[$key] = $value;
        }
        return $vArray;
    }

    function getActivePages($pagesInfo) {
        if (empty($pagesInfo))
            return array();
        $vArray = array();
        foreach ($pagesInfo as $page => $value) {
            $vArray[] = $this->fileNamePart($value['name'], "file"); // get File Name
        }
        return $vArray;
    }

    function clean4UrlParm($string) {
        return trim(urldecode($string));
    }

    function clean4AlphaNumeric($string) {
        return trim(preg_replace("/[^a-zA-Z0-9]/", "", $string));
    }

    function clean4Int($integer) {
        return intval($integer);
    }

    function clean4Float($float) {
        return floatval($float);
    }

    function clean4SQL($string) {
        $pattern[0] = '/(\\\\)/';
        $pattern[1] = "/\"/";
        $pattern[2] = "/'/";
        $replacement[0] = '\\\\\\';
        $replacement[1] = '\"';
        $replacement[2] = "\\'";
        return trim(preg_replace($pattern, $replacement, $string));
    }

    function clean4HTML($string) {
        $pattern[0] = '/\&/';
        $pattern[1] = '/</';
        $pattern[2] = "/>/";
        $pattern[3] = '/\n/';
        $pattern[4] = '/"/';
        $pattern[5] = "/'/";
        $pattern[6] = "/%/";
        $pattern[7] = '/\(/';
        $pattern[8] = '/\)/';
        $pattern[9] = '/\+/';
        $pattern[10] = '/-/';
        $replacement[0] = '&amp;';
        $replacement[1] = '&lt;';
        $replacement[2] = '&gt;';
        $replacement[3] = '<br>';
        $replacement[4] = '&quot;';
        $replacement[5] = '&#39;';
        $replacement[6] = '&#37;';
        $replacement[7] = '&#40;';
        $replacement[8] = '&#41;';
        $replacement[9] = '&#43;';
        $replacement[10] = '&#45;';
        return trim(preg_replace($pattern, $replacement, $string));
    }

    function loginForm() {
        $body = $this->tag("label", "Password: ") . $this->password(array('name' => "passw")) . $this->submit(array('value' => "Login"));
        return "<h2>Admin Login</h2><div>" . $this->form("?do=login", $body) . "</div>";
    }

    function logout() {
        session_destroy();
        $this->redirect("index.php", "Log Out!");
    }

    function statusTo($file) {
        (substr($file, -1) == "l") ? $return = "hide" : $return = "show";
        return $return;
    }

    function statusToggle($status) {
        ($status == "hide") ? $return = "show" : $return = "hide";
        return $return;
    }

    function action() {
        $return = $this->getRequestVal("do");
        (isset($return) and $return) ? $action = $return : $action = "view";
        return $action;
    }

    function app() {
        $return = $this->getRequestVal("app");
        (isset($return) and $return) ? $action = $return : $action = "pages";
        return $action;
    }

    function rename($fromFilespec, $toFilespec) {
        $this->debugO(array('name' => $fromFilespec, 'msg' => 'from:', 'debug' => $this->debug));
        $this->debugO(array('name' => $toFilespec, 'msg' => 'to:', 'debug' => $this->debug));
        if (file_exists($toFilespec) or ! file_exists($fromFilespec))
            return false;
        return (rename($fromFilespec, $toFilespec));
    }

    function editorSaveButton($pagecontent) {
        $fileExt = "." . $this->getRequestVal("ext");
        return "<div style='float:left'>\n"
                . $this->hidden(array('value' => $this->getRequestVal("pname"), 'name' => "savepage"))
                . $this->hidden(array('value' => $fileExt, 'name' => "extension"))
                . $this->hidden(array('value' => base64_encode($pagecontent), 'name' => "lastrev"))
                . $this->cancel(array('value' => "Cancel"))
                . $this->submit(array('value' => "Save"))
                . "</div>
		";
    }

    function loadTimer($opt) {
        global $time, $start;
        if ($opt == 'start') {
            //Page load start timer
            $time = microtime();
            $time = explode(" ", $time);
            $time = $time[1] + $time[0];
            $start = $time;
        }
        if ($opt == 'stop') {
            //Stop Timer
            $time = microtime();
            $time = explode(" ", $time);
            $time = $time[1] + $time[0];
            $finish = $time;
            $totaltime = ($finish - $start);
            echo '<p>';
            printf("Page loaded in %f seconds.", $totaltime);
            echo '</p>';
        }
    }

    function showMoseMsg() {
        if (isset($_SESSION['mosemsg']) && $_SESSION['mosemsg']) {
            echo $_SESSION['mosemsg'], "<br />";
            unset($_SESSION['mosemsg']);
        }
    }

    function getInfoChange($value, $field) {
//		if (!isset($value) or !isset($field)) return $return;
        $id = $this->getVal($value, 'id');
        $newValue = $this->getRequestVal($field . $id);
        ($newValue) ? $return = $newValue : $return = $this->getVal($value, $field);
        return $return;
    }

    function writeInfo2File($iInfo, $iFile) {
//	if (empty($iInfo)) return;	// it is ok if file is not there
        $string = '$' . 'info = ' . $this->var2String($iInfo, "format") . ';';
        $protect = "defined( '_MOSECMS' ) or die( 'NOT allowed!' );\n";
        $ftext = "<?php\n" . $protect . $string . "\n?>";
        $this->writeFile($iFile, $ftext);
    }

    function loadInfo($indx) {
        $filename = $this->fileNamePart($this->basename($indx), "file");
        if (empty($indx))
            return;
//		$indx=$iDir."/index.php";
        $return = array();
        if (file_exists($indx))
            include($indx);
        if (isset($info) and count($info) > 0)
            $return = $info; // assume array with info otherwise use filename
        if (empty($return) && isset($$filename) and count($$filename) > 0)
            $return = $$filename;
        return $return;
    }

    function redirect($url, $imsg = "") {
        if (!empty($imsg))
            $_SESSION['mosemsg'] = $imsg;
        if (!headers_sent()) {    //If headers not sent yet... then do php redirect
            header('Location: ' . $url);
            exit;
        } else {                  //If headers are sent... do java redirect... if java disabled, do html redirect.
            echo $this->redirectViaJs($url) . '<noscript>' . $this->redirectViaMeta($url) . '</noscript>';
            exit;
        }
    }

    function redirectViaMeta($url = "", $imsg = "", $delay = 0) {
        if (!empty($url))
            $url = $_SERVER['REQUEST_URI'];
        if (!empty($imsg))
            $_SESSION['mosemsg'] = $imsg;
        print "<meta http-equiv=\"refresh\" content=\"" . $delay . " URL=" . $url . "\" />";
        exit;
    }

    function jsOnclick($js) {
        return "#\" onclick=\"$js";
    }

    function redirectViaJs($url, $imsg = "") {
        if (!empty($imsg))
            $_SESSION['mosemsg'] = $imsg;
        print $this->wrapjs("document.location.href='" . $url . "';");
    }

    function debugP($inVar) { // quick debug print
        $inVar['debug'] = true;
//		$inVar['format']=true;
        $this->debugO($inVar);
    }

    function pr($inVar) {
        if (!empty($inVar))
            $this->debugP($inVar);
    }

    function getFilesList($iDir = "") {
//pr($iDir,"iDir:");
        if (!empty($iDir))
            return $this->filesList($iDir . "*", "fullname", "index|history"); // history|index
    }

    // old staff
}

?>