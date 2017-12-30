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

$Run = new Run();
$Lift = new Lift();
$header = "Add Run";
$liftList = $Lift->getList();
if ($_GET['id']) {
    $header = "Edit Run";
    $run = $Run->getById($_GET['id']);
}

if ($_POST['add']) {
    if ($_POST['active']) {
        $active = 1;
    } else {
        $active = 0;
    }
    if ($_POST['name'] != "") {
        $newData = array(
            'name'      => $_POST['name'],
            'lift_id'   => $_POST['lift'],
            'active'    => $active
        );
        $runId = $Run->add($newData);
        header("location: /settings/runs.php");
    }
}

if ($_POST['edit']) {
    if ($_POST['active']) {
        $active = 1;
    } else {
        $active = 0;
    }
    if ($_POST['name'] != "") {
        $newData = array(
            'name'      => $_POST['name'],
            'lift_id'   => $_POST['lift'],
            'active'    => $active
        );
        $Run->updateById($_GET['id'], $newData);
        header("location: /settings/runs.php");
    }
}

page_header(null, $header);

?>
    <form method="post">
        <table class="table">
            <tr>
                <td>
                    Name:
                </td>
                <td>
                    <input type="text" name="name" value="<?=$run['name'];?>">
                </td>
            </tr>
            <tr>
                <td>
                    Lift:
                </td>
                <td>
                    <select name="lift">
                        <?
                        foreach ($liftList as $lift) {
                            ?>
                            <option value="<?=$lift['id'];?>"
                                <?
                                if ($lift['id'] == $run['lift_id']) {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                ><?=$lift['name'];?></option>
                        <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Active:</td>
                <td>
                    <input type="checkbox" name="active"
                        <? if ($run['active'] == 1) { ?>
                            checked="checked"
                        <?}?>
                        >
                </td>
            </tr>
        </table>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Run">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>