<?php

/**
 * addons\macros\mosmodule\index.php
 *
 * A MosModule macro that was ported from Mambo mambots and register to onContent event via contentHook to be used in content
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
mosecms_register_macro_mosmodule();

function mosecms_register_macro_mosmodule() {
    global $mosecms;
    $pattern = '|{(mosmodule)\s*(.*?)}|i';
    $name = "mosmodule";
    $callback = "mosecms_macro_" . $name;
    $mosecms->hooksRegister("contentHook", "onContent", $name, $callback, $pattern);
}

// only get :{:yourcontentcommand} to do something
function mosecms_macro_mosmodule($iContentWithMacro = '') { //  content has this {:yourmacro} in it
    global $mosecms;
    $return = "";
    $mm = new MosmoduleMacroClass();
    list($whole, $dot, $func) = $iContentWithMacro;
    $return = $mm->me($whole);
    /*
      list($whole,$dot,$func) = $iContentWithMacro;
      if (method_exists($mm,$func)) $return = $mm->$func("MosModule");
      else $return=$whole;	// return for the next add-on to parse, important
     */
    return $return;
}

class MosmoduleMacroClass {

    var $meme;
    var $mepath;

//    function MosmoduleMacroClass($meme = "", $mepath = "") {
    function __construct($meme = "", $mepath = "") {
        
        $this->meme = $meme;
        $this->mepath = $mepath;
    }

    function me($textin) {
        if (!$bots = $this->isMe($textin))
            return $textin;
        $text = preg_split("/{(mosmodule)\s*(.*?)}/i", $textin);
        $textout = $text[0];  // split out text only no mosmodule
        $n = count($bots);
        for ($i = 0; $i <= $n - 1; $i++) {
            $textout .= $this->process($bots[$i][0]);  // process mosmodule
            $textout .= $text[$i + 1];  // add back text
        }
        return $textout;  // return row with processed text
    }

    function isMe($textin) {
        if (preg_match_all("/{(mosmodule)\s*(.*?)}/i", $textin, $retMatchArray, PREG_SET_ORDER))
            return $retMatchArray;
    }

    function process($inText) {
        global $database, $mainframe, $foldername;
        $pname = "";
        // split out the arguments
        preg_match_all("/{(mosmodule)\s*(.*?)}/i", $inText, $matches, PREG_SET_ORDER);
        $text = '';
        $args = $matches[0][2]; // module=module title or module block position or list
        $options = explode('=', $matches[0][2]);
        if (count($options) > 1)
            $option = strtolower($options[0] ? $options[0] : $options[1]);
        else
            $option = strtolower($options[0]);
        ($option) ? $task = $option : $task = "list";
        $args = explode('=', $args, 2);
        if (count($args) > 1)
            $pname = $args[1];
        else
            $pname = "";
        switch ($task) {
            case 'textsizer':
            case 'videoegg':
            case 'video':
            case 'flash':
            case 'wrapper':
            case 'grabpage':
            case 'rssfeed':
                $text = $this->docapture($task, $pname);
                break;
            case 'list':
            case 'help':
            default:
                $text = $this->help();
                break;
        }
        return $text;
    }

    function help() {
        return "<h3>MosModule usages</h3>
<br /><i>Note: substitue MM with mosmodule!</i>
<p />{MM list} - Display this help
<p />Need more detail: see readme.txt
";
    }

    function docapture($task, $pname) {
        global $mosmodule_var, $foldername;
        ob_start();
        switch ($task) {
            case 'inc':
            case 'phpinc':
                $foldername .= "/";
                $mosmodule_var = array();
                $mosmodule_var = explode(",", $pname);
                if (file_exists($foldername . $mosmodule_var[0])) {
                    $php_file = $foldername . array_shift($mosmodule_var);
                    include($php_file);
                }
                break;
            case 'snippet':
//				$output = $this->loadarticle($pname);  
                eval($output);
                break;
            case 'textsizer':
                echo $this->textsizer($pname);
                break;
            case 'video':
            case 'videoegg':
            case 'flash':
                echo $this->doVideo($task, $pname);
                break;
            case 'grabpage':
            case 'rssfeed':
                echo $this->grabPage($pname);
                break;
            case 'wrapper':
                echo $this->wrapper($pname);
                break;
            default:
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    function getWordsFromText($text, $limit) {
        if (strlen($text) > $limit) {
            $words = str_word_count($text, 2); // get words into array
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]);
        }
        return $text;
    }

    function wrapper($pname) {
        $iwidth = "100%";
        $iheight = "800px";
        $iscrolling = "auto";
        $ialign = "top";
        $aparam = array();
        $aparam = explode(",", $pname);
        $iurl = $aparam[0];
        if (count($aparam) > 1 and $aparam[1])
            $iwidth = $aparam[1];
        if (count($aparam) > 2 and $aparam[2])
            $iheight = $aparam[2];
        if (count($aparam) > 3 and $aparam[3])
            $iscrolling = $aparam[3];
        if (count($aparam) > 4 and $aparam[4])
            $ialign = $aparam[4];

        return "<iframe id='MMWrapper' src='$iurl' width='$iwidth' height='$iheight' scrolling='$iscrolling' align='$ialign' frameborder='0'>This option will not work correctly.  Unfortunately, your browser does not support Inline Frames</iframe>";
    }

    function grabPage($pname) {
        global $mosecms;
        $aparam = array();
        $aparam = explode(",", $pname);
        $iurl = $aparam[0];
        $tmp = explode("/", $iurl);
        if ($tmp[0] <> "http:")
            $iurl = $mosecms->h->siteUrlName() . $iurl;

        (count($aparam) > 1) ? $textpart = $aparam[1] : $textpart = "body";
        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $iurl);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
        $buffer = "";
        $buffer = curl_exec($curl_handle);
        $curl_errors = curl_errno($curl_handle);
        curl_close($curl_handle);
        if ($curl_errors || !$buffer) {
            $buffer = "";
        }
        $buffer = preg_replace("/<\/html>/i", "", $buffer); // strip out end html
        $buffer = preg_replace("/(.+?)<body(.+?)>(.+?)<\/body>/is", "$3", $buffer); // just keep content of body
        $buffer = "<!-- Start of MosModule --><div id='mmGrabPage'>" . $buffer . "</div><!-- End of MosModule -->";
        return $buffer;
    }

    function textsizer($pname) {
        $output = " 
<script type='text/javascript'>
mostsizer = function( tagid,iafsize,isize,idirection ) {
  var msize=iafsize.length-1;
  if ( idirection != 0 ) isize += idirection;
  else isize = 2; // reset to original size
  if ( isize < 0 ) isize = msize;
  if ( isize > msize ) isize = 0;
  
  var dd = 'document.getElementById(\"'+tagid+'\");';
  var d = eval(dd);
  if (d) {
    var innerTags = d.getElementsByTagName(\"*\");
    for ( var j = 0 ; j < innerTags.length ; j++ ) {
      innerTags[j].style.fontSize = iafsize[ isize ];
    }
  }
return isize;
}                  
</script>
<div id='tsizerlink'>
<script>
var nsize=2;
var afsize = new Array( 'xx-small','x-small','small','medium','large','x-large','xx-large' );
var mostsizertag = '$pname';
</script>
<input class='tsizerbutton' id='plus' value=' A+ '  onclick='javascript:nsize=mostsizer(mostsizertag,afsize,nsize,1)' type='button' />
<input class='tsizerbutton' id='reset'value=' A '  onclick='javascript:nsize=mostsizer(mostsizertag,afsize,nsize,0)' type='button' />
<input class='tsizerbutton' id='minus' value=' A- '  onclick='javascript:nsize=mostsizer(mostsizertag,afsize,nsize,-1)' type='button' />
</div>";
        return $output;
    }

    function videoegg($pname, $vwidth, $vheight) {
        $output = "<script language=\"javascript\" src=\"http://update.videoegg.com/js/PlayerCustom.js\"></script><script language='javascript'>var api = VE_getCustomPlayerAPI('1.0');api.embedPlayer('" . $pname . "', " . $vwidth . ", " . $vheight . ", false, '', 'videoegg', false, '', '');</script>";
        return $output;
    }

    function video($pname, $vwidth, $vheight) {
        $output = "<embed style=\"width:" . $vwidth . "px; height:" . $vheight . "px;\" id=\"VideoPlayback\" type=\"application/x-shockwave-flash\" src=\"" . $pname . "\" flashvars=\"\"> </embed>";
        return $output;
    }

    function flash($pname, $vwidth, $vheight) {
        $output = "<script type=\"text/javascript\" src=\"" . $this->mepath . "swfobject.js\"></script>
<p id=\"mmplayer\"><a href=\"http://www.macromedia.com/go/getflashplayer\">Get the Flash Player</a> to see this player.</p>
<script type=\"text/javascript\">
	var s1 = new SWFObject(\"" . $this->mepath . "mediaplayer.swf\",\"single\",\"$vwidth\",\"$vheight\",\"7\");
	s1.addParam(\"allowfullscreen\",\"true\");
	s1.addVariable(\"file\",\"$pname\");
	s1.addVariable(\"image\",\"$pname\");
	s1.write(\"mmplayer\");
</script>";
        return $output;
    }

    function doVideo($task, $pname) {
        $aparam = array();
        $aparam = explode(",", $pname);
        $videofile = $aparam[0];
        (count($aparam) > 1) ? $vwidth = $aparam[1] : $vwidth = "390";
        (count($aparam) > 2) ? $vheight = $aparam[2] : $vheight = "280";
        switch ($task) {
            case 'videoegg':
                $output = $this->videoegg($videofile, $vwidth, $vheight);
                break;
            case 'video':
                $output = $this->video($videofile, $vwidth, $vheight);
                break;
            case 'flash':
                $output = $this->flash($videofile, $vwidth, $vheight);
                break;
        }
        return $output;
    }

}

?>