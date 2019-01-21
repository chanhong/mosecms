<?php

/**
 * class.admin.php
 *
 * MoseCMS main admin class act as a dispatcher or controller and provide a set of administrative API for add-ons
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
//include_once("class.admin.base.php");

class MosecmsAdminClass extends MosecmsAdminBaseClass {

    function __construct() {
//        parent::__construct();
        global $mosecms;
        $this->m = $mosecms;
        if (!isset($mosecmsHelper))
            $mosecmsHelper = new MosecmsHelperClass();
        $this->h = $mosecmsHelper;
    }

    function main() {
        switch ($this->h->action()) {
            case "run":
                echo $this->m->appLoad();
                break;
            case "logout" :
                $this->h->logout();
                break;
            case "view":
                $this->pageView("pages");
                break;
            case "cpanel" :
            case "admin" :
                $this->managers();
                break;
            default:
                echo "unknown option from main";
        }
    }

    function managers() {
        $mgr = strtolower($this->h->getRequestVal("mgr"));
        switch ($mgr) {
            case "config":
                $this->panelMgr($mgr);
                break;
            case "mods":
                $this->panelMgr($mgr);
                break;
            case "apps":
                $this->panelMgr($mgr);
                break;
            case "macros":
                $this->panelMgr($mgr);
                break;
            case "info":
                $this->infoMgr();
                break;
            case "cmods":
                $this->cmodsMgr();
                break;
            case "positions":
                $this->positionsMgr();
                break;
            case "editors":
                $this->editorsMgr();
                break;
            default:
            case "pages":
                $this->pagesMgr();
        }
    }

    function panelMgr($app) {
        $app = strtolower($app);
        switch ($app) {
            case "macros":
                $header = "";
                $detail = $this->macrosDetail();
                break;
            case "apps":
                $header = "";
                $detail = $this->appsDetail();
                break;
            case "editors":
                $header = "";
                $detail = $this->editorsDetail();
                break;
            case "mods":
                $header = "";
                $detail = $this->modsDetail();
                break;
            case "config":
                $header = "";
                $detail = $this->configDetail();
                break;
            case "cmods":
                $header = $this->pageNewButton($app);
                $detail = $this->jsRename($app) . $this->cmodsDetail();
                break;
            case "positions":
                $header = $this->pageNewButton($app);
                $detail = $this->jsRename($app) . $this->positionsDetail();
                break;
            default:
            case "pages":
                $header = $this->pageNewButton($app) . $this->pageUploadButton($app);
                $detail = $this->jsRename($app) . $this->pagesDetail();
                break;
        }
        $href = $this->h->href(array('do' => 'admin', 'mgr' => 'info', 'task' => 'infosave', 'app' => $app));
        $body = "\n<table>" . $detail . "</table>";
        if ($app <> "positions" and $app <> "templates"
//			and $app<>"template" and $app<>"css"
        ) {
            $body .= "<br /><div>" . $this->h->submit(array('value' => "Save " . ucfirst($app) . " Setting")) . "</div>";
        }
        print "<fieldset>"
                . $this->h->tag("legend", ucfirst($app) . " Panel")
                . $header . $this->h->form($href, $body)
                . "</fieldset>";
    }

    function infoMgr() {
        $task = strtolower($this->h->getRequestVal("task"));
        $app = strtolower($this->h->getRequestVal("app"));
        switch ($task) {
            case "show":
            case "hide":
                echo $this->showHide($task);
                break;
            case "paramssave":
                $this->paramsSave($app);
                break;
            case "paramsedit":
                $this->paramsEdit($app);
                break;
            case "infosave":
                switch ($app) {
                    case "config":
                        $this->configSave();
                        break;
                    default: // all others
                        $this->infoSave();
                }
                break;
            default:
        }
    }

    function pagesMgr() {
        $task = strtolower($this->h->getRequestVal("task"));
        switch ($task) {
            case "metainfo":
                echo $this->pageMetaInfo("pages");
                break;
            case "preview":
                echo $this->m->pageLoad("pages");
                break;
            case "view":
                $this->pageView("pages");
                break;
            case "edit" :
                $this->pageEdit("pages");
                break;
            case "new" :
                $this->pageNew("pages");
                break;
            case "del" :
                $this->pageDelete("pages");
                break;
            case "rename" :
                $this->pageRename("pages");
                break;
            case "save" :
                $this->pageSave("pages");
                break;
            case "upload" :
                $this->pageUpload($this->m->pagesDir, "pages");
                break;
            case "list" :
            default:
                $this->panelMgr("pages");
        }
    }

    function paramsSave($iMgr) {
        $pname = strtolower($this->h->getRequestVal("pname"));
        $aDir = $this->getMgrDir($iMgr);
        $info = $this->h->loadInfo($aDir . $pname . "/info.php"); // from dir
        $params = $this->h->loadInfo($aDir . $pname . "/params.php"); // from dir
        foreach ($params as $param) {
            $info[$param['name']] = $this->h->getInfoChange($param, $param['name']);
        }
        $infoFile = $this->getMgrDir($iMgr) . $pname . "/info.php";
//		pr($infoFile,"infofile:");
        echo $this->writeInfo($info, $infoFile);
    }

    function array2String($var) {
        $code = "";
        if (is_array($var)) {
            foreach ($var as $key => $value) {
                $nValue = $this->array2String($value);
                $code .= "\n<label>" . ucfirst($key) . "</label>: " . $nValue . '<br />';
            }
            return "<br />" . $code;
        } else {
            if (is_string($var)) {
                return $this->h->escapeSingleQuote($var);
            } elseif (is_numeric($var)) {
                return $var;
            } elseif (is_bool($code)) {
                return ($code ? 'TRUE' : 'FALSE');
            } else {
                return 'NULL';
            }
        }
    }

    function paramInfo($info) {
        $header = "<h2>Info:</h2>";
        if (!empty($info)) {
            $detail = "Name: " . $info['name'] . "<br />"
                    . "Version: " . $info['version'] . "<br />"
                    . "Author: " . $info['author'] . "<br />"
                    . "Description: " . $info['description'] . "<br />";
        } else
            $detail = "None";
        return $header . $detail;
    }

    function paramParams($info, $params) {
        $detail = "";
        $header = "<h2>Paramerters:</h2>";
        if (!empty($params)) {
            foreach ($params as $param) {
                $detail .= $this->h->tag('label', $param['label']) . ": ";
                (!empty($info[$param['name']])) ? $pvalue = $info[$param['name']] : $pvalue = $param['default'];

                $itype = strtolower($this->h->getVal($param, 'type'));
//				$vParmName = "params[".$param['name']."]";  // this won't work!
                $vParmName = $param['name'];
                switch ($itype) {
                    case 'text':
                        $detail .= $this->h->input(array('name' => $vParmName, 'value' => $pvalue));
                        break;
                    case 'radio':
                        $detail .= $this->h->inputRadio($param['value'], $vParmName, $pvalue);
                        break;
                    case 'list':
                        $detail .= $this->h->optionList($param['value'], $vParmName, $pvalue);
                        break;
                    default:
                    case 'spacer':
                        $detail .= $pvalue;
                        break;
                }
                $detail .= "<br />";
            }
        } else
            $detail = "None";
        return $header . $detail;
    }

    function paramsDetail($iMgr, $pname) {
        $aDir = $this->getMgrDir($iMgr);
        $info = $this->h->loadInfo($aDir . $pname . "/info.php"); // from dir
        $params = $this->h->loadInfo($aDir . $pname . "/params.php"); // from dir
        $aInfo = $this->paramInfo($info);
        $aParams = $this->paramParams($info, $params);
        return $aInfo . $aParams;
    }

    function paramsEdit($iMgr) {
        $pname = strtolower($this->h->getRequestVal("pname"));
        $detail = $this->paramsDetail($iMgr, $pname);
        $metaData = "";
        /*
          $aDir = $this->getMgrDir($iMgr);
          pr($aDir);
          $info = $this->h->loadInfo($aDir."info.php"); // from dir
          pr($info,"info:");
          pr($pname);
          $stat = $info[$pname['statusto']];
          $hrefStat = $this->h->href(array('do'=>'admin','mgr'=>'info','task'=>$stat, 'pname'=>$fName,'app'=>$app));
          $metaData = ""
          ."<td>&nbsp;".$this->h->a($hrefStat, ucfirst($stat))."</td>"
          ."<td>&nbsp;".$this->getPositionList("position".$info[$pname['id']],$info[$pname['position']])."</td>";
         */
        $href = $this->h->href(array('do' => 'admin', 'mgr' => 'info', 'task' => 'paramssave', 'app' => $iMgr, 'pname' => $pname));
        $body = "\n<table>"
                . $metaData
                . $detail . "</table>"
                . "<br /><div>" . $this->h->submit(array('value' => "Save " . ucfirst($iMgr) . " Setting")) . "</div>";
        print "<fieldset>"
                . $this->h->tag("legend", ucfirst($iMgr) . ": " . ucfirst($pname) . " Setting")
                . $this->h->form($href, $body)
                . "</fieldset>";
    }

    function editorsMgr() {
        $task = strtolower($this->h->getRequestVal("task"));
        switch ($task) {
            case "list":
            default:
                $this->panelMgr("editors");
        }
    }

    function positionsMgr() {
        $task = strtolower($this->h->getRequestVal("task"));
        switch ($task) {
            case "del" :
                $this->pageDelete("positions");
                break;
            case "new" :
                $this->pageNew("positions");
                break;
            case "rename" :
                $this->pageRename("positions");
                break;
            case "list":
            default:
                $this->panelMgr("positions");
        }
    }

    function cmodsMgr() {
        $task = strtolower($this->h->getRequestVal("task"));
        switch ($task) {
            case "preview":
                echo $this->m->pageLoad("cmods");
                break;
            case "view":
                $this->pageView("cmods");
                break;
            case "new" :
                $this->pageNew("cmods");
                break;
            case "del" :
                $this->pageDelete("cmods");
                break;
            case "upload" :
                $this->pageUpload($this->m->cmodsDir, "cmods");
                break;
            case "edit" :
                $this->pageEdit("cmods");
                break;
            case "save" :
                $this->pageSave("cmods");
                break;
            case "rename" :
                $this->pageRename("cmods");
                break;
            case "list":
            default:
                $this->panelMgr("cmods");
        }
    }

    function configDetail() {
        $config = &$this->m->config;
        foreach ($this->m->configDefault as $key => $value) {
            if (!isset($config[$key]))
                $config[$key] = $value;
        }
        return "<tr><td>Template</td><td>" . $this->getTemplatesName('template') . "</td></tr>
<tr><td>Skin</td><td>" . $this->getSkinsName($config['template'], 'skin') . "</td></tr>		
<tr><td>Admin Template</td><td>" . $this->getTemplatesName('admintemplate') . "</td></tr>		
<tr><td>Admin Skin</td><td>" . $this->getSkinsName($config['admintemplate'], 'adminskin') . "</td></tr>		
<tr><td>Editor</td><td>" . $this->getEditorList() . "</td></tr>
<tr><td>Homepage</td><td>" . $this->h->input(array('value' => $config['homepage'], 'name' => "homepage")) . "</td></tr>
<tr><td>Custom 404 page</td><td>" . $this->h->input(array('value' => $config['custom404'], 'name' => "custom404")) . "</td></tr>
<tr><td>Password</td><td>" . $this->h->input(array('value' => "New Admin Password", 'name' => "save")) . "</td></tr>
<tr><td>History Folder</td><td>" . $this->h->input(array('value' => $config['history'], 'name' => "history")) . "</td></tr>
<tr><td>Core Folder</td><td>" . $this->h->input(array('value' => $this->h->stripDriveLetter($config['coredir']), 'name' => "coredir", 'size' => '50')) . "</td></tr>
<tr><td>Packages Folder</td><td>" . $this->h->input(array('value' => $this->h->stripDriveLetter($config['packages']), 'name' => "packages", 'size' => '50')) . "</td></tr>
<tr><td>Site Private Folder</td><td>" . $this->h->input(array('value' => $this->h->stripDriveLetter($config['siteprivate']), 'name' => "siteprivate", 'size' => '50')) . "</td></tr>
<tr><td>Site Public Folder</td><td>" . $this->h->input(array('value' => $this->h->stripDriveLetter($config['sitepublic']), 'name' => "sitepublic", 'size' => '50')) . "</td></tr>
<tr><td>Meta Desc</td><td>" . $this->h->input(array('value' => $config['metadesc'], 'name' => "metadesc", 'size' => '78')) . "</td></tr>
<tr><td>Meta Key</td><td>" . $this->h->input(array('value' => $config['metakey'], 'name' => "metakey", 'size' => '78')) . "</td></tr>
<br />
<tr><td align='center'>Optional</td></tr>
<tr><td>DB Type</td><td>" . $this->h->input(array('value' => $config['dbtype'], 'name' => "dbtype", 'size' => '50')) . "</td></tr>
<tr><td>DB Host</td><td>" . $this->h->input(array('value' => $config['dbhost'], 'name' => "dbhost", 'size' => '50')) . "</td></tr>
<tr><td>DB Login</td><td>" . $this->h->input(array('value' => $config['dblogin'], 'name' => "dblogin", 'size' => '50')) . "</td></tr>
<tr><td>DB Password</td><td>" . $this->h->input(array('value' => $config['dbpw'], 'name' => "dbpw", 'size' => '50')) . "</td></tr>
<tr><td>DB Name</td><td>" . $this->h->input(array('value' => $config['dbname'], 'name' => "dbname", 'size' => '50')) . "</td></tr>
";
    }

    function macrosDetail() {
        $app = "macros";
        $info = $this->m->getMergedInfo($app);
        $details = "";
        foreach ($info as $page => $value) {
            $id = $value['id'];
            $ordering = $value['ordering'];
            $fName = $this->h->getVal($value, 'name');
            $filespec = $this->m->cmodsDir . $fName;
//			$ext = $this->h->fileExtPart($filespec);
            $page = $this->h->fileNamePart($fName, "file"); // get File Name
            $stat = $value['statusto'];
            $hrefView = $this->h->href(array('do' => 'admin', 'task' => 'paramsedit', 'mgr' => 'info', 'pname' => $page, 'app' => $app));
            $hrefStat = $this->h->href(array('do' => 'admin', 'mgr' => 'info', 'task' => $stat, 'pname' => $page, 'app' => $app));
            $details .= "<tr>"
                    . "<td>" . $id . "</td>"
                    . "<td>&nbsp;<input id='ordering" . $id . "' style='width:33px' type='text' name='ordering" . $id . "' value='" . $ordering . "' /></td>"
                    . "<td>&nbsp;" . $this->h->a($hrefView, $fName) . "</td>"
                    . "<td>&nbsp;" . $this->h->a($hrefStat, ucfirst($stat)) . "</td>"
                    . "</tr>\n"
            ;
        }
        return $details;
    }

    function positionsDetail() {
        $app = "positions";
        $info = $this->m->getMergedInfo($app);
        if (empty($info))
            return;
        $details = "";
        foreach ($info as $page => $value) {
            $id = $value['id'];
            $fName = $this->h->getVal($value, 'name');
            $page = $this->h->fileNamePart($fName, "file"); // get File Name
            $filespec = $this->m->positionsDir . $fName;
            $ext = $this->h->fileExtPart($filespec);
            $hrefDelete = $this->h->href(array('do' => 'admin', 'mgr' => $app, 'task' => 'del', 'pname' => $page));
            $details .= "<tr>"
                    . "<td>" . $id . "</td>"
                    . "<td>&nbsp;" . $page . "</td>"
                    . "<td>&nbsp;" . $this->h->a($hrefDelete, "Delete") . "</td>"
                    . "<td>&nbsp;" . $this->h->a($this->h->jsOnclick("moserenamefile('$page')"), "Rename") . "</td>"
                    . "</tr>\n"
            ;
        }
        return $details;
    }

    function appsDetail() {
        $app = "apps";
        $info = $this->m->getMergedInfo($app);
        $details = "";
        foreach ($info as $page => $value) {
            $id = $value['id'];
            $ordering = $value['ordering'];
            $fName = $this->h->getVal($value, 'name');
            $stat = $value['statusto'];
            $hrefView = $this->h->href(array('do' => 'admin', 'task' => 'paramsedit', 'mgr' => 'info', 'pname' => $page, 'app' => $app));
            $hrefStat = $this->h->href(array('do' => 'admin', 'mgr' => 'info', 'task' => $stat, 'pname' => $fName, 'app' => $app));
            $details .= "<tr>"
                    . "<td>" . $id . "</td>"
                    . "\n<td>&nbsp;<input id='ordering" . $id . "' style='width:33px' type='text' name='ordering" . $id . "' value='" . $ordering . "' /></td>"
                    . "<td>&nbsp;" . $this->h->a($hrefView, $fName) . "</td>"
                    . "<td>&nbsp;" . $this->h->a($hrefStat, ucfirst($stat)) . "</td>"
                    . "</tr>\n"
            ;
        }
        return $details;
    }

    function editorsDetail() {
        $app = "editors";
        $info = $this->m->getMergedInfo($app);
        $details = "";
        foreach ($info as $page => $value) {
            $id = $value['id'];
            $ordering = $value['ordering'];
            $fName = $this->h->getVal($value, 'name');
            $fullPath = $this->m->jsDir . $fName;
            if (file_exists($fullPath)) { // show only folder exists
                $stat = $value['statusto'];
                $hrefView = $this->h->href(array('do' => 'admin', 'task' => 'paramsedit', 'mgr' => 'info', 'pname' => $page, 'app' => $app));
                $hrefStat = $this->h->href(array('do' => 'admin', 'mgr' => 'info', 'task' => $stat, 'pname' => $fName, 'app' => $app));
                $details .= "<tr>"
                        . "<td>" . $id . "</td>"
                        . "\n<td>&nbsp;<input id='ordering" . $id . "' style='width:33px' type='text' name='ordering" . $id . "' value='" . $ordering . "' /></td>"
                        . "<td>&nbsp;" . $this->h->a($hrefView, $fName) . "</td>"
                        . "<td>&nbsp;" . $this->h->a($hrefStat, ucfirst($stat)) . "</td>"
                        . "</tr>\n"
                ;
            }
        }
        return $details;
    }

    function modsDetail() {
        $app = "mods";
        $info = $this->m->getMergedInfo($app);
        $details = "";
//pr($info,"info:");
        foreach ($info as $page => $value) {
            $id = $value['id'];
            $ordering = $value['ordering'];
            $position = $this->h->getVal($value, 'position');
            $fName = $this->h->getVal($value, 'name');
            $stat = $value['statusto'];
            $hrefView = $this->h->href(array('do' => 'admin', 'task' => 'paramsedit', 'mgr' => 'info', 'pname' => $page, 'app' => $app));
            $hrefStat = $this->h->href(array('do' => 'admin', 'mgr' => 'info', 'task' => $stat, 'pname' => $fName, 'app' => $app));
            $details .= "<tr>"
                    . "<td>" . $id . "</td>"
                    . "\n<td>&nbsp;<input id='ordering" . $id . "' style='width:33px' type='text' name='ordering" . $id . "' value='" . $ordering . "' /></td>"
                    . "<td>&nbsp;" . $this->h->a($hrefView, $fName) . "</td>"
                    . "<td>&nbsp;" . $this->h->a($hrefStat, ucfirst($stat)) . "</td>"
                    . "<td>&nbsp;" . $this->getPositionList("position" . $id, $position) . "</td>"
                    . "</tr>\n"
            ;
        }
        return $details;
    }

    function pagesDetail() {
        $app = "pages";
        $info = $this->m->getMergedInfo($app);
        if (empty($info))
            return;
        $details = "";
        foreach ($info as $page => $value) {
            $id = $value['id'];
            $ordering = $value['ordering'];

            $editor = $this->h->getVal($value, 'editor');
            $publish = $this->h->getVal($value, 'publish', date("Y/m/d"));
            $fName = $this->h->getVal($value, 'name');
            $page = $this->h->fileNamePart($fName, "file"); // get File Name
            $title = $this->h->getVal($value, 'title', $page);
            $ext = $this->h->fileNamePart($fName, "ext"); // get File Ext 
            $stat = $value['statusto'];
            $status = $value['statusto'];
            $hrefView = $this->h->href(array('do' => 'admin', 'task' => 'view', 'pname' => $page, 'mgr' => $app, 'ext' => $ext));
            $hrefStat = $this->h->href(array('do' => 'admin', 'mgr' => 'info', 'task' => $stat, 'pname' => $page, 'app' => $app));
            $details .= "<tr>"
                    . "<td>" . $id . "</td>"
                    . "\n<td>&nbsp;" . $this->h->input(array('id' => 'ordering' . $id, 'name' => 'ordering' . $id, 'value' => $ordering, 'style' => 'width:33px')) . "</td>"
//      ."\n<td>&nbsp;".$title."</td>"
                    . "\n<td>&nbsp;" . $this->h->a($hrefView, $fName) . "</td>"
//      ."\n<td>&nbsp;".$this->h->inputCheckbox(array('id'=>'status'.$id,'name'=>'status'.$id,'value'=>$status))."</td>"
                    . "\n<td>&nbsp;" . $this->h->a($hrefStat, ucfirst($stat)) . "</td>"
// 			."\n<td>&nbsp;<input id='publish".$id."' style='width:73px' type='text' name='publish".$id."' value='".$publish."' />&nbsp;</td>"
                    . "<td>&nbsp;" . $this->getEditorList("editor" . $id, $editor) . "</td>"
                    . "<td>&nbsp;" . $this->pageInfo($fName) . "</td>"
                    . "</tr>\n"
            ;
        }
        return $details;
    }

    function cmodsDetail() {
        $app = "cmods";
        $info = $this->m->getMergedInfo($app);
        $details = "";
        foreach ($info as $page => $value) {
            $id = $value['id'];
            $ordering = $value['ordering'];
            $editor = $this->h->getVal($value, 'editor');
            $position = $this->h->getVal($value, 'position');
            $fName = $this->h->getVal($value, 'name');
            $filespec = $this->m->cmodsDir . $fName;
            $ext = $this->h->fileExtPart($filespec);
            $page = $this->h->fileNamePart($fName, "file"); // get File Name
            $stat = $value['statusto'];
            $hrefView = $this->h->href(array('do' => 'admin', 'task' => 'view', 'pname' => $page, 'mgr' => $app, 'ext' => $ext));
            $hrefStat = $this->h->href(array('do' => 'admin', 'mgr' => 'info', 'task' => $stat, 'pname' => $page, 'app' => $app));
            $details .= "<tr>"
                    . "<td>" . $id . "</td>"
                    . "<td>&nbsp;<input id='ordering" . $id . "' style='width:33px' type='text' name='ordering" . $id . "' value='" . $ordering . "' /></td>"
                    . "<td>&nbsp;" . $this->h->a($hrefView, $fName) . "</td>"
                    . "<td>&nbsp;" . $this->h->a($hrefStat, ucfirst($stat)) . "</td>"
                    . "<td>&nbsp;" . $this->getPositionList("position" . $id, $position) . "</td>"
                    . "<td>&nbsp;" . $this->getEditorList("editor" . $id, $editor) . "</td>"
                    . "<td>&nbsp;" . $this->pageInfo($fName, "cmods") . "</td>"
                    . "</tr>\n"
            ;
        }
        return $details;
    }

}

// end class
?>