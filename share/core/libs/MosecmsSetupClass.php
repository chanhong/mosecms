<?php
/**
 * class.setup.php
 *
 * MoseCMS setup class to setup a new site
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

class MosecmsSetupClass {

    var $setuppw;
    var $setupTemplate;

    function __construct() {
        global $mosecms;
        $this->setuppw = $mosecms->setupPw;
        $this->setupTemplate = $mosecms->templatesDir . "default/";
    }

    function setup() {
//	purple.innerHTML='"< ?=$this->box("purplebox",purpleset,"box")? >"'"><tr><td></td></tr></table>';
        ?>
        <html>
            <head>
                <script language="javascript">

                    addEvent(window, "load", init);

                    function init() {
                        form = document.getElementById("setup");
                        addEvent(form, "submit", correctSubmitHandler);
                    }

                    function correctSubmitHandler(e) {
                        if (!document.setup.password.value || document.setup.password.value == "<?= $this->setuppw ?>") {
                            alert("Please use or enter another password.");
                            if (e && e.preventDefault)
                                e.preventDefault();
                            return false;
                        }
                        if (document.setup.sitename.value == "") {
                            alert("Please enter your website name.");
                            if (e && e.preventDefault)
                                e.preventDefault();
                            return false;
                        }
                        if (!document.setup.homepage.value) {
                            alert("You forgot to place a Homepage name, We will be using 'home'..");
                            document.setup.homepage.value = "home";
                        }
                    }

                    function addEvent(obj, evType, fn) {
                        if (obj.addEventListener) {
                            obj.addEventListener(evType, fn, false);
                            return true;
                        } else if (obj.attachEvent) {
                            var r = obj.attachEvent("on" + evType, fn);
                            return r;
                        } else {
                            return false;
                        }
                    }

                    function setup() {
                        switch (document.setup.c.value) {
                            case "1":
                                purpleset = "1";
                                blueset = "2";
                                orangeset = "1";
                                redset = "1";
                                greenset = "1";
                                document.setup.c.value = "2";
                                break;
                            case "2":
                                purpleset = "1";
                                blueset = "1";
                                orangeset = "2";
                                redset = "1";
                                greenset = "1";
                                document.setup.c.value = "3";
                                break;
                            case "3":
                                purpleset = "1";
                                blueset = "1";
                                orangeset = "1";
                                redset = "2";
                                greenset = "1";
                                document.setup.c.value = "4";
                                break;
                            case "4":
                                purpleset = "2";
                                blueset = "1";
                                orangeset = "1";
                                redset = "1";
                                greenset = "2";
                                document.setup.c.value = "5";
                                break;
                            case "5":
                                purpleset = "1";
                                blueset = "1";
                                orangeset = "1";
                                redset = "1";
                                greenset = "2";
                                document.setup.c.value = "1";
                                break;
                        }
                        purple.innerHTML = '<?= $this->box('purple') ?>';
                        red.innerHTML = '<?= $this->box('red') ?>';
                        blue.innerHTML = '<?= $this->box('blue') ?>';
                        orange.innerHTML = '<?= $this->box('orange') ?>';
                        green.innerHTML = '<?= $this->box('green') ?>';
                        t = setTimeout("setup()", 1000)
                    }
                </script>
                <style type="text/css" media="all">@import "<?= $this->setupTemplate ?>css/setup.css";</style>
            </head>
            <body onload="setup()">
                <p>&nbsp;</p><p>&nbsp;</p>
                <p align="center"><img src="<?= $this->setupTemplate ?>images/logo.png" /><br/><a><b>Setting up your website...</b></a></p>
                <div align="center">
                    <div style="border:1px solid black;width:400px;height:165px;">
                        <form action="index.php" name="setup" id="setup" method="post">
                            <table>
                                <tr><td colspan=2>&nbsp;</td></tr>
                                <tr><td>Your Homepage name?</td><td><input type="text" name="homepage" value="home" /></td></tr>
                                <tr><td>Name of this website?</td><td><input type="text" name="sitename" value="" /></td></tr>
                                <tr><td>What will be your password?</td><td><input type="password" name="password" value="" /></td></tr>
                                <tr><td colspan="2">&nbsp;</td></tr>
                                <tr>
                                    <td colspan="2" align="right">
                                        <input type="hidden" name="setup" value="setup" />
                                        <input type="hidden" name="c" value="1" />
                                        <input type="submit" class="submit" value="Go!" />
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <p align="center">
                <table>
                    <tr>
                        <td><div id="purple" style="background:white;">&nbsp;</div></td>
                        <td><div id="blue" style="background:white;">&nbsp;</div></td>
                        <td><div id="orange" style="background:white;">&nbsp;</div></td>
                        <td><div id="red" style="background:white;">&nbsp;</div></td>
                        <td><div id="green" style="background:white;">&nbsp;</div></td>
                    </tr>
                </table>
            </p>
        </body>
        </html>	
        <?php
    }

    function box($color) {
        (date("s") % 2) ? $class = "box2" : $class = "box1";
        return "<table class=\"" . $class . "\" id=\"" . $color . "box'+" . $color . "set+'\"><tr><td></td></tr></table>";
    }

    function setupCompleted() {
        ?>
        <html>
            <head><style type="text/css" media="all">@import "<?= $this->setupTemplate ?>css/setup.css";</style></head>
            <body>
                <p>&nbsp;</p><p>&nbsp;</p>
                <p align="center">
                    <img src="<?= $this->setupTemplate ?>images/logo.png" /><br/>
                    <a href="index.php"><b>Your website in now ready!</b></a><br /><br />
                    <input type="submit" class="submit" value="Home" onclick="javascript:window.location = 'index.php'" />
                </p>
            </body>
        </html>
        <?php
    }

}

// end class
?>