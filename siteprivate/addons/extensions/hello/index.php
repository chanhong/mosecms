<?php

/**
 * addons\extensions\hello\index.php
 *
 * A extension demo to show case a simple "Hello World" via extension
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

function MyHelloWorld() {
    return "Hello, World";
}

?>