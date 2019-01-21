<?php

/**
 * addons\apps\phorumembed\index.php
 *
 * A sample addons app that show case show to utilitize Phorum embeded template module
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
// not very pretty but just a proof of concept!
/*
  Hack common.php to work around "headers already sent by ...
  // our standard-way
  //        header( "Location: $redir_url" );
  // hack to work around this error
  //  Warning: Cannot modify header information - headers already sent by ...  in phorum525\common.php on line 905
  redirect($redir_url);
  }
  exit(0);
  }
  function redirect($url, $imsg=""){
  if (!headers_sent()){    //If headers not sent yet... then do php redirect
  header('Location: '.$url);
  exit;
  }else{                  //If headers are sent... do java redirect... if java disabled, do html redirect.
  echo redirectViaJs($url).'<noscript>'.redirectViaMeta($url).'</noscript>';
  exit;
  }
  }
  function redirectViaMeta($url="",$imsg="",$delay=0) {
  if (!$url) $url = $_SERVER['REQUEST_URI'];
  print "<meta http-equiv=\"refresh\" content=\"".$delay." URL=".$url."\" />";
  exit;
  }
  function redirectViaJs($url,$imsg="") {
  print "<script type='text/javascript'><!-- document.location.href='".$url."';//--></script>";
  }
  // hack to work around this error
 */
phorum();

function phorum() {
    ob_start();
    include ('phorum.php');
    $flushed = ob_get_contents();
    ob_end_clean();
    echo $flushed;
}

?>