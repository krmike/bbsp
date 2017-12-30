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

$header = "Add Equipment";
if ($_GET['id']) {
    $header = "Edit Equipment";
    $equip = $Equip->getById($_GET['id']);
}

if ($_POST['add']) {
    if ($_POST['consumable'] == "on") {
        $consumable = 1;
    } else {
        $consumable = 0;
    }
        $newData = array(
            'name' => $_POST['name'],
            'consumable' => $consumable
        );
        $equipId = $Equip->add($newData);
        header("location: /settings/equipment.php");
}

if ($_POST['edit']) {
    if ($_POST['consumable'] == "on") {
        $consumable = 1;
    } else {
        $consumable = 0;
    }
        $newData = array(
            'name' => $_POST['name'],
            'consumable' => $consumable
        );
        $Equip->updateById($_GET['id'], $newData);
        header("location: /settings/equipment.php");
}

page_header(null, $header);

?>
    <form method="post">
        <div class="form-div">
            Name: <input type="text" name="name" value="<?=$equip['name'];?>">
        </div>
        <div class="form-div">
            Consumable: <input type="checkbox" name="consumable"
                <?
                if ($equip['consumable'] == 1) {
                    ?>
                    checked="checked"
                <?
                }
                ?>
                >
        </div>
        <div class="form-div">
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
        </div>
    </form>
<?

page_footer();

?>