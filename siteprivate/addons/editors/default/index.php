<?php

/**
 * addons\editors\default\index.php
 *
 * A editor connector to preload default editor hook on onHead and onEdit event 
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
$name = "default";
$mosecms->hooksRegister($hook, "onHead", $name, "");
$mosecms->hooksRegister($hook, "onEdit", $name, "");
?>