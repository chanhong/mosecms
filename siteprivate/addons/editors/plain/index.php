<?php

/**
 * addons\editors\plain\index.php
 *
 * A editor connector to preload plain editor hook on onHead and onEdit event 
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
$name = "plain";
$callback = "";
$mosecms->hooksRegister($hook, "onHead", $name, $callback);
$callback = "mosecms_editor_" . $name;
$mosecms->hooksRegister($hook, "onEdit", $name, $callback);

// call back function name convention
function mosecms_editor_plain($pagecontent) {
    global $mosecms;
    $mosecms->editorArea = "<textarea name=\"content\" id=\"content\" cols=\"100%\" rows=\"15\" wrap=\"off\">"
            . $pagecontent . "</textarea>";
    $mosecms->editorCode = ""; // js to run in editor save form
    print $mosecms->editorSaveForm($pagecontent);
}

?>