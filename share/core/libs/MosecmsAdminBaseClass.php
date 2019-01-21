<?php

/**
 * class.admin.php
 *
 * MoseCMS admin base class that contain the administrative base API
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

class MosecmsAdminBaseClass {

    var $m; // main class
    var $h; // helper class


    function pageNew($iMgr = "pages") {
        $newpage = $this->h->getRequestVal("new");
        if ($newpage == "Enter Name")
            $msg = "Please use a different page name.";
        else {
            $filespec = $this->m->getPagePath($iMgr) . $newpage . ".html";
//			pr($filespec);
            $text = "<div><h2>" . ucfirst($newpage) . "</h2>Place your content here..</div>";
            $this->h->writeFile($filespec, $text);
            $msg = $newpage . " page successfully created.";
            $this->infoUpdate($iMgr);
        }
        $this->redirect2Admin($msg);
    }

    function pageDelete($iMgr = "pages") {
        $status = false;
        $cpage = $this->m->getPagePath($iMgr) . "/" . $_REQUEST['pname'];
        if ($this->h->delete($cpage . ".html")) {
            $msg = $_REQUEST['pname'] . " successfully deleted. \n";
            $status = true;
        } elseif ($this->h->delete($cpage . ".htm")) {
            $msg = $_REQUEST['pname'] . " successfully deleted. \n";
            $status = true;
        } else
            $msg = "The page cannot be deleted. Please check your file permissions..";
        if ($status == true)
            $this->infoUpdate($iMgr);
        $this->redirect2Admin($msg);
    }

    function pageRename($iMgr = "pages") {
        $msg = "";
        $page = $this->h->getRequestVal("pname");
        $newname = $this->h->getRequestVal("newname");
        $pid = $this->h->getRequestVal("pid");
        $path = $this->m->getPagePath($iMgr);
        if ($iMgr == "positions") {
            $oldFname = $path . $page . ".html";
            $newFname = $path . $newname . ".html";
        } else {
            $oldFname = $path . $page;
            $newFname = $path . $newname;
        }
        if ($newname && $newname <> "null") {
            $msg = "Rename not performed! ";
            if ($this->h->rename($oldFname, $newFname)) {
                $msg = $oldFname . " successfully renamed to " . $newFname . ". \n";
                $msg .= $page . " successfully renamed to " . $newname . ". \n";
                $this->infoUpdate($iMgr);
            }
        }
        $this->redirect2Admin($msg);
    }

    function pageSave($iMgr = "pages") {
        $page = $this->h->getRequestVal('savepage');
        $fileExt = $this->h->getRequestVal('extension');
        $path = $this->m->getPagePath($iMgr);
        $filespec = $path . $page . $fileExt;
        //		$path=$this->m->pagesDir."/".$page.$fileExt;
        $text = $_POST['content'];
        $this->h->pr(array('name' => $filespec, 'msg' => "filespec", 'debug' => $this->h->debug));
        $ftext = stripcslashes($text);
        $this->h->writeFile($filespec, $ftext);
        //Save last version;
        if ($this->m->config['history'] != "") {
//			$path=$this->m->pagesDir."/".$this->m->config['history']."/".stripcslashes($_REQUEST['savepage'])."_".date("m-d-y_g.i.sa").$_REQUEST['extension'];
// to be look latter
            $path = $this->m->pagesDir . $this->m->config['history'] . stripcslashes($_REQUEST['savepage']) . "_" . date("m-d-y_g.i.sa") . $fileExt;
            $text = base64_decode($_POST['lastrev']);
            $ftext = stripcslashes($text);
            $this->h->writeFile($path, $ftext);
        }
        $this->redirect2Admin($_REQUEST['savepage'] . " has been saved!");
    }

// to be work on, developing here
    function pageMetaInfo($iMgr = "pages") {
        $info = $this->m->getInfo($iMgr);
        $pname = $this->h->getRequestVal("pname");
        $page = $this->h->fileNamePart($pname, "file"); // get File Name
        $metaData = $detail = "";
        if (!empty($info)) {
            $thispage = $info[$pname];
            $id = $this->h->getVal($thispage, 'id');
            $editor = $this->h->getVal($thispage, 'editor');

            $detail = "Title: " . $this->h->input(array('name' => 'title' . $id, 'value' => $thispage['title'])) . "<br />"
                    . "Publish: " . $this->h->input(array('name' => 'publish' . $id, 'value' => $thispage['publish'])) . "<br />"
                    . "Editor: " . $this->getEditorList("editor" . $id, $editor) . "</td>"
            ;
        }
        $href = $this->h->href(array('do' => 'admin', 'mgr' => 'info', 'task' => 'infosave', 'app' => $iMgr, 'pname' => $pname));
        $body = "\n<table>" . $metaData . $detail . "</table><br /><div>" . $this->h->submit(array('value' => "Save Meta Info")) . "</div>";
        print "<fieldset>"
                . $this->h->tag("legend", "Meta Info: " . ucfirst($pname))
                . $this->h->form($href, $body)
                . "</fieldset>";
    }

    function pageEdit($iMgr = "pages") {
        $page = $this->h->getRequestVal("pname");
        $ext = $this->h->getRequestVal("ext");
        $file = $page . "." . $ext;
        $editorFunction = $this->loadThisPageEditor2Head($page, $iMgr); // override global editor if set by page
        $path = $this->m->getPagePath($iMgr);
//		pr($path,"path");
        $contents = $this->h->readPageContent($path . $file);
        print "<div class=\"content\"> \n<h3>Editing: " . $file . "</h3> \n";
        if ($this->m->defaultEditor == "default" or $this->m->defaultEditor === "") {
            $this->m->internalEditor($contents);
        } else {
            $result = $this->m->onEditor("onEdit", $contents);
        }
        print "</div>";
    }

    function pageUpload($folder, $iMgr = "pages") {
        if (isset($_REQUEST['upload'])) {
            move_uploaded_file($_FILES['uploadFile'] ['tmp_name'], "./" . $folder . "{$_FILES['uploadFile'] ['name']}");
            $i = $_FILES['uploadFile']['error'];
            $this->h->debugO(array('name' => $_FILES['uploadFile']['type'], 'msg' => "type", 'debug' => $this->h->debug));
            $this->h->debugO(array('name' => $_FILES['uploadFile']['error'], 'msg' => "error", 'debug' => $this->h->debug));
            $mfs = ceil($_REQUEST['MAX_FILE_SIZE'] / 1024);
            $info = array(
                'file succesfully uploaded.',
                'The uploaded file exceeds the server allowed limit filesize',
                'The uploaded file exceeds ' . $mfs . ' KBytes.',
                'The uploaded file was only partially uploaded.',
                'No file was uploaded.');
            list($a[0], $a[1], $a[2], $a[3], $a[4]) = $info;
            if ($i == 0)
                $this->infoUpdate($iMgr);
            $this->redirect2Admin($a[$i]);
        }
    }

    function pageView($iMgr = "pages") {
        $page = $this->h->getRequestVal("pname");
        ($fExt = $this->h->getRequestVal("ext")) ? $ext = ".$fExt" : $ext = "";
        $fName = $page . $ext;
        print "<div class='pagetitle'>Title: &nbsp;" . $page . "</div><br />";
        print $this->jsRename("pages");
        print "<div align='right'>" . $this->pageInfo($fName, $iMgr) . "</div>";
        echo $this->m->pageLoad($iMgr);
    }

    function pageInfo($fName, $iMgr = "pages") {
        $preview = $metaInfo = "";

        $path = $this->m->getPagePath($iMgr);
        $filespec = $path . $fName;
        $ext = $this->h->fileExtPart($filespec);
        $page = $this->h->fileNamePart($fName, "file"); // get File Name
        $info = $this->m->getInfo($iMgr);
        $cpage = $this->h->getVal($info, $page);
        $pid = $this->h->getVal($page, 'id');

        $hrefPreview = $this->h->href(array('do' => 'admin', 'task' => "preview", 'pname' => $page, 'mgr' => $iMgr));
        $hrefMetaInfo = $this->h->href(array('do' => 'admin', 'task' => "metainfo", 'pname' => $page, 'mgr' => $iMgr));
        $hrefDelete = $this->h->href(array('do' => 'admin', 'task' => 'del', 'pname' => $page, 'mgr' => $iMgr));
        $hrefEdit = $this->h->href(array('do' => 'admin', 'task' => 'edit', 'pname' => $page, 'ext' => $ext, 'mgr' => $iMgr));
        if ($iMgr <> "template" and $iMgr <> "css") {
            $preview = $this->h->a($hrefPreview, "Preview", "_blank") . "&nbsp;|&nbsp;";
            $metaInfo = $this->h->a($hrefMetaInfo, "Meta Info") . "&nbsp;|&nbsp;";
        }

        return $metaInfo . $preview
                . $this->h->a($hrefEdit, "Edit") . "&nbsp;|&nbsp;"
                . $this->h->a($hrefDelete, "Delete") . "&nbsp;|&nbsp;"
                . $this->h->a($this->h->jsOnclick("moserenamefile('$fName')"), "Rename")
        ;
        //     .$this->h->a("#\" onclick=\"moserenamefile('".$fName."')","Rename")      
    }

    function jsRename($iMgr = "") {
        $href = $this->h->href(array('do' => 'admin', 'mgr' => $iMgr, 'task' => 'rename'));
        $renamewin = "function moserenamefile(name){
	newname=prompt('Enter new page name',name);
	window.location='index.php" . $href . "&pname='+name+'&newname='+newname;
}";
        return $this->h->wrapjs($renamewin);
    }

    function getMgrDir($iMgr = "pages") {
        switch (strtolower($iMgr)) {
            case "cmods":
                $dir = $this->m->cmodsDir;
                break;
            case "apps" :
                $dir = $this->m->appsDir;
                break;
            case "editors" :
                $dir = $this->m->editorsDir;
                break;
            case "mods" :
                $dir = $this->m->modsDir;
                break;
            case "macros" :
                $dir = $this->m->macrosDir;
                break;
            case "positions" :
                $dir = $this->m->positionsDir;
                break;
            case "pages" :
                $dir = $this->m->pagesDir;
                break;
            default:
                $dir = "";
        }
        return $dir;
    }

    function writeInfo($info, $infoFile) {
        $msg = " info can't be saved!";
        if (isset($info)) {
// create new file if not there
//			if (file_exists($infoFile)) {
            $this->h->writeInfo2File($info, $infoFile);
            $msg = " info have been saved!";
//			}
        }
        return $infoFile . $msg;
    }

// don't touch, work solid
    function infoUpdate($iMgr = "pages") {
        $info = $this->m->getMergedInfo($iMgr);
        $infoFile = $this->getMgrDir($iMgr) . "index.php";
        echo $this->writeInfo($info, $infoFile);
    }

    function infoSave() {
        $iMgr = strtolower($this->h->getRequestVal("app"));
        $mInfo = $this->m->getMergedInfo($iMgr);
        foreach ($mInfo as $pid => $value) {
            $file = $value['name'];
//			$info[$id] = $this->m->info;
            $this->m->info['name'] = $file;
            $this->m->info['id'] = $value['id'];
            $this->m->info['statusto'] = $value['statusto'];
            $this->m->info['editor'] = $this->h->getInfoChange($value, "editor");
            $this->m->info['ordering'] = $this->h->getInfoChange($value, "ordering");
            $this->m->info['publish'] = $this->h->getInfoChange($value, "publish");
            $this->m->info['position'] = $this->h->getInfoChange($value, "position");
            $this->m->info['title'] = $this->h->getInfoChange($value, "title");
            $fName = $this->h->fileNamePart($file, "file"); // get File Name
            $info[$fName] = $this->m->info;
        }
        $infoFile = $this->getMgrDir($iMgr) . "index.php";
        echo $this->writeInfo($info, $infoFile);
    }

// don't touch, work solid
    // when switch template reset skin to default.css
    function resetSkin($conf, $template = "template") {
        $reset = false;
        $ctemplate = $this->h->getInfoChange($conf, $template);
        if ($ctemplate <> $this->m->config[$template]) {
            $reset = true;
        }
        return $reset;
    }

    function configSave() {
        $conf = $_REQUEST;
        if ($_REQUEST['save'] == "New Admin Password" || $_REQUEST['save'] == "") {
            $conf['password'] = $_REQUEST['save'] = $this->m->config['password'];
        } else if ($_REQUEST['save']) {
            $conf['password'] = $this->h->encryptPw($_REQUEST['save']);
        }
        if ($this->resetSkin($conf, "admintemplate")) {
            $conf['adminskin'] = "default.css";
        }
        if ($this->resetSkin($conf, "template")) {
            $conf['skin'] = "default.css";
        }
        // not used need to do this in template/skin manager
        /*
          if (!isset($_REQUEST['template']) || $_REQUEST['template']=="" || $_REQUEST['template']=="New Template") {
          $conf['template'] = $this->m->config['template'];
          }

          if (!isset($_REQUEST['admintemplate']) || $_REQUEST['admintemplate']=="" || $_REQUEST['admintemplate']=="New Template") {
          $conf['admintemplate'] = $this->m->config['admintemplate'];
          }
         */
        $conf['onload'] = $this->m->config['onload'];
        $conf['sitename'] = $this->m->config['sitename'];
        $this->m->writeConfig($conf);
//		$_SESSION['site_id']=sha1($_REQUEST['save']);
        $_SESSION['site_id'] = $conf['password'];
//		$_SESSION['settings']=$this->m->config['password'];	// why do it twice?
        $pgPath = $this->m->pagesDir;
        if ($_POST['history'] != "" && !file_exists($pgPath . $_POST['history']))
            mkdir($pgPath . $_POST['history'], 0700);
        $this->h->redirect("?do=admin&task=config", "Config Saved!");
    }

    function loadThisPageEditor2Head($page, $iMgr = "pages") { // work too!
        $editor = strtolower($this->m->defaultEditor);
        $editorFunction = 'mosecms_editor_' . strtolower($editor); //get the global editor
        $editor4Page = $this->getPageEditor($page, $iMgr);
        /*
          $pagesInfo = $this->h->loadInfo($this->m->pagesDir);
          $editor4Page=$pagesInfo[$page]['editor'];
         */
//		$this->h->debugO(array('name'=>$this->m->hooks,'msg'=>"run editor=>",'debug'=>$this->h->debug));
        if ($editor4Page <> $editor && $editor4Page <> "default" && $editor4Page) {
            if ($editor == "tinymce")
                print "<head>" . $this->h->wrapjs("tinyMCE=null;") . "</head>"; // unload tinymce
            $editorFunction = 'mosecms_editor_' . strtolower($editor4Page);
            $this->m->defaultEditor = $editor4Page; // override the global editor

            if (!function_exists($editorFunction)) {
                $addonsdir = $this->m->addOnsDir;
//				$filename = $addonsdir."/".$this->m->editorsDir."/".$editor4Page."/index.php";
                $filename = $this->m->editorsDir . $editor4Page . "/index.php";
                print "<head>" . $this->h->getIncludeContent($filename) . $this->m->onEditor("onHead") . "</head>"; // for tinymce to work
//				$this->m->onHead();	// load edit via onHead onEvent
            }
        }
        return $editorFunction;
    }

    function work_loadThisPageEditor2Head($page, $iMgr = "pages") {
        $editor = strtolower($this->m->defaultEditor);
        $editorFunction = 'mosecms_editor_' . strtolower($editor); //get the global editor
        $editor4Page = $this->getPageEditor($page, $iMgr);
        /*
          $pagesInfo = $this->h->loadInfo($this->m->pagesDir);
          $editor4Page=$pagesInfo[$page]['editor'];
         */
//		$this->h->debugO(array('name'=>$this->m->hooks,'msg'=>"run editor=>",'debug'=>$this->h->debug));
        if ($editor4Page <> $editor && $editor4Page <> "default" && $editor4Page) {
            if ($editor == "tinymce")
                print "<head>" . $this->h->wrapjs("tinyMCE=null;") . "</head>"; // unload tinymce
            $editorFunction = 'mosecms_editor_' . strtolower($editor4Page);
            $this->m->defaultEditor = $editor4Page; // override the global editor
            if (!function_exists($editorFunction)) {
                $addonsdir = $this->m->addOnsDir;
//				$filename = $addonsdir."/".$this->m->editorsDir."/".$editor4Page.".php";
                $filename = $this->m->editorsDir . $editor4Page . "/index.php";
//pr($filename);        
                print "<head>" . $this->h->getIncludeContent($filename) . "</head>";
                $this->m->onEditor("onHead");
//				$this->m->onHead("onHead");        
//				$this->m->onHead();	// load edit via onHead onEvent
            }
        }
        return $editorFunction;
    }

    function getPageEditor($page, $iMgr = "pages") {
        switch (strtolower($iMgr)) {
            case "cmods":
                $info = $this->h->loadInfo($this->m->cmodsDir . "index.php");
                $return = $this->h->getVal($info[$page], 'editor');
                break;
            default:
            case "pages":
                $info = $this->h->loadInfo($this->m->pagesDir . "index.php");
                $return = $this->h->getVal($info[$page], 'editor');
                break;
        }
        return $return;
    }

    function redirect2Admin($imsg) {
        $this->h->redirect("?do=admin&mgr=pages", $imsg);
    }

    function pageNewButton($iMgr = "pages") {
        $href = $this->h->href(array('do' => 'admin', 'mgr' => $iMgr, 'task' => 'new'));
        return '<form method="post" action="' . $href . '">
<input type="text" name="new" value="Enter Name" />
<input type="submit" class="submit" name="newpage" value="Create" />
</form>';
    }

    function pageUploadButton($iMgr = "pages") {
        $href = $this->h->href(array('do' => 'admin', 'mgr' => $iMgr, 'task' => 'upload'));
        return '<form enctype="multipart/form-data" action="' . $href . '" method="post">
<input type="file" name="uploadFile">
<input type="hidden" name="MAX_FILE_SIZE" value="640000">
<input type="submit" class="submit" name="upload" value="Upload">
</form>';
    }

    function showHide($task) {
        $page = $this->h->getRequestVal("pname");
        $app = strtolower($this->h->getRequestVal("app"));
        switch ($app) {
            case "cmods":
                $this->m->livecModsInfo[$page]['statusto'] = $this->h->statusToggle($task);
                break;
            case "mods":
                $this->m->liveModsInfo[$page]['statusto'] = $this->h->statusToggle($task);
                break;
            case "apps":
                $this->m->liveAppsInfo[$page]['statusto'] = $this->h->statusToggle($task);
                break;
            case "editors":
                $this->m->liveEditorsInfo[$page]['statusto'] = $this->h->statusToggle($task);
                break;
            case "pages":
                $this->m->livePagesInfo[$page]['statusto'] = $this->h->statusToggle($task);
                break;
            case "macros":
                $this->m->liveMacrosInfo[$page]['statusto'] = $this->h->statusToggle($task);
//				pr($this->m->liveMacrosInfo[$page]);
                break;
            default:
        }
        $this->infoUpdate($app);
        return "<br />" . $task . " operation is successfully in menu.";
    }

// something strange with * that cause editor to skip in the function list
    function getSkinsName($template, $skin = "skin") {
//		$filesListArray = $this->h->filesList($this->m->templatesDir.$template."/css/"."*.css", "dir","index");
        $filesListArray = $this->h->filesList($this->m->templatesDir . $template . "/css/" . "*", "file", "index|setup");
        return $this->h->optionList($filesListArray, $skin, $this->m->config[$skin]);
    }

    function getPositionList($varName = "position", $default = "left") {
        $filesListArray = $this->h->filesList($this->m->positionsDir . "*.html", "file", "index");
        return $this->h->optionList($filesListArray, $varName, $default);
    }

    function getEditorList($varName = "editor", $default = "") {
        if ($default == "")
            $default = $this->m->config['defaulteditor'];
//		$filesListArray = $this->h->filesList(getcwd()."/".$this->m->editorsDir."/"."*", "dir","index");   
        foreach ($this->m->activeEditorsInfo as $activeEditor)
            $filesListArray[] = $activeEditor['name'];
        return $this->h->optionList($filesListArray, $varName, $default);
    }

    function getTemplatesName($template = "template") {
        $filesListArray = $this->h->filesList($this->m->templatesDir . "*", "dir", "index");
        return $this->h->optionList($filesListArray, $template, $this->m->config[$template]);
    }

// old stuff
    function xgetEditorList($varName = "editor", $default = "") {
        if ($default == "")
            $default = $this->m->config['defaulteditor'];
        $filesListArray = $this->h->filesList($this->m->addOnsDir . $this->m->editorsDir . "*.php", "file", "index");
        return $this->h->optionList($filesListArray, $varName, $default);
    }

    function xloadThisPageEditor2Head($page, $iMgr = "pages") {
        $editor = strtolower($this->m->defaultEditor);
        $editorFunction = 'mosecms_editor_' . strtolower($editor); //get the global editor
        $editor4Page = $this->getPageEditor($page, $iMgr);
        /*
          $pagesInfo = $this->h->loadInfo($this->m->pagesDir);
          $editor4Page=$pagesInfo[$page]['editor'];
         */
//		$this->h->debugO(array('name'=>$this->m->hooks,'msg'=>"run editor=>",'debug'=>$this->h->debug));
        if ($editor4Page <> $editor && $editor4Page <> "plain" && $editor4Page) {
            if ($editor == "tinymce")
                print "<head>" . $this->h->wrapjs("tinyMCE=null;") . "</head>"; // unload tinymce
            $editorFunction = 'mosecms_editor_' . strtolower($editor4Page);
            $this->m->defaultEditor = $editor4Page; // override the global editor
            if (!function_exists($editorFunction)) {
                $addonsdir = $this->m->addOnsDir;
//				$filename = $addonsdir."/".$this->m->editorsDir."/".$editor4Page.".php";
                $filename = $this->m->editorsDir . $editor4Page . ".php";
                print "<head>" . $this->h->getIncludeContent($filename) . "</head>";
                $this->m->onEditor("onHead");
//				$this->m->onHead("onHead");        
//				$this->m->onHead();	// load edit via onHead onEvent
            }
        }
        return $editorFunction;
    }

}

// end class
?>