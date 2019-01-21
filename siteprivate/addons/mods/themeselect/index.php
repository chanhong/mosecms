<?php

/**
 * addons\mods\themeselect\index.php
 *
 * A module to select theme on the front page and to show how to create a module in MoseCMS, similar to Mambo template chooser
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
$curTemplateName = $mosecms->currentTemplateName();
//$siteTemplate = $mosecms->liveSite."/".$mosecms->templatesDir."/";
$siteTemplate = $mosecms->templatesDir;
$curSkinName = $mosecms->currentSkinName();

$params = $mosecms->getParams("mods", "themeselect");
if ($params) {
    $titlelength = $params['title_length'];
    $preview_height = $params['preview_height'];
    $preview_width = $params['preview_width'];
    $show_preview = $params['show_preview'];
}

//$filesListArray = $mosecms->h->filesList(getcwd()."/".$mosecms->templatesDir."*", "dir","index");
$filesListArray = $mosecms->h->filesList($mosecms->templatesDir . "*", "dir", "index");
if (!empty($fileListArray))
    sort($filesListArray);

//$cssListArray = $mosecms->h->filesList(getcwd()."/".$mosecms->templatesDir."/".$curTemplateName."/css/*", "dir","admin|setup");
//sort( $cssListArray );

$requestUri = 'index.php';
if (!empty($_SERVER['QUERY_STRING'])) {
	$requestUri = htmlspecialchars(trim($_SERVER['QUERY_STRING']));
	$requestUri = $requestUri != '' ? 'index.php?' . $requestUri : 'index.php';
}

$onchange = "";
if ($show_preview)
    $onchange = " onchange=\"mose_showimage();\"";
$formbody = $mosecms->h->optionList($filesListArray, 'mose_themeselect', $curTemplateName, $onchange) .
        "<br />" . $mosecms->h->submit(array('value' => "Select"));

print $mosecms->h->img($mosecms->currentTemplate() . "theme.png", "themePreview", $preview_width, $preview_height, $curTemplateName);
print $mosecms->h->wrapjs("
	function mose_showimage() {
		var tpimage=document.getElementById('themePreview');
		tpimage.src =  '" . $siteTemplate . "' + mose_getSelectedValue() + '/theme.png';
	}
	function mose_getSelectedValue() {
		var srcList = document.getElementById('mose_themeselect');
		i = srcList.selectedIndex;
		if (i != null && i > -1) {
			return srcList.options[i].value;
		} else {
			return null;
		}
	}");
print $mosecms->h->form($requestUri, $formbody);
?>