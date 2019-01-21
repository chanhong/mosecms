<?php

/**
 * functions.global.php
 *
 * global functions API call by template or go direct to global mosecms class variable
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

function mosecmsMainBody() {
    global $mosecms;
    $mosecms->loadMainBody();
}

function mosecmsModules() {
    global $mosecms;
    $mosecms->loadModules();
}

function mosecmsSiteURL() {
    global $mosecms;
    return $mosecms->siteUrl();
}

function mosecmsSiteRoot() {
    global $mosecms;
    return $mosecms->siteRoot();
}

function mosecmsLoadOnBody() {
    global $mosecms;
    return $mosecms->loadOnBody();
}

function mosecmsMenu($opt) {
    global $mosecms;
    $mosecms->menu($opt);
}

function mosecmsLoadInHeader() {
    global $mosecms;
    $mosecms->loadInHeader();
}

function mosecmsGetRequestVal($inParm, $inType) {
    global $mosecms;
    return $mosecms->h->getRequestVal($inParm, $inType);
}

function mosecmsIsAdmin() {
    global $mosecms;
    return $mosecms->h->isAdmin();
}

function mosecmsCurrentTemplate() {
    global $mosecms;
    return $mosecms->currentTemplate();
}

function mosecmsCSSImport($css) {
    global $mosecms;
    return $mosecms->cssImport($css);
}

function mosecmsCSS($css) {
    global $mosecms;
    return $mosecms->cssLink($css);
}

function mosecmsUsingPackage($iDir, $iFile) {
    global $mosecms;
    return $mosecms->usingPackage($iDir, $iFile);
}

function mosecmsUsingPackageFromCore($iDir, $iFile) {
    global $mosecms;
    return $mosecms->usingPackageFromCore($iDir, $iFile);
}

function pr($inVar, $inMsg = "", $inFormat = "") {
    global $mosecms;
    $mosecms->h->pr(array('name' => $inVar, 'msg' => $inMsg, 'format' => $inFormat));
}

?>