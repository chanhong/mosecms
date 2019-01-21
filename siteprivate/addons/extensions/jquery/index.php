<?php

/**
 * addons\extensions\jquery\index.php
 *
 * A extension demo to preload AJAX jQuery on onHead event to contentHook to be use by MoseCMS and its addons
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
$hook = "contentHook";
$name = "jquery";
$callback = "mosecms_load_" . $name;
$mosecms->hooksRegister($hook, "onHead", $name, $callback);

function mosecms_load_jquery() {
    global $mosecms;
    $packageDir = "jquery";
    $package = "jquery.js";
    $js = $mosecms->usingJS($packageDir, $package);
    if (empty($js))
        $js = $mosecms->usingJSFromCore($packageDir, $package); // try load from core for final attempt
    print $js;
}

?>