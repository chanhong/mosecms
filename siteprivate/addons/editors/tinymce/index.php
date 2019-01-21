<?php

/**
 * addons\editors\tinymce\index.php
 *
 * A editor connector to preload tinymce editor hook on onHead and onEdit event 
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
$name = "tinymce";
$callback = "mosecms_editor_load_" . $name;
$mosecms->hooksRegister($hook, "onHead", $name, $callback);
$callback = "mosecms_editor_" . $name;
;
$mosecms->hooksRegister($hook, "onEdit", $name, $callback);

function mosecms_editor_load_tinymce() {
    global $mosecms;
    $mosecms->editorHead = "<!-- TinyMCE -->
<script language=\"javascript\" type=\"text/javascript\" src=\"" . $mosecms->jsDir . "tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>
<script language=\"javascript\" type=\"text/javascript\">
	tinyMCE.init({
		mode : \"textareas\",
		theme : \"advanced\",
		plugins : \"table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen\",
		theme_advanced_buttons1_add_before : \"save,newdocument,separator\",
		theme_advanced_buttons1_add : \"fontselect,fontsizeselect\",
		theme_advanced_buttons2_add : \"separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor\",
		theme_advanced_buttons2_add_before: \"cut,copy,paste,pastetext,pasteword,separator,search,replace,separator\",
		theme_advanced_buttons3_add_before : \"tablecontrols,separator\",
		theme_advanced_buttons3_add : \"emotions,iespell,media,advhr,separator,print,separator,ltr,rtl,separator,fullscreen\",
		theme_advanced_toolbar_location : \"top\",
		theme_advanced_toolbar_align : \"left\",
		theme_advanced_statusbar_location : \"bottom\",
		content_css : \"example_word.css\",
	    plugi2n_insertdate_dateFormat : \"%Y-%m-%d\",
	    plugi2n_insertdate_timeFormat : \"%H:%M:%S\",
		external_link_list_url : \"example_link_list.js\",
		external_image_list_url : \"example_image_list.js\",
		media_external_list_url : \"example_media_list.js\",
		file_browser_callback : \"fileBrowserCallBack\",
		paste_use_dialog : false,
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		theme_advanced_link_targets : \"_something=My somthing;_something2=My somthing2;_something3=My somthing3;\",
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : \"all\",
		paste_remove_spans : false,
		paste_remove_styles : false		
	});

	function fileBrowserCallBack(field_name, url, type, win) {
		// This is where you insert your custom filebrowser logic
		alert(\"Filebrowser callback: field_name: \" + field_name + \", url: \" + url + \", type: \" + type);

		// Insert new URL, this would normaly be done in a popup
		win.document.forms[0].elements[field_name].value = \"someurl.htm\";
	}
</script>
<!-- /TinyMCE -->
";

    // print in head
    print $mosecms->editorHead;
}

function mosecms_editor_tinymce($pagecontent) {
    global $mosecms;
    $mosecms->editorArea = "<textarea name=\"content\" id=\"content\" cols=\"90%\" rows=\"15\" wrap=\"off\">" . $pagecontent . "</textarea>";
    // js to run in editor save form
    $mosecms->editorCode = "";
    print $mosecms->editorSaveForm($pagecontent);
}

?>