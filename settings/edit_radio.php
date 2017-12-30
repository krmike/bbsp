<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 23:22
 */

include("../config.php");

chk_auth();

$Access = new Access();

$Access->checkPageAccess($_COOKIE['user_type'], 1);

$Radio = new Radio();
$header = "Add Radio";
if ($_GET['id']) {
    $header = "Edit Radio";
    $radio = $Radio->getById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $radioId = $Radio->add($newData);
        header("location: /settings/radio.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Radio->updateById($_GET['id'], $newData);
        header("location: /settings/radio.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$radio['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Radio">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>