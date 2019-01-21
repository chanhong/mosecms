<?php

/**
 * addons\mods\cssmenu\index.php
 *
 * A simple CSS menu to show how to create a module in MoseCMS
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
addOnCSSMenu();

// a sample for add-on
function addOnCSSMenu() {
    global $mosecms;
    $mitems = "";
    $start = 0;
    $active = 'class="active"';
    if (isset($_GET['page']))
        $apage = $_GET['page'];
    else
        $apage = "";
    if (isset($_GET['site']))
        $asite = "&site=" . $_GET['site'];
    else
        $asite = "";
    foreach ($mosecms->liveActivePages as $fName) {
        ($fName == $apage) ? $activemenu = $active : $activemenu = "";
        if ($start == 0 && $apage == "" && $activemenu == "")
            $activemenu = $active;
        $mitems .= '<li><a ' . $activemenu . 'href="index.php?pname=' . $fName . $asite . '">' . ucfirst($fName) . '</a></li>';
        $mitems .= "\n";
        $start = 1;
    }
    print '<ul>' . "\n" . $mitems . '</ul>' . "\n";
}

?>