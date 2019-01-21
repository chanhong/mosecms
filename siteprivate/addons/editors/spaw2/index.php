<?php

/**
 * addons\editors\spaw2\index.php
 *
 * A editor connector to preload spaw2 editor hook on onHead and onEdit event 
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
$name = "spaw2";
$callback = "mosecms_editor_load_" . $name;
$mosecms->hooksRegister($hook, "onHead", $name, $callback);
$callback = "mosecms_editor_" . $name;
$mosecms->hooksRegister($hook, "onEdit", $name, $callback);

function mosecms_editor_load_spaw2() {
    global $mosecms;
    // print in head
    $mosecms->editorHead = "";
    print $mosecms->editorHead;
}

// call back function
function mosecms_editor_spaw2($pagecontent) {
    global $mosecms;
    if ($mosecms->h->usingOnce($mosecms->jsDir . "spaw2/spaw.inc.php")) {
//	include_once "packages/spaw2/spaw.inc.php";
        $spaw1 = new SpawEditor("content", $pagecontent);
        ob_start();
        $spaw1->show();
        $mosecms->editorArea = ob_get_contents();
        ob_end_clean();

        // js to run in editor save form
        $mosecms->editorCode = "";
        print $mosecms->editorSaveForm($pagecontent);
    }
}

?>