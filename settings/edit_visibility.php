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

$Visibility = new Visibility();
$header = "Add Visibility";
if ($_GET['id']) {
    $header = "Edit Visibility";
    $visibility = $Visibility->getById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $visibilityId = $Visibility->add($newData);
        header("location: /settings/visibility.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Visibility->updateById($_GET['id'], $newData);
        header("location: /settings/visibility.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$visibility['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Visibility">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>