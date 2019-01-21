<?php

/**
 * addons\extensions\opacity\index.php
 *
 * A extension demo to preload opacity javascript on onHead event to contentHook to be use by MoseCMS and its addons
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
$name = "opacity";
$callback = "mosecms_load_" . $name;
$mosecms->hooksRegister($hook, "onHead", $name, $callback);

function mosecms_load_opacity() {
    global $mosecms;
    print $mosecms->h->wrapjs("", $mosecms->jsDir . "opacity/browserdetect_lite.js");
    print $mosecms->h->wrapjs("", $mosecms->jsDir . "opacity/opacity.js");
    $js = "	var objMyImg = null;
    function init() {
      objMyImg = new OpacityObject('logo','contents/templates/default/images');
      objMyImg.setBackground();
    }";
    print $mosecms->h->wrapjs($js);

//	<script src="js/opacity/browserdetect_lite.js" type="text/javascript"></script>
//	<script src="js/opacity/opacity.js" type="text/javascript"></script>
    /*

      <script type="text/javascript">
      var objMyImg = null;
      function init() {
      objMyImg = new OpacityObject('logo','contents/templates/default/images');
      objMyImg.setBackground();
      }
      </script>
     */
}

?>