<?php

/**
 * addons\mods\hello\index.php
 *
 * A simple "Hello World" to how to create a module in MoseCMS
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
echo hello();

function hello() {
    return "<div><h2>Hello</h2>hello from mods<br /></div>";
}

?>