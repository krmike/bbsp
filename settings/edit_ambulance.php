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

$Ambulance = new Ambulance();
$header = "Add Ambulance Destination";
if ($_GET['id']) {
    $header = "Edit Ambulance Destination";
    $destination = $Ambulance->getById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $destinationId = $Ambulance->add($newData);
        header("location: /settings/ambulance.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Ambulance->updateById($_GET['id'], $newData);
        header("location: /settings/ambulance.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$destination['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Ambulance Destination">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>