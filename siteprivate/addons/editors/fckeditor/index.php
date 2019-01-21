<?php

/**
 * addons\editors\fckeditor\index.php
 *
 * A editor connector to preload FCKeditor editor hook on onHead and onEdit event 
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
$hook = "editorHook";
$name = "fckeditor";

$callback = "mosecms_editor_load_" . $name;
$mosecms->hooksRegister($hook, "onHead", $name, $callback);
$callback = "mosecms_editor_" . $name;
$mosecms->hooksRegister($hook, "onEdit", $name, $callback);

function mosecms_editor_load_fckeditor() {
    global $mosecms;
    $mosecms->editorHead = '<script type="text/javascript" src="' . $mosecms->jsDir . 'fckeditor/fckeditor.js"></script>';
    // print in head
    print $mosecms->editorHead;
}

function mosecms_editor_fckeditor($pagecontent) {
    global $mosecms;
//	include_once "js/fckeditor/fckeditor.php";
    if ($mosecms->h->usingOnce($mosecms->jsDir . "fckeditor/fckeditor.php")) {
        print "<link href=\"" . $mosecms->jsDir . "fckeditor/_samples/sample.css\" rel=\"stylesheet\" type=\"text/css\" />";
        $mosecms->editorArea = "";
        // js to run in editor save form
        // must be exact like this otherwise it won't run
        $mosecms->editorCode = "<script type=\"text/javascript\">
      var sBasePath = '" . $mosecms->jsDir . "fckeditor/';
      var oFCKeditor = new FCKeditor( 'content' ) ;
      oFCKeditor.BasePath	= sBasePath ;
      oFCKeditor.Height	= 300 ;
      oFCKeditor.Value	= '" . $mosecms->h->escapeSingleQuote($pagecontent) . "' ;
      </script>"
                . "<script type=\"text/javascript\">
      oFCKeditor.Create() ;
      </script>";
        print $mosecms->editorSaveForm($pagecontent);
    }
}

?>