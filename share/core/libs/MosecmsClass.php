<?php
/**
 * class.main.php
 *
 * MoseCMS main class act as a dispatcher or controller and provide a set of API for add-ons
 *
 * @copyright  (C) 2008 ongetc.com
 * @license    GNU/GPL http://ongetc.com/gpl.html.
 * @info       ongetc@ongetc.com http://ongetc.com
 * @version    $Id:$
 * @since      File available since Release 0.1
 */
?>
<?php
namespace Libs;

// note: set h->debug = false before go live
defined('_MOSECMS') or die('NOT allowed!');
//include_once("class.main.base.php");

class MosecmsClass extends MosecmsMainBaseClass {

    function __construct() {
        parent::__construct();
        
// set default variables
        $this->user = "admin";
        $this->setupPw = "setup";
        $this->siteShareDir = "share";
        $this->coreDefaultDir = "core";
        $this->packagesDefaultDir = "packages";
        $this->sitePrivateDefDir = "siteprivate";
        $this->sitePublicDefDir = "sitepublic";
        $this->iso = "charset=utf-8";
        $this->frontPage = "index.html";
        $this->adminPage = "admin.html";
        $this->hooks = $this->hook = array();
        $this->contentHooks = $this->contentHook = array();
        $this->editorHooks = $this->editorHook = array();
        $this->info = array(
            'id' => 0,
            'ordering' => 0,
            'name' => "",
            'statusto' => "hide",
            'editor' => "",
            'publish' => "",
            'position' => "",
            'title' => "",
        );
// must be here to get the base helper in place
//        include_once("class.helpers.php"); // should be the same directory as class.main.php
        if (!isset($mosecmsHelper))
            $mosecmsHelper = new MosecmsHelperClass();
        $this->h = $mosecmsHelper;
        $this->initVars();  // set the initial global config variable before starting anything
//        $this->usingLibsFromCore("class.admin.php");
        $this->usingLibsFromCore("functions.global.php");
    }

    function initVars() {
        $this->configDefault = array(
            'homepage' => "home",
            'coredir' => $this->coreDir(),
            'packages' => $this->packagesDir(),
            'siteprivate' => $this->sitePrivateDir(),
            'sitepublic' => $this->sitePublicDir(),
            'password' => $this->setupPw,
            'template' => "default",
            'skin' => "default.css",
            'admintemplate' => "default",
            'adminskin' => "default.css",
            'defaulteditor' => "default",
            'sitename' => "MoseCMS",
            'custom404' => "custom404.html",
            'history' => "",
            'onload' => "init();initEditor();",
            'metadesc' => "MosECMS - My Open Source (Editable/Extensible/Easy) Content Management System",
            'metakey' => "MosECMS, CMS, Easy CMS, Content Management System, Portal, Website Software",
            'dbtype' => "mysql",
            'dbhost' => "localhost",
            'dblogin' => "root",
            'dbpw' => "",
            'dbname' => "mosecms",
        );
        global $moseConfig; // from config.php
        (empty($moseConfig)) ? $moseConfig = $this->configDefault : $this->config = $moseConfig;
        if (empty($this->config))
            $this->config = $this->configDefault;
// one last check to ensure these important variables are set
        if (empty($this->config['packages']))
            $this->config['packages'] = $this->packagesDir();
        if (empty($this->config['coredir']))
            $this->config['coredir'] = $this->coreDir();
        if (empty($this->config['siteprivate']))
            $this->config['siteprivate'] = $this->sitePrivateDir();
        if (empty($this->config['sitepublic']))
            $this->config['sitepublic'] = $this->sitePublicDir();

// default to site root if sitePublicDir is not defined - must use forward slash for this, access from url	
//		$this->sitePublicDir 			= $this->config['sitepublic']."/"; 	
        $this->jsFromCoreDir = $this->currentSite() . "core/js/";
        $this->jsDir = $this->currentSite() . "js/";
        $this->templatesDir = $this->currentSite() . "templates/";
        $this->contentsDir = $this->config['siteprivate'] . DIRECTORY_SEPARATOR . "contents" . DIRECTORY_SEPARATOR;
        $this->addOnsDir = $this->config['siteprivate'] . DIRECTORY_SEPARATOR . "addons" . DIRECTORY_SEPARATOR;
        $this->macrosDir = $this->addOnsDir . "macros" . DIRECTORY_SEPARATOR;
        $this->appsDir = $this->addOnsDir . "apps" . DIRECTORY_SEPARATOR;
        $this->editorsDir = $this->addOnsDir . "editors" . DIRECTORY_SEPARATOR;
        $this->modsDir = $this->addOnsDir . "mods" . DIRECTORY_SEPARATOR;
        $this->pagesDir = $this->contentsDir . "pages" . DIRECTORY_SEPARATOR;
        $this->cmodsDir = $this->contentsDir . "cmods" . DIRECTORY_SEPARATOR;
        $this->positionsDir = $this->contentsDir . "positions" . DIRECTORY_SEPARATOR;
        $this->liveRootDir = $this->h->siteAbsDir() . DIRECTORY_SEPARATOR;
        $this->liveSite = $this->h->siteUrl();
        $this->version = $this->h->version;

        $this->editorHead = "";
        $this->editorCode = "";
        $this->editorArea = "";
        $this->defaultEditor = $this->config['defaulteditor'];

        $this->liveMacrosInfo = $this->h->loadInfo($this->macrosDir . "index.php"); // from dir
        $this->livecModsInfo = $this->h->loadInfo($this->cmodsDir . "index.php"); // from dir
        $this->liveModsInfo = $this->h->loadInfo($this->modsDir . "index.php"); // from dir
        $this->liveAppsInfo = $this->h->loadInfo($this->appsDir . "index.php"); // from dir
        $this->liveEditorsInfo = $this->h->loadInfo($this->editorsDir . "index.php"); // from dir    
        $this->livePagesInfo = $this->h->loadInfo($this->pagesDir . "index.php"); // from dir
        $this->positionsInfo = $this->h->loadInfo($this->positionsDir . "index.php"); // from dir

        $this->activecModsInfo = $this->h->getActiveInfo($this->livecModsInfo);   // exclude hidden, 
        $this->activeModsInfo = $this->h->getActiveInfo($this->liveModsInfo);   // exclude hidden, 
        $this->activeMacrosInfo = $this->h->getActiveInfo($this->liveMacrosInfo);   // exclude hidden, 
        $this->activeAppsInfo = $this->h->getActiveInfo($this->liveAppsInfo);   // exclude hidden, 
        $this->activeEditorsInfo = $this->h->getActiveInfo($this->liveEditorsInfo);   // exclude hidden, 
        $this->activePagesInfo = $this->h->getActiveInfo($this->livePagesInfo);  // exclude hidden, 
        $this->liveActivePages = $this->h->getActivePages($this->activePagesInfo); // just name, use by add-ons
    }

    function main() {
        $configFile = $this->getConfigFile();
//    $this->h->debugO(array('name'=>$configFile,'msg'=>'configFile: ','debug'=>$this->h->debug));
        if ((file_exists($configFile)) && $this->sessionCheckPassword()) {
//      $this->h->debugO(array('name'=>$configFile,'msg'=>'exist configFile: ','debug'=>$this->h->debug));  
            $mose_themeselect = $this->h->getRequestVal('mose_themeselect');
            if ($mose_themeselect)
                setcookie("mose_themecookie", $mose_themeselect, time() + (60 * 10));
            $addOnsDir = $this->addOnsDir;
            $this->loadAddOnsByType($addOnsDir . "connectors" . DIRECTORY_SEPARATOR, "*");
            $this->loadAddOnsByType($addOnsDir . "extensions" . DIRECTORY_SEPARATOR, "*");
            $this->loadAddOnsByType($addOnsDir . "macros" . DIRECTORY_SEPARATOR, "*");
            $this->loadAddOnsEditor();
            ($this->isAdmin()) ? $template = $this->currentAdminTemplate() . $this->adminPage : $template = $this->currentTemplate() . $this->frontPage;
//      $this->h->debugO(array('name'=>$template,'msg'=>'template: ','debug'=>$this->h->debug));
            $this->h->using($template);
        } else {
            $this->setup();
        }
    }

    function loadMainBody() {
        $this->h->showMoseMsg();
        if (!$this->isAdmin()) {  // load frontend
            switch ($this->h->action()) {
                case "login":
                    $buff = $this->checkLoginPasswordRedirect() . $this->h->loginForm();
                    break;
                case "run":
                    $buff = $this->appLoad();
                    break;
                default:
                case "view":
                    $buff = $this->pageLoad("pages");
            }
            echo $buff;
        } else {  // load backend
            if (!isset($mosecmsAdmin))
                $mosecmsAdmin = new MosecmsAdminClass();
            $mosecmsAdmin->main();
        }
    }

    function loadOnBody() {
        return $this->config['onload'];
    }

    function pageMenuItem($opt, $href, $fName) {
        if ($opt == "vmenu" or $opt == "vadmin") {
            $mclass = "class='menulevel'";
            return "<tr><td $mclass><a href=\"$href\">" . ucfirst($fName) . "</td></tr>" . " \n";
        } elseif ($opt == "hmenu" or $opt == "hadmin") {
            $mclass = "class='cmenu pad5'";
            return "<a $mclass href=\"$href\">" . ucfirst($fName) . "</a>" . " \n";
        }
    }

    function appMenuItem($opt, $href, $fName) {
        if ($opt == "vapp" or $opt == "vappadmin") {
            $mclass = "class='menulevel'";
            return "<tr><td $mclass><a href=\"$href\">" . ucfirst($fName) . "</td></tr>" . " \n";
        } elseif ($opt == "happ" or $opt == "hadminapp") {
            $mclass = "class='cmenu pad5'";
            return "<a $mclass href=\"$href\">" . ucfirst($fName) . "</a>" . " \n";
        }
    }

    function pageMenuItems($opt) {
        $info = $this->getInfo("pages");
        if (empty($info))
            return;
        $mitems = $mitem = $mbar = "";
        $start = 0;
        foreach ($info as $key => $value) {
            $mbar = $mitem = "";
            $file = $value['name'];
            $ext = $this->h->fileExtPart($this->pagesDir . $file);
            $fName = $this->h->fileNamePart($file, "file"); // get File Name
            $href = $this->h->href(array('do' => "view", 'pname' => $fName, 'ext' => $ext));

            switch ($opt) {
                case "hmenu":
                case "vmenu":
                    if ($opt == "hmenu") {
                        if ($start == 1)
                            $mbar = "<a> | </a>";
                        $start = 1;
                    }
                    $mitem = $mbar . $this->pageMenuItem($opt, $href, $fName);
                    break;
                case "vadmin":
                    $mitem = $this->pageMenuItem($opt, $href, $fName);
                    break;
            }
            $mitems .= $mitem;
        }
        return $mitems;
    }

    function appMenuItems($opt) {
        $info = $this->getInfo("apps");
        if (empty($info))
            return;
        $mitems = $mitem = $mbar = "";
        $start = 0;
        foreach ($info as $key => $value) {
            $mbar = $mitem = "";
            $app = $value['name'];
            $href = $this->h->href(array('do' => "run", 'app' => $app));
            switch ($opt) {
                case "vappadmin":
                case "vapp":
                    $mitem = $this->appMenuItem($opt, $href, $app);
                    break;
            }
            $mitems .= $mitem;
        }
        return $mitems;
    }

    function menu($opt) {
        $mitems = "";
        switch ($opt) {
            case "vmenu":
            case "hmenu":
            case "vadmin":
            case "hadmin":
                $mitems = $this->pageMenuItems($opt);
                break;
            case "vappadmin":
            case "vapp":
                $mitems = $this->appMenuItems($opt);
        }
        if ($this->isAdmin()) {
            switch ($opt) {
                case "vappadmin":
                case "vadmin":
                case "vmenu":
                case "vapp":
                    $mitems = "<table>" . $mitems . "</table>";
                    break;
                case "hadmin":
                    $mitems = $this->h->cpanel();
                    break;
            }
            print $mitems;
        } else {
            switch ($opt) {
                case "vappadmin":
                case "vadmin":
                case "vapp":
                case "vmenu":
                    $mitems = "<table>" . $mitems . "</table>";
                    break;
                case "hmenu":
                    $mitems = $mitems . "<a> | </a>" . "<a class=\"cmenu pad5\" href=\"?do=login\">Login</a>";
                    break;
            }
            print $mitems;
        }
    }

    function writeMenu($info) {
        $string = '$' . 'info = ' . $this->h->var2String($info, "format") . ';';
        $path = $this->pagesDir . "/info.php";
        $ftext = "<?php\n" . $string . "\n?>";
        $this->h->writeFile($path, $ftext);
    }

    function metaData() {
        ?>
        <meta name="description" content="<?= $this->config['metadesc'] ?>" />
        <meta name="keywords" content="<?= $this->config['metakey'] ?>" />
        <title><?= $this->pageTitle() ?></title>
        <?= $this->searchRobots(); ?>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <?php
    }

    function loadInHeader() { // preload editor in head
        $this->metaData();
//		$this->loadAddOns();
        $this->onHead("onHead");  // call back to onHead in extensions things js, etc in head
        $this->onEditor("onHead");  // only when login to admin, for fck to work??
        //If not in admin, blank initEditor
        if (!isset($_SESSION['name'])) { // not sure what this does??
            print "<script type=\"text/javascript\">function initeditor(){}</script>\n";
        }
    }

    function pageTitle() {
        $trans = get_html_translation_table(HTML_ENTITIES);
        $str = $this->config['sitename'];
        $sitename = str_replace("(", "", strtr($str, $trans));
        if (isset($_REQUEST["pname"])) {
            $paget = ucfirst($_REQUEST['pname']);
            print html_entity_decode($sitename) . " - " . $paget;
        } else {
            print html_entity_decode($sitename);
        }
    }

    // need to work on this more!
    function getQueryParm($parm) { // not working too good!
        $return = "";
        $uri = parse_url(rawurldecode($_SERVER['REQUEST_URI']));
        if (isset($uri['query'])) {
            $query = $uri['query'];
            if ($parm) {
                $pos = $this->stripos($query, $parm . "=");
                if ($pos)
                    $return = substr($query, $pos + strlen($parm) + 1);
            }
        }
        return $return;
    }

    function searchRobots() {
        $seostring = '<meta name="revisit" content="3 days" /><meta name="robots" content="all" />';
        (!$this->isAdmin()) ? $return = $seostring : $return = '';
        return $return;
    }

    function captureCallback($hook, $contents) {
        $callback = $hook['callback'];
        $pattern = $hook['pattern'];
        if (function_exists($callback)) {
            ob_start();
            $result = preg_replace_callback($pattern, $callback, $contents);
            $return = ob_get_contents();
            ob_end_clean();
            return $return;
        }
    }

    function getParams($ptype, $pname) {
        $filespec = $this->addOnsDir . $ptype . "/" . $pname . "/info.php";
        $info = $this->h->loadInfo($filespec); // from dir
        $filespec = $this->addOnsDir . $ptype . "/" . $pname . "/params.php";
        $params = $this->h->loadInfo($filespec); // from dir
        if (!empty($params)) {
            foreach ($params as $param) {
                (!empty($info[$param['name']])) ? $pvalue = $info[$param['name']] : $pvalue = $param['default'];
                $paramsInfo[$param['name']] = $pvalue;
            }
        }
        return $paramsInfo;
    }

    // old stuff
}

// end class
?>