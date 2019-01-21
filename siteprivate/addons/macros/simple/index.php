<?php

/**
 * addons\macros\simple\index.php
 *
 * A Simple macro register to onContent event via contentHook to be used in content
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
mosecms_register_macro_simple();

function mosecms_register_macro_simple() {
    global $mosecms;
    $pattern = '|{(!)*(.*?)}|i';
    $name = "simple";
    $callback = "mosecms_macro_" . $name;
    $mosecms->hooksRegister("contentHook", "onContent", $name, $callback, $pattern);
}

// only get :{:yourcontentcommand} to do something
function mosecms_macro_simple($iContentWithMacro = '') { //  content has this {:yourmacro} in it
    global $mosecms;
    list($whole, $dot, $func) = $iContentWithMacro;
    $sm = new SimpleMacroClass();
    if (method_exists($sm, $func))
        $return = $sm->$func("SimpleMacro");
    else
        $return = $whole; // return for the next add-on to parse, important
    return $return;
}

// a sample content callback to subsitute content text
class SimpleMacroClass {

    function __construct() {
        
    }

    function hello($inVar = "") {
        return 'Hello!, ' . $inVar;
    }

    function today($inVar = "") {
        return date("Y/m/d");
    }

    function servername($inVar = "") {
        return $_SERVER['SERVER_NAME'];
        ;
    }

}

?>