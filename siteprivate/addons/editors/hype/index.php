<?php

/**
 * addons\editors\hype\index.php
 *
 * A editor connector to preload HYPE editor hook on onHead and onEdit event 
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
$name = "hype";
$callback = "mosecms_editor_load_" . $name;
$mosecms->hooksRegister($hook, "onHead", $name, $callback);
$callback = "mosecms_editor_" . $name;
$mosecms->hooksRegister($hook, "onEdit", $name, $callback);

// callback function
function mosecms_editor_load_hype() {
    global $mosecms;
// print to head
    $mosecms->editorHead = "
	function setSelectionRange(input, selectionStart, selectionEnd) {
	  if (input.setSelectionRange) {
	    input.focus();
	    input.setSelectionRange(selectionStart, selectionEnd);
	  }
	  else if (input.createTextRange) {
	    var range = input.createTextRange();
	    range.collapse(true);
	    range.moveEnd('character', selectionEnd);
	    range.moveStart('character', selectionStart);
	    range.select();
	  }
	}
	function replaceSelection (input, replaceString) {
		if (input.setSelectionRange) {
			var selectionStart = input.selectionStart;
			var selectionEnd = input.selectionEnd;
			input.value = input.value.substring(0, selectionStart)+ replaceString + input.value.substring(selectionEnd);
			if (selectionStart != selectionEnd){ 
				setSelectionRange(input, selectionStart, selectionStart + 	replaceString.length);
			}else{
				setSelectionRange(input, selectionStart + replaceString.length, selectionStart + replaceString.length);
			}
		}else if (document.selection) {
			var range = document.selection.createRange();

			if (range.parentElement() == input) {
				var isCollapsed = range.text == '';
				range.text = replaceString;
				 if (!isCollapsed)  {
					range.moveStart('character', -replaceString.length);
					range.select();
				}
			}
		}
	}
	// catch the TAB key
	function catchTab(item,e){
		if(navigator.userAgent.match(\"Gecko\")){
			c=e.which;
		}else{
			c=e.keyCode;
		}
		if(c==9){
			replaceSelection(item,String.fromCharCode(9));
			setTimeout(\"document.getElementById('\"+item.id+\"').focus();\",0);	
			return false;
		}
	}

	var HYPECode = function(){
	window.undefined = window.undefined;
	this.initDone = false;
	}
	HYPECode.prototype.init = function(t){
	if(this.initDone) return false;
	if(t == undefined) return false;
	this._target = t ? document.getElementById(t) : t;
	this.initDone = true;
	return true;
	}
	HYPECode.prototype.noForm = function(){
	return this._target == undefined;
	}
	// insertcode is used for bold, italic, underline and quote and just
	// wraps the tags around a selection or prompts the user for some
	// text to apply the tag to
	HYPECode.prototype.insertCode = function (tag, desc, endtag) {
	if(this.noForm()) return false;
	var isDesc = (desc == undefined || desc == '') ? false : true;
	// our textfield
	var textarea = this._target;
	// our open tag
	var open = '<'+tag+'>';
	var close = '</'+((endtag == undefined) ? tag : endtag)+'>';
	if (!textarea.setSelectionRange) {
	var selected = document.selection.createRange().text;
	if (selected.length<=0) {
	// no text was selected so prompt the user for some text
	textarea.value += open+((isDesc) ? prompt(\"Please enter the text you'd like to \"+desc, \"\")+close : '');
	} else {
	// put the code around the selected text
	document.selection.createRange().text = open+selected+((isDesc) ? close : '');
	}
	} else {
	// the text before the selection
	var pretext = textarea.value.substring(0, textarea.selectionStart);
	// the selected text with tags before and after
	var codetext = open+textarea.value.substring(textarea.selectionStart, textarea.selectionEnd)+((isDesc) ? close : '');
	// the text after the selection
	var posttext = textarea.value.substring(textarea.selectionEnd, textarea.value.length);
	// check if there was a selection
	if (codetext == open+close) {
	//prompt the user
	codetext = open+((isDesc) ? prompt(\"Please enter the text you'd like to \"+desc, \"\")+close : '');
	}
	// update the text field
	textarea.value = pretext+codetext+posttext;
	}
	// set the focus on the text field
	textarea.focus();
	}
	// inserts an image by prompting the user for the url
	HYPECode.prototype.insertImage = function (html) {
	if(this.noForm()) return false;
	var src = prompt('Please enter the url', 'http://');
	this.insertCode('img src=\"'+src+'\" alt=\"'+prompt(\"Please enter the alt tag\", \"alt image name\")+'\" /');
	}

	// inserts a link by prompting the user for a url
	HYPECode.prototype.insertLink = function (html) {
	if(this.noForm()) return false;
	this.insertCode('a href=\"'+prompt(\"Please enter the url\", \"http://\")+'\" title=\"'+prompt(\"Please enter the title\", \"Untitled\")+'\"', 'as text of the link', 'a')
	}
";
    // print in head
    print $mosecms->h->wrapjs($mosecms->editorHead);
}

// call back function
function mosecms_editor_hype($pagecontent) {
    global $mosecms;
    // set to be called in editor save form
    $mosecms->editorCode = "
	<input type=\"button\" onclick=\"hype.insertCode('b', 'bold');\" value=\"B\" title=\"Bold text\" />
	<input type=\"button\" onclick=\"hype.insertCode('i', 'make italic');\" value=\"I\" title=\"Italic text\" />
	<input type=\"button\" onclick=\"hype.insertCode('u', 'underline');\" value=\"U\" title=\"Underlined text\" />
	<input type=\"button\" onclick=\"hype.insertCode('p', 'paragraph');\" value=\"<P>\" title=\"Insert a Paragraph\" />
	<input type=\"button\" onclick=\"hype.insertImage();\" value=\"Image\" title=\"Inset an image\" />
	<input type=\"button\" onclick=\"hype.insertLink();\" value=\"Link\" title=\"Insert a link\" />
	<p />";

    // js to run in editor save form
    $js = "var hype = new HYPECode();
	hype.init('content');";
    $mosecms->editorArea = "<textarea name=\"content\" id=\"content\" cols=\"100%\" rows=\"15\" wrap=\"off\" onkeydown=\"return catchTab(this,event)\">"
            . $pagecontent . "</textarea>" . $mosecms->h->wrapjs($js);

    print $mosecms->editorSaveForm($pagecontent);
}

?>