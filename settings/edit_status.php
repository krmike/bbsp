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

$Status = new Status();
$header = "Add Status";
if ($_GET['id']) {
    $header = "Edit Status";
    $status = $Status->getById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $statusId = $Status->add($newData);
        header("location: /settings/statuses.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Status->updateById($_GET['id'], $newData);
        header("location: /settings/statuses.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$status['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Status">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>