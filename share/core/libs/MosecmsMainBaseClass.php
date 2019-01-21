<?php

/**
 * class.main.base.php
 *
 * MoseCMS main base class that contain the base API
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

defined('_MOSECMS') or die('NOT allowed!');


class MosecmsMainBaseClass {

    var $version;
    var $iso;
    var $user;
    var $setupPw;
    var $coreDefaultDir;
    var $sitePrivateDefDir;
    var $sitePublicDefDir;
    var $packagesDefaultDir;
    var $addOnDir;
    var $templatesDir;
    var $editorsDir;
    var $contentsDir;
    var $pagesDir;
    var $macrosDir;
    var $appsDir;
    var $modsDir;
    var $cmodsDir;
    var $positionsDir;
//  var $packagesDir;
    var $liveRootDir;
    var $liveSite;
    var $siteShareDir;
//	var $sitePublicDir;  
//	var $sitePrivateDir;  
    var $jsDir;
    var $jsFromCoreDir;
    var $hook;
    var $hooks;
    var $contentHook;
    var $contentHooks;
    var $editorHook;
    var $editorHooks;
    var $frontPage;
    var $adminPage;
    // use by editor add-on
    var $defaultEditor;
    // set by each editor
    var $editorCode;
    var $editorHead;
    var $editorArea;
    var $info = array(); // one master info
    var $livecModsInfo = array(); // all cmods from live site 
    var $liveModsInfo = array(); // all mods from live site 
    var $liveMacrosInfo = array(); // all macros from live site 
    var $liveAppsInfo = array(); // all apps from live site 
    var $liveEditorsInfo = array(); // all editors from live site 
    var $livePagesInfo = array(); // all pages from live site 
    var $positionsInfo = array(); // all positions from live site 
    var $activecModsInfo = array(); // all active cmods from live site
    var $activeModsInfo = array(); // all active mods from live site
    var $activeMacrosInfo = array(); // all active apps from live site
    var $activeAppsInfo = array(); // all active apps from live site
    var $activeEditorsInfo = array(); // all active editors from live site
    var $activePagesInfo = array(); // all active pages from live site
    var $configDefault = array();
    var $config = array();
    var $h;  //helper class
    var $a;  // admin class

    function __construct() {
        
    }

    function loadModulesCount($position = "") {
        return count($this->getMods($position)) + count($this->getcMods($position));
    }

    function getTemplateName($templateVar = "template", $file = "index.html") {
        $mose_themecookie = $this->h->getVal($_COOKIE, 'mose_themecookie');
        $mose_themeselect = $this->h->getVal($_REQUEST, 'mose_themeselect', $mose_themecookie);
        ($mose_themeselect)  // set template base on cookie
                        ? $templateName = $mose_themeselect : $templateName = $this->h->getVal($this->config, $templateVar); // set template base on config screen
        // if nothing then get the "default" template
        if (!file_exists($this->templatesDir . $templateName . "/" . $file))
            $templateName = "default";
        return $templateName;
    }

    function currentSite() {
        ($this->config['sitepublic'] == $this->h->lastPart(getcwd(), DIRECTORY_SEPARATOR)) ? $site = "" // already at the site folder
                        : $site = $this->sitePublicDir() . "/"; // default site
        return $site;
    }

    function currentTemplateName() {
        return $this->getTemplateName("template", $this->frontPage);
    }

    function currentAdminTemplateName() {
        return $this->getTemplateName("admintemplate", $this->adminPage);
    }

    function currentTemplate() {
        return $this->templatesDir . $this->currentTemplateName() . "/";
    }

    function currentAdminTemplate() {
        return $this->templatesDir . $this->currentAdminTemplateName() . "/";
    }

    function getSkinName($skinVar = "skin") {
        ($skinVar == "adminskin") ? $template = $this->currentAdminTemplate() : $template = $this->currentTemplate();
        $current_css = $this->h->getVal($this->config, $skinVar);
        $cssFile = $template . "css/" . $current_css;
//    $this->h->debugO(array('name'=>$cssFile,'msg'=>'cssFile: ','debug'=>$this->h->debug,'format'=>''));
        if (!file_exists($cssFile))
            $current_css = "default.css";
        return $current_css;
    }

    function currentSkinName() {
        return $this->getSkinName("skin");
    }

    function currentAdminSkinName() {
        return $this->getSkinName("adminskin");
    }

    function currentSkin() {
        return $this->currentTemplate() . "css/" . $this->currentSkinName();
    }

    function currentAdminSkin() {
        return $this->currentAdminTemplate() . "css/" . $this->currentAdminSkinName();
    }

    function pageLoad($iMgr = "pages") {
        $contents = "";
        $info = $this->getInfo($iMgr);
        $pgPath = $this->getPagePath($iMgr);
        $cpage = $this->h->getRequestVal("pname");
        $fName = $this->h->getVal($info, $cpage);
        if (empty($cpage)) {
            $homepage = $this->config['homepage'];
            if ($pgFound = $this->h->fileExist($pgPath . $homepage . ".html"))
                ;
            else
                ($pgFound = $this->h->fileExist($pgPath . $homepage . ".htm"));
        } elseif (isset($cpage) && isset($fName['name'])) {
            $pgFound = $this->h->fileExist($pgPath . $fName['name']);
        } else
            $pgFound = $this->custom404();
        if (!empty($pgFound)) {
            $contents = $this->h->getIncludeContent($pgFound);
            $contents = $this->onContent($contents);
        } else {
            $contents = "<h2>Info file might not be current!</h2>";
        }
        return $contents;
    }

    function appLoad($isite = "") {
        $app = $this->h->getRequestVal("app");
        $filespec = $this->appsDir . $app . DIRECTORY_SEPARATOR . "index.php";
        (file_exists($filespec)) ? $page = $filespec : $page = $this->custom404();
        (!empty($page)) ? $return = $this->h->getIncludeContent($page) : $return = "<h2>Error: App not found!</h2>";
        return $return;
    }

    function loadModules($position = "") {
        $paramsInfo = array();
        $contents = "";
        // load content modules
        $cinfo = $this->getcMods($position);
        if (!empty($cinfo)) {
            foreach ($cinfo as $key => $mods) {
                $filespec = $this->cmodsDir . $mods['name'];
                if (file_exists($filespec))
                    $contents .= $this->h->getIncludeContent($filespec);
            }
        }
        // load modules
        $info = $this->getMods($position);
        if (!empty($info)) {
            foreach ($info as $key => $mods) {
                $filespec = $this->modsDir . $mods['name'] . "/index.php";
                if (file_exists($filespec))
                    $contents .= $this->h->getIncludeContent($filespec);
                $filespec = $this->modsDir . $mods['name'] . "/params.php";
                if (file_exists($filespec)) {
                    $paramsInfo = $this->h->loadInfo($filespec); // from dir
                    $this->paramsInfo[$mods['name']] = $paramsInfo;
                }
            }
        }
        return $contents;
    }

    function getPagePath($iMgr = "pages") {
        switch (strtolower($iMgr)) {
            case "positions":
                $path = $this->positionsDir;
                break;
            case "cmods":
                $path = $this->cmodsDir;
                break;
            default:
            case "mods":
                $path = $this->modsDir;
                break;
            case "pages":
                $path = $this->pagesDir;
                break;
        }
        return $path;
    }

    function getInfo($info) {
        $iArray = array();

        switch (strtolower($info)) {
            case "cmods":
                if ($this->isAdmin()) {
                    $iArray = $this->livecModsInfo;
                } else {
                    $iArray = $this->activecModsInfo;
                }
                break;
            case "mods":
                if ($this->isAdmin()) {
                    $iArray = $this->liveModsInfo;
                } else {
                    $iArray = $this->activeModsInfo;
                }
                break;
            case "macros":
                if ($this->isAdmin()) {
                    $iArray = $this->liveMacrosInfo;
                } else {
                    $iArray = $this->activeMacrosInfo;
                }
                break;
            case "apps":
                if ($this->isAdmin()) {
                    $iArray = $this->liveAppsInfo;
                } else {
                    $iArray = $this->activeAppsInfo;
                }
                break;
            case "pages":
                if ($this->isAdmin()) {
                    $iArray = $this->livePagesInfo;
                } else {
                    $iArray = $this->activePagesInfo;
                }
                break;
            default:
        }
        return $iArray;
    }

    function loadAddOnsByType($aDir, $pattern) {
        $filespec = $aDir . $pattern;
        $files = $this->h->filesList($filespec, "dir", "index");
        //   $this->h->debugO(array('name'=>$files,'msg'=>'files:','debug'=>$this->h->debug,'format'=>''));
        if (isset($files)) {
            foreach ($files as $value) {
                $filespec = $aDir . $value . DIRECTORY_SEPARATOR . "index.php";
//        $this->h->debugO(array('name'=>$filespec,'msg'=>'filespec:','debug'=>$this->h->debug,'format'=>''));
                $this->h->usingOnce($filespec);
            }
        }
    }

    function isAdmin() {
        return (isset($_SESSION['name']) && $_SESSION['name'] == $this->user);
    }

    function loadAddOnsEditor() {
        if ($this->isAdmin()) {
            $filespec = $this->editorsDir . strtolower($this->config['defaulteditor']) . "/index.php";
            print $this->h->getIncludeContent($filespec);
        }
    }

    function writeConfig($conf) {
        $this->config = array(
            'homepage' => $this->h->getVal($conf, 'homepage', "home"),
            'coredir' => $this->h->getVal($conf, 'coredir', $this->coreDir()),
            'packages' => $this->h->getVal($conf, 'packages', $this->packagesDir()),
            'siteprivate' => $this->h->getVal($conf, 'siteprivate', $this->sitePrivateDir()),
            'sitepublic' => $this->h->getVal($conf, 'sitepublic', $this->sitePublicDir()),
            'password' => $this->h->getVal($conf, 'password', $this->setupPw),
            'template' => $this->h->getVal($conf, 'template', "default"),
            'skin' => $this->h->getVal($conf, 'skin', "default"),
            'admintemplate' => $this->h->getVal($conf, 'admintemplate', "default"),
            'adminskin' => $this->h->getVal($conf, 'adminskin', "default"),
            'defaulteditor' => $this->h->getVal($conf, 'editor', "default"),
            'sitename' => $this->h->getVal($conf, 'sitename', "moseCMS"),
            'history' => $this->h->getVal($conf, 'history', ""),
            'custom404' => $this->h->getVal($conf, 'custom404', "custom404.html"),
            'onload' => $this->h->getVal($conf, 'onload', "init();initEditor();"),
            'metadesc' => $this->h->getVal($conf, 'metadesc', "MosECMS - My Open Source (Editable/Extensible/Easy) Content Management System"),
            'metakey' => $this->h->getVal($conf, 'metakey', "MosECMS, CMS, Easy CMS, Content Management System, Portal, Website Software"),
            'dbtype' => $this->h->getVal($conf, 'dbtype', "mysql"),
            'dbhost' => $this->h->getVal($conf, 'dbhost', "localhost"),
            'dblogin' => $this->h->getVal($conf, 'dblogin', "root"),
            'dbpw' => $this->h->getVal($conf, 'dbpw', ""),
            'dbname' => $this->h->getVal($conf, 'dbname', "mosecms"),
        );
        $string = '$' . 'moseConfig = ' . $this->h->var2String($this->config, "format") . ';';
        $protect = "defined( '_MOSECMS' ) or die( 'NOT allowed!' );\n";
        $configFile = $this->getConfigFile();
        $ftext = "<?php\n" . $protect . $string . "\n?>";
        $this->h->writeFile($configFile, $ftext);
    }

    function getConfigFile() {
        $configFile = $this->config['sitepublic'] . DIRECTORY_SEPARATOR . "config.php";
        if ($this->config['sitepublic'] == $this->h->lastPart(getcwd(), DIRECTORY_SEPARATOR)) {
            $configFile = "config.php";
        }
        return $configFile;
    }

    function setup() {
// this does not work in hostupon, why?
//    if ($this->h->lastPart($this->h->siteRoot(),"/")<>$this->h->lastPart(getcwd(),DIRECTORY_SEPARATOR)) die( "Setup can't run here!" );
        include_once("MosecmsSetupClass.php");
        $mosecmsSetup = new MosecmsSetupClass();
        if (!$this->h->getVal($_REQUEST, 'password'))
            $mosecmsSetup->setup();
        elseif ($this->h->getVal($_REQUEST, 'setup') == "setup") {
            $conf['homepage'] = $this->h->getVal($_REQUEST, 'homepage');
            $conf['password'] = $_SESSION['setup'] = $this->h->encryptPw($this->h->getVal($_REQUEST, 'password'));
            $conf['sitename'] = $this->h->getVal($_REQUEST, 'sitename');
            $pgPath = $this->pagesDir;
            $this->h->rename($pgPath . $this->config['homepage'] . ".html", $pgPath . $conf['homepage'] . ".html");
            $this->writeConfig($conf);
            $mosecmsSetup->setupCompleted();
        }
    }

    function cssImport($cssFile) {
        return '<style type="text/css" media="all">@import "' . $cssFile . '";</style>';
    }

    function cssLink($cssFile) {
        return '<link rel="stylesheet" href="' . $cssFile . '" type="text/css" media="screen" title="Screen" charset="utf-8" />';
    }

    function custom404() {
        return $this->h->fileExist($this->config['custom404']);
    }

    function editorSaveForm($pagecontent) {
        $mgr = $this->h->getRequestVal("mgr");
        if (empty($mgr))
            $mgr = "pages";
        $this->h->debugO(array('name' => $mgr, 'msg' => 'mgr:', 'debug' => $this->h->debug, 'format' => ''));
        $body = $this->editorCode . '<p />' . $this->editorArea . $this->h->editorSaveButton($pagecontent);
        return $this->h->form("?do=admin&mgr=" . $mgr . "&task=save", $body);
    }

    function coreDir() {
//        return $this->h->siteAbsDir() . DIRECTORY_SEPARATOR . $this->siteShareDir . DIRECTORY_SEPARATOR . $this->coreDefaultDir;
        return $this->siteShareDir . DIRECTORY_SEPARATOR . $this->coreDefaultDir;
    }

    function packagesDir() {
//        return $this->h->siteAbsDir() . DIRECTORY_SEPARATOR . $this->siteShareDir . DIRECTORY_SEPARATOR . $this->packagesDefaultDir;
        return $this->siteShareDir . DIRECTORY_SEPARATOR . $this->packagesDefaultDir;
    }

    function sitePrivateDir() {
//        return $this->h->siteAbsDir() . DIRECTORY_SEPARATOR . $this->sitePrivateDefDir;
        return $this->sitePrivateDefDir;
    }

    function sitePublicDir() {
        (empty($this->config['sitepublic'])) ? $site = $this->sitePublicDefDir : $site = $this->config['sitepublic'];
        return $site;
    }

    function internalEditor($pagecontent) {
        $this->editorArea = "<textarea name=\"content\" id=\"content\" cols=\"100%\" rows=\"15\" wrap=\"off\">" . $pagecontent . "</textarea>";
        // js to run in editor save form
        $this->editorCode = "";
        print $this->editorSaveForm($pagecontent);
    }

    // don't touch work solid
    function getMergedInfo($info = "pages") {
        switch (strtolower($info)) {
            case "positions":
                $iDir = $this->h->getFilesList($this->positionsDir);
                $iInfo = $this->positionsInfo;
                break;
            case "macros":
                $iDir = $this->h->getFilesList($this->macrosDir);
                $iInfo = $this->liveMacrosInfo;
                break;
            case "apps":
                $iDir = $this->h->getFilesList($this->appsDir);
                $iInfo = $this->liveAppsInfo;
                break;
            case "editors":
                $iDir = $this->h->getFilesList($this->editorsDir);
                $iInfo = $this->liveEditorsInfo;
                break;
            case "mods":
                $iDir = $this->h->getFilesList($this->modsDir);
                $iInfo = $this->liveModsInfo;
                break;
            case "cmods":
                $iDir = $this->h->getFilesList($this->cmodsDir);
                $iInfo = $this->livecModsInfo;
                break;
            default:
            case "pages":
                $iDir = $this->h->getFilesList($this->pagesDir);
                $iInfo = $this->livePagesInfo;
                break;
        }
        /*
          $this->h->debugO(array('name'=>$iDir,'msg'=>'iDir:','debug'=>$this->h->debug,'format'=>''));
          $this->h->debugO(array('name'=>$iInfo,'msg'=>'iInfo:','debug'=>$this->h->debug,'format'=>''));
         */
        return $this->mergeSort($iDir, $iInfo);
    }

    function mergeSort($files, $info, $field = "ordering") {
        /*
          $this->h->debugO(array('name'=>$iStructure,'msg'=>'iStructure:','debug'=>$this->h->debug,'format'=>'x'));
          $this->h->debugO(array('name'=>$files,'msg'=>'files:','debug'=>$this->h->debug,'format'=>'x'));
          $this->h->debugO(array('name'=>$info,'msg'=>'info:','debug'=>$this->h->debug,'format'=>'x'));
         */
        if (empty($files))
            return; // info might be empty
        (!empty($info)) ? $i = $this->h->getLastArrayItem($info, "id") : $i = 0;
        foreach ($files as $file) {

            $new = true;
            if (!empty($info)) { // if info is empty assume new
                foreach ($info as $page => $value) {
                    $id = $value['id'];
                    $fName = $this->h->fileNamePart($file, "file"); // get File Name
                    $pageFname = $this->h->fileNamePart($value['name'], "file"); // get File Name
//          $this->h->debugO(array('name'=>$value,'msg'=>'value:','debug'=>$this->h->debug));
                    if ($fName == $pageFname) {
                        $new = false;
                        $info[$fName]['name'] = $file;
                        $pages[$fName] = $info[$fName];
                    }
                }
            }
            if ($new) { // new file found
                $i++;
                $fName = $this->h->fileNamePart($file, "file");
                $pages[$fName] = $this->info;
                $pages[$fName]['name'] = $file;
                $pages[$fName]['id'] = $i;
                $pages[$fName]['ordering'] = sprintf("%s", $i);
            }
        }
//    $this->h->debugO(array('name'=>$pages,'msg'=>'pages:','debug'=>$this->h->debug,'format'=>'x'));
        return $this->h->sortArrayByField($pages, $field);
    }

    function onContent($contents = '') { //	content has {:yourmacro} this in it
//		$this->h->debugO(array('name'=>$this->contentHooks,'msg'=>"contentHooks:",'debug'=>$this->h->debug));
        if (empty($this->contentHooks))
            return $contents;
        foreach ($this->contentHooks as $key => $hook) {
//			$this->h->debugO(array('name'=>$hook,'msg'=>"hook:",'debug'=>$this->h->debug));
            $callback = $hook['callback'];
            $onEvent = $hook['onEvent'];
            if ($onEvent == "onContent" && function_exists($callback)) {
                $pattern = $hook['pattern'];
//				$this->h->debugO(array('name'=>$callback,'msg'=>"callback:",'debug'=>$this->h->debug));
                $contents = preg_replace_callback($pattern, $callback, $contents);
            }
        }
        return $contents;
    }

    function onHead($inEvent, $contents = "") {
        if (empty($this->contentHooks))
            return array();
        foreach ($this->contentHooks as $key => $hook) {
//			$this->h->debugO(array('name'=>$hook,'msg'=>"hook:",'debug'=>$this->h->debug));    
            $callback = $hook['callback'];
            $hookName = $hook['name'];
            $onEvent = $hook['onEvent']; // onHead, onEdit
            if ($onEvent == $inEvent && function_exists($callback)) {
//				$this->h->debugO(array('name'=>$callback,'msg'=>"callback:",'debug'=>$this->h->debug));      
                $result = call_user_func($callback, $contents); // false on error
            }
        }
    }

    function onEditor($inEvent, $contents = "") {
        if (empty($this->editorHooks))
            return array();
        foreach ($this->editorHooks as $key => $hook) {
//			$this->h->debugO(array('name'=>$hook,'msg'=>"hook:",'debug'=>$this->h->debug));     
            $callback = $hook['callback'];
            $editor = $hook['name'];
            $onEvent = $hook['onEvent']; // onHead, onEdit
            if ($onEvent == $inEvent && function_exists($callback) && ($this->defaultEditor == $editor)) { // == editor to avoid dup textarea 
//				$this->h->debugO(array('name'=>$callback,'msg'=>"callback:",'debug'=>$this->h->debug));       
                $result = call_user_func($callback, $contents); // false on error
            }
        }
    }

    // register hook for mosecms with hook type, event, hook name,  callback and pattern
    function hooksRegister($hook, $onEvent, $name, $callback, $pattern = "") {
        if (!($hook or $onEvent or $name or $callback))
            die("Can't register hook!");
        switch ($hook) {
            case ($hook == "contentHook"):
                if (isset($pattern)) {
                    $this->contentHooks[$callback] = array(
                        'name' => $name, 'onEvent' => $onEvent, 'hook' => $hook, 'callback' => $callback, 'pattern' => $pattern,
                    );
                    $this->hooks[$callback] = array(
                        'name' => $name, 'onEvent' => $onEvent, 'hook' => $hook, 'callback' => $callback, 'pattern' => $pattern,
                    );
                }
                break;
            case ($hook == "editorHook"):
                $this->editorHooks[$callback] = array(
                    'name' => $name, 'onEvent' => $onEvent, 'hook' => $hook, 'callback' => $callback,
                );
                $this->hooks[$callback] = array(
                    'name' => $name, 'onEvent' => $onEvent, 'hook' => $hook, 'callback' => $callback,
                );
                break;
            default:
        }
    }

    // session handling
    function checkLoginPasswordRedirect() {
        $buff = ""; // if nothing show normal login form
        $passwd = $this->h->getRequestVal("passw"); // get value from login form
        if (!empty($passwd)) {
            $pw = $this->h->encryptPw($passwd);
            if ($pw == $this->config['password']) {
                $_SESSION['name'] = $this->user;
                $_SESSION['site_id'] = $pw;
                $this->h->redirect("?do=admin", "<b>Welcome, Admin!</b>");
            } else
                $buff = "<h3>Incorrect Password!</h3>";
        }
        return $buff;
    }

    function sessionCheckPassword() {
        $password = $this->config['password'];
        $username = $this->h->getVal($_SESSION, 'name');
        $site_id = $this->h->getVal($_SESSION, 'site_id');
        $setup = $this->h->getVal($_SESSION, 'setup');
        // work solid!
        if ((empty($setup) && !empty($username) && !empty($password) && $site_id <> $password) or ( !empty($setup) && !empty($password) && $setup <> $password)) {
            print "<br />Error: Not Authorized!  Press refresh and try again!";
            session_destroy();
            exit;
        } else if (!empty($password) && $password <> $this->h->encryptPw($this->setupPw)) {
            return true;
        } else {
            $this->h->debugO(array('name' => $this->config, 'msg' => "config: ", 'debug' => $this->h->debug));
            $_SESSION['setup'] = "";  // clear setup session
            return false;
        }
    }

    function addOnsDir() {
        return $this->addOnsDir;
    }

    function usingPackage($iDir, $iFile) {
        if (empty($iDir) or empty($iFile))
            return;
        return $this->h->usingOnce($this->config['packages'] . DIRECTORY_SEPARATOR . $iDir . DIRECTORY_SEPARATOR . $iFile);
    }

    function usingPackageFromCore($iDir, $iFile) {
        if (empty($iDir) or empty($iFile))
            return;
        return $this->h->usingOnce($this->config['coredir'] . DIRECTORY_SEPARATOR . $this->packagesDefaultDir . DIRECTORY_SEPARATOR . $iDir . DIRECTORY_SEPARATOR . $iFile);
    }

    function usingLibsFromCore($iFile) {
        if (empty($iFile))
            return;
        return $this->h->usingOnce($this->config['coredir'] . DIRECTORY_SEPARATOR . "libs" . DIRECTORY_SEPARATOR . $iFile);
    }

    function usingJSFromCore($iDir, $iFile) {
        if (!empty($iDir) and ! empty($iFile))
            return $this->h->usingJS($this->jsFromCoreDir . $iDir . "/" . $iFile);
    }

    function usingJS($iDir, $iFile) {
        if (!empty($iDir) and ! empty($iFile))
            return $this->h->usingJS($this->jsDir . $iDir . "/" . $iFile);
    }

    // need to consolidated this
    function getcMods($position = "") {
        $info = $this->getInfo("cmods");
        if (empty($info))
            return;
        $mods4Position = array();
        $filespec = $this->cmodsDir . "*"; // this cause some weird thing with editor
        $files = $this->h->filesList($filespec, "fullname", "index");
        if (isset($files)) {
            foreach ($info as $key => $mods) {
                if (isset($mods['position']) && ($mods['statusto'] == "hide") && strtolower($mods['position']) == strtolower($position)) {
                    foreach ($files as $value) {
                        if ($mods['name'] == $value)
                            $mods4Position[$key] = $mods;
                    }
                }
            }
        }
        return $mods4Position;
    }

    function getMods($position = "") {
        $info = $this->getInfo("mods");
        if (empty($info))
            return;
        $mods4Position = array();
        $filespec = $this->modsDir . "*";
        $files = $this->h->filesList($filespec, "dir", "index");
        if (isset($files)) {
            foreach ($info as $key => $mods) {
                if (isset($mods['position']) && ($mods['statusto'] == "hide") && strtolower($mods['position']) == strtolower($position)) {
                    foreach ($files as $value) {
                        if ($mods['name'] == $value)
                            $mods4Position[$key] = $mods;
                    }
                }
            }
        }
        return $mods4Position;
    }

// old stuff
    function xxcoreDir() {
        return $this->h->fileNamePart(basename($this->config['coredir']), "dir");
    }

    function xxpackagesDir() {
        return $this->h->fileNamePart(basename($this->config['packages']), "dir");
    }

    function xpackagesDir() {
        return $this->packagesDir;
    }

    function xusingPackage($iFile) {
        return $this->h->usingOnce($this->packagesDir() . $iFile);
    }

    function xloadAddOnsEditor() {
        if ($this->isAdmin()) {
            $filespec = $this->addOnsDir . $this->editorsDir . strtolower($this->config['defaulteditor']) . ".php";
            print $this->h->getIncludeContent($filespec);
        }
    }

    function xcurrentSiteTemplate() {
        return $this->currentSite() . $this->currentTemplate();
    }

    function xcurrentSiteAdminTemplate() {
        return $this->currentSite() . $this->currentAdminTemplate();
    }

}

?>