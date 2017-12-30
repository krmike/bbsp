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

$Injury = new Injury();
$header = "Add injury location";
if ($_GET['id']) {
    $header = "Edit injury location";
    $location = $Injury->getLocationById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $locationId = $Injury->addLocation($newData);
        header("location: /settings/injury_location.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Injury->updateLocationById($_GET['id'], $newData);
        header("location: /settings/injury_location.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$location['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add injury location">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>