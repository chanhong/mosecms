<?php
/**
 * addons\apps\hello\index.php
 *
 * A sample addons "Hello World" to show case a simple application for MoseCMS
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
echo HelloForm();

function HelloForm() {

    $firstvar = "";  // first parameter from mosmodule
    $secvar = "";  // second parameter from mosmodule
    ?>
    <form action="?do=run&app=hello" method="post">
        <input type="hidden" name="app" value="hello">
        <input type="hidden" name="do" value="run">
        <input name="firstname" type="text" value="firstname" size="20">
        <input name="submit" type="submit" value="submit">
    </form>
    <p></p>
    <?php
    (isset($_REQUEST['firstname'])) ? $firstname = $_REQUEST['firstname'] : $firstname = "";
    echo "Your First Name is:" . $firstname;
    if ($secvar) {
        echo "<p>This is from the second parameter: <b>" . $secvar . "</b>";
    }
}
?>