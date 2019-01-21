<?php

/**
 * index.php
 *
 * A sample to show what you need to put in your index to include MoseCMS in your existing site
 *
 * @copyright  (C) 2008 ongetc.com
 * @license    GNU/GPL http://ongetc.com/gpl.html.
 * @info       ongetc@ongetc.com http://ongetc.com
 * @version    $Id:$
 * @since      File available since Release 0.1
 */
?>
<?php

if (!defined('_MOSECMS'))
    define('_MOSECMS', 1);  // set signature on main entry or in the core index

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

global $moseConfig;
$configFile = "sitepublic" . DS . "config.php";
if (file_exists($configFile))
    include_once($configFile);
(empty($moseConfig['coredir'])) ? $coredir = "share" . DS . "core" : $coredir = $moseConfig['coredir'];
include_once($coredir . DS . "index.php");
?>