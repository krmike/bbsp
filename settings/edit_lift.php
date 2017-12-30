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

$Lift = new Lift();
$header = "Add Lift";
if ($_GET['id']) {
    $header = "Edit Lift";
    $lift = $Lift->getById($_GET['id']);
}

if ($_POST['add']) {
    if ($_POST['active']) {
        $active = 1;
    } else {
        $active = 0;
    }
    $newData = array(
        'name'      => $_POST['name'],
        'active'    => $active
    );
    $liftId = $Lift->add($newData);
    header("location: /settings/lifts.php");
}

if ($_POST['edit']) {
    if ($_POST['active']) {
        $active = 1;
    } else {
        $active = 0;
    }
    $newData = array(
        'name' => $_POST['name'],
        'active'    => $active
    );
    $Lift->updateById($_GET['id'], $newData);
    header("location: /settings/lifts.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$lift['name'];?>"> <br>
        Active: <input type="checkbox" name="active"
            <? if ($lift['active'] == 1) { ?>
                checked="checked"
            <?}?>
            > <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Lift">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>