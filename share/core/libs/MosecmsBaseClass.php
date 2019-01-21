<?php

/**
 * class.helpers.base.php
 *
 * MoseCMS helper base class that contain the helper base API
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

class MosecmsBaseClass {

    var $debug;
    var $version;

    function __construct() {
        
    }

// IO or stream related
    function fileExtPart($filespec) {
        if (!file_exists($filespec))
            return;
        $path_parts = pathinfo($filespec);
        (isset($path_parts['extension'])) ? $ext = $path_parts['extension'] : $ext = "";
        return $ext;
    }

    function getRequestVal($ivalue, $type = "") {
        $return = $this->getVal($_REQUEST, $ivalue);
        return stripslashes($return);
    }

    function fileNamePart($filespec, $fType = "dir") {
        $filename = basename($filespec);
        switch (strtolower($fType)) {
            case "ext":
                $pos = $this->stripos($filename, ".");
                ($pos > 0) ? $return = substr($filename, $pos + 1) : $return = "";
                break;
            case "file":
                $pos = $this->stripos($filename, ".");
                ($pos > 0) ? $return = substr($filename, 0, $pos) : $return = $filename;
                break;
            default: // dir or fullname
                $return = $filename;
        }
        return $return;
    }

    function notThisFile($filespec, $exclude = "index") {
        $pattern = '/^(' . $exclude . ')$/'; // index|history
        $fname = $this->fileNamePart(basename($filespec), "file");
        preg_match($pattern, $fname, $matches);
        (!$matches) // <> match 
                        ? $return = $filespec : $return = "";
        return $return;
    }

    function basename($filespec) {
        $path_parts = pathinfo($filespec);
        (count($path_parts) > 0) ? $return = $path_parts['basename'] : $return = "";
        return $return;
    }

    function readFile2Array($fName) {
        return explode(PHP_EOL, $this->readFile($fName));
    }

    function readFile($fName) {
        $contents = "";
        if ($handle = @fopen($fName, 'r')) {
            $contents = fread($handle, filesize($fName));
            fclose($handle);
        }
        return $contents;
    }

    function writeFile($fName, $cText) {
        $this->debugO(array('name' => $fName, 'msg' => 'writeFile fName: ', 'debug' => $this->debug, 'format' => ''));
        if ($handle = @fopen($fName, 'w')) {
            $this->debugO(array('name' => $fName, 'msg' => 'Write to fName: ', 'debug' => $this->debug, 'format' => ''));
            fwrite($handle, $cText);
            fclose($handle);
        }
        return $handle;
    }

    function writeFileAppend($path, $ftext) {
        $filehandle = fopen($path, "w+");
        fputs($filehandle, $ftext);
        fclose($filehandle);
    }

    function fileExist($filespec) {
        (file_exists($filespec)) ? $return = $filespec : $return = "";
        return $return;
    }

    function var2String($var, $format = "") {
        $code = "";
        if (is_array($var)) {
            $code = 'array(' . "\n";
            foreach ($var as $key => $value) {
                ($format) ? $yestab = "\t" : $yestab = "";
                $code .= $yestab . "'$key'=>" . $this->var2String($value) . ',';
                if ($format)
                    $code .= "\n";
            }
            $code = chop($code, ','); //remove unnecessary coma
            $code .= ')';
            return $code;
        } else {
            if (is_string($var)) {
                return "'" . $this->escapeSingleQuote($var) . "'";
            } elseif (is_numeric($var)) {
                return $var;
            } elseif (is_bool($code)) {
                return ($code ? 'TRUE' : 'FALSE');
            } else {
                return 'NULL';
            }
        }
    }

    function escapeSingleQuote($string) { // fix single quote problem with fckeditor
        return str_replace("'", "&#039", str_replace("\n", "", str_replace("\r", "", $string)));
    }

    function quote($inVar) {
        return "\"" . $inVar . "\"";
    }

    function debugO($inVar) { // only when debug is true
        if (!$this->getVal($inVar, 'debug'))
            return;
        $formated = $this->getVal($inVar, 'format');
        $msg = $this->getVal($inVar, 'msg');
        $name = $this->getVal($inVar, 'name');
        $output = print_r($name, true);
        if ($formated)
            $output = "<pre>$output</pre>";
        echo "$msg$output<br />";
    }

    function debug($istring, $msg = "", $formated = "") {
        if (empty($istring))
            return;
        $output = print_r($istring, true);
//		$output=var_export($istring);
        if ($formated)
            $output = "<pre>$output</pre>";
        echo "$msg:$output<br />";
    }

    function wrapjs($intext, $src = "") {
        ($src) ? $osrc = "<script type='text/javascript' src='$src'></script>" : $osrc = "";
        ($intext) ? $otext = "<script type='text/javascript'><!-- \n" . $intext . "\n //--></script>" : $otext = "";
        return $osrc . $otext;
    }

    function sortArrayByField($original, $field, $descending = false) {
        if (empty($original))
            return array();
        $sortArr = array();
        foreach ($original as $key => $value)
            $sortArr[$key] = $value[$field];
        ( $descending ) ? arsort($sortArr) : asort($sortArr);
        $resultArr = array();
        foreach ($sortArr as $key => $value)
            $resultArr[$key] = $original[$key];
        return $resultArr;
    }

    function delete($page = "") {
        $status = false;
        if (file_exists($page)) {
            if (unlink($page))
                $status = true;
        }
        return $status;
    }

    function getIncludeContent($filename) {
        if (is_file($filename)) {
            ob_start();
//			$this->debugO(array('name'=>$filename,'msg'=>'getIncludeContent:','debug'=>$this->debug));
            include $filename;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
        return false;
    }

    function getFolderList($fPath) {
        return preg_grep("/[^.]/", scandir($fPath));
    }

    function filesList($filespec, $fType = "dir", $exclude = "index") {
//pr($filespec,"filespec:");
        $eArray = array();
//		if (!$filesListArray=$this->safe_glob($filespec)) return;
//    $filesListArray=$this->safe_glob($filespec);
        $filesListArray = glob($filespec);
        foreach ($filesListArray as $eachFileName) {
            switch (strtolower($fType)) {
                case "dir":
                    $ea = $this->basename($eachFileName); // get Dir Name
                    break;
                default:
                    $ea = $this->fileNamePart($eachFileName, $fType); // get File Name option fullname or file
            }
            if ($ea = $this->notThisFile($ea, $exclude))
                $eArray[] = $ea;
        }
        return $eArray;
    }

    function getLastArrayItem($iArray, $field) {
        if (empty($iArray))
            return array();
        $iArray = $this->sortArrayByField($iArray, $field);
        end($iArray);
        $lastPage = array_pop($iArray);
        return $lastPage[$field];
    }

    function readPageContent($filespec) {
        if (!file_exists($filespec))
            return;
        $pagecontent = " ";
        $fsize = filesize($filespec);
        if ($fsize > 0) {
            $filehandle = fopen($filespec, "r");
            $pagecontent = fread($filehandle, $fsize);
// may be this fix the fckedit problem
//			$pagecontent = htmlentities(fread($filehandle, $fsize));	// messup some special chars
            fclose($filehandle);
        }
        return $pagecontent;
    }

    function siteRoot() {
        $uri = explode("/", $_SERVER['REQUEST_URI']);
        (!empty($uri) && isset($uri[1]) && $uri[1] <> "") ? $siteFolder = "/" . $uri[1] : $siteFolder = "";
        return $siteFolder;
    }

    function siteUrlName() {
        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
        return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port;
    }

    function siteAbsDir() {
        return $this->stripDriveLetter(getcwd());
    }

    function siteUrl() {
        return $this->siteUrlName() . $this->siteRoot();
    }

    function fullUrl() {
        return $this->siteUrlName() . $_SERVER['REQUEST_URI'];
    }

    function lastPart($inString, $inParse = "/") {
        $tArray = explode($inParse, $inString);
        $last = end($tArray); // get the last item
        (!empty($last)) ? $return = $last : $return = "";
        return $return;
    }

// Don't touch!  Work solid!
    function getVal($inVar, $inField = "", $inValue = "") {
        (is_array($inVar) && (isset($inVar[$inField]))) ? $var = $inVar[$inField] : $var = $inValue;
        (!empty($var)) ? $return = $var : $return = $inValue;
        return $return;
    }

// Don't touch!  Work solid!
// html related
    function href($inVar) {
        $str = "";
        foreach ($inVar as $key => $value) {
            if ($key && $value)
                $str .= "&$key=$value";
        }
        (!empty($str)) ? $return = "?" . substr($str, 1) : $return = "";
        return $return;
    }

    function img($iLink, $iID, $iWidth = "", $iHeight = "", $iAlt = "") {
        (!empty($iWidth)) ? $sWidth = " width='$iWidth'" : $sWidth = "";
        (!empty($iHeight)) ? $sHeight = " height='$iHeight'" : $sHeight = "";
        (!empty($iAlt)) ? $sAlt = " alt='$iAlt'" : $sAlt = "";
        (!empty($iLink) && !empty($iID)) ? $return = "<img src=" . $this->quote($iLink) . " id=" . $this->quote($iID) . "$sWidth$sHeight$sAlt />" : $return = "";
        return $return;
    }

    function a($iLink, $iName, $iTarget = "") {
        (!empty($iTarget)) ? $sTarget = " target='$iTarget'" : $sTarget = "";
        (!empty($iLink) && !empty($iName)) ? $return = "<a" . $sTarget . " href=" . $this->quote($iLink) . ">" . $iName . "</a>" : $return = "";
        return $return;
    }

    function tag($iTag, $iValue) {
        (!empty($iTag) and ! empty($iValue)) ? $return = "<$iTag>$iValue</$iTag>" : $return = "";
        return $return;
    }

    function form($iAction, $iBody) {
        (!empty($iAction) && !empty($iBody)) ? $return = "<form action=" . $this->quote($iAction) . " method=\"post\">" . $iBody . "</form>" : $return = "";
        return $return;
    }

    function option($eachOption, $iselect = "") {
        ($iselect) ? $selected = " selected=" . $this->quote($iselect) : $selected = "";
        return "<option value=" . $this->quote($eachOption) . $selected . ">" . $eachOption . "</option>";
    }

//<input type="checkbox" id="cb1" name="cid[]" value="262" onclick="isChecked(this.checked);" 
    function inputCheckbox($inVar) {
        if (!$inVar)
            return;
        if (!isset($inVar['type']))
            $inVar['type'] = "checkbox"; // if type set use it otherwise override by checkbox
        return "\n" . $this->input($inVar);
    }

    function inputRadio($strArray, $varName, $defaultValue) {
        $checkedNotRadio = '';
        if (!$strArray)
            return;
        foreach ($strArray as $key => $ea)
            ($key <> $defaultValue) ? $checkedNotRadio = $this->input(array('type' => 'radio', 'name' => $varName, 'value' => $key)) . $ea : $checkedRadio = $this->input(array('type' => 'radio', 'name' => $varName, 'value' => $key, 'checked' => 'checked')) . $ea;
        return "\n" . $checkedRadio . $checkedNotRadio;
    }

    function optionList($strArray, $varName, $defaultValue, $onEvent = "") {
        $availOptions = '';
        if (!$strArray)
            return;
        $currentOption = $this->option($defaultValue, "selected");
        foreach ($strArray as $ea)
            if ($ea <> $defaultValue)
                $availOptions .= $this->option($ea) . "\n";
        return "<select name=" . $this->quote($varName) . " id=" . $this->quote($varName) . " " . $onEvent . ">\n" . $currentOption . "\n" . $availOptions . "</select>";
    }

    function input($inVar) {
        //	array('type'=>type, 'value'=>value, 'name'=>name, 'event'=>event, 'id'=>id, 'class'=>class...);
        (isset($inVar['checked'])) ? $checked = " checked=" . $this->quote($inVar['checked']) : $checked = "";
        (isset($inVar['type'])) ? $type = $inVar['type'] : $type = "text";
        (isset($inVar['value'])) ? $value = " value=" . $this->quote($inVar['value']) : $value = "";
        (isset($inVar['name'])) ? $name = " name=" . $this->quote($inVar['name']) : $name = "";
        (isset($inVar['id'])) ? $id = " id=" . $this->quote($inVar['id']) : $id = "";
        (isset($inVar['style'])) ? $style = " style=" . $this->quote($inVar['style']) : $style = "";
        (isset($inVar['class'])) ? $class = " class=" . $this->quote($inVar['class']) : $class = "";
        (isset($inVar['event'])) ? $onEvent = " " . $inVar['event'] : $onEvent = "";
        (isset($inVar['size'])) ? $size = " size=" . $inVar['size'] : $size = "";
        return "<input" . $id . $name . $style . $class . $size . $onEvent . " type=" . $this->quote($type) . $value . $checked . " />\n";
    }

    function button($inVar) {
        if (!isset($inVar['type']))
            $inVar['type'] = "button"; // if type set to submit use it otherwise override by button
        if (!isset($inVar['class']))
            $inVar['class'] = "submit"; // if type set use it otherwise override by button
        (isset($inVar['value']) && isset($inVar['name'])) ? $return = $this->input($inVar) : $return = array();
        return $return;
    }

    function password($inVar) {
        $inVar['type'] = "password";
        $inVar['name'] = strtolower($this->getVal($inVar, 'name'));
        return $this->input($inVar);
    }

    function submit($inVar) {
        $inVar['type'] = "submit";
        $inVar['name'] = strtolower($this->getVal($inVar, 'value'));
        return $this->button($inVar);
    }

    function cancel($inVar) {
        $inVar['event'] = "onclick=\"javascript:history.back(1)\"";
        $inVar['name'] = strtolower($this->getVal($inVar, 'value'));
        return $this->button($inVar);
    }

    function hidden($inVar) {
        $inVar['type'] = "hidden";
        (isset($inVar['value']) && isset($inVar['name'])) ? $return = $this->input($inVar) : $return = array();
        return $return;
    }

    function encryptPw($pw) {
        $salt = "mosecms";
        return sha1($salt . $pw . $salt);
    }

    function usingOnce($iFile) {
        $found = false;
//pr($iFile,"ifile:");
        if (file_exists($iFile)) {
//pr($iFile,"in ifile:");
            include_once($iFile);
            $found = true;
        }
        return $found;
    }

    function using($iFile) {
        $found = false;
//    $this->debugO(array('name'=>getcwd(),'msg'=>'getcwd: ','debug'=>$this->debug));
//    $this->debugO(array('name'=>$iFile,'msg'=>'using File: ','debug'=>$this->debug));
        if (file_exists($iFile)) {
//      $this->debugO(array('name'=>$iFile,'msg'=>'include iFile: ','debug'=>$this->debug));
            include($iFile);
            $found = true;
        }
        return $found;
    }

    function usingJS($iFile) {
        (file_exists($iFile)) ? $return = $this->wrapjs("", $iFile) : $return = "";
        return $return;
    }

    function cpanel() {
        $editorsmgr = $this->a($this->href(array('do' => 'admin', 'mgr' => 'editors')), "Editors Mgr");
        $macrosmgr = $this->a($this->href(array('do' => 'admin', 'mgr' => 'macros')), "Macros Mgr");
        $cmodsmgr = $this->a($this->href(array('do' => 'admin', 'mgr' => 'cmods')), "Cmods Mgr");
        $possmgr = $this->a($this->href(array('do' => 'admin', 'mgr' => 'positions')), "Positions Mgr");
        $modsmgr = $this->a($this->href(array('do' => 'admin', 'mgr' => 'mods')), "Mods Mgr");
        $appsmgr = $this->a($this->href(array('do' => 'admin', 'mgr' => 'apps')), "Apps Mgr");
        $pagesmgr = $this->a($this->href(array('do' => 'admin', 'mgr' => 'pages')), "Pages Mgr");
        $config = $this->a($this->href(array('do' => 'admin', 'mgr' => 'config')), "Configuration");
        $logout = $this->a($this->href(array('do' => "logout")), "Logout");
        $version = "<span class='version'>(version: " . $this->version . ")</span>";
        $mitems = "
    $editorsmgr<a> | </a>
  	$macrosmgr<a> | </a>
  	$appsmgr<a> | </a>
  	$modsmgr<a> | </a>
  	$possmgr<a> | </a>
  	$cmodsmgr<a> | </a>
  	$pagesmgr<a> | </a>
  	$config<a> | </a>
  	$logout<a> | </a>
    $version"
        ;
        return $mitems;
    }

    function stripos($str, $needle, $offset = 0) {
        return strpos(strtolower($str), strtolower($needle), $offset);
    }

    function stripDriveLetter($iPath) {
        $iarray = explode(":", $iPath);
        return array_pop($iarray);
    }

    function string2Var($string) {
        return eval('return ' . $string . ';');
    }

    function xxxgetcwd() {
        return $this->stripDriveLetter(getcwd());
    }

// get folder without drive letter
}

?>