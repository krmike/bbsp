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

$Equip = new Equipment();
$header = "Add Patroller Equipment";
if ($_GET['id']) {
    $header = "Edit Patroller Equipment";
    $equip = $Equip->getPEById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $equipId = $Equip->addPE($newData);
        header("location: /settings/patroller_equipment.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Equip->updatePEById($_GET['id'], $newData);
        header("location: /settings/patroller_equipment.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$equip['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Equipment">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>