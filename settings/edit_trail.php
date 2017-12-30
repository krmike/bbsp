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

$Trail = new Trail();
$Activity = new Activity();
$header = "Add Trail";
$activityList = $Activity->getList();
if ($_GET['id']) {
    $header = "Edit Trail";
    $trail = $Trail->getById($_GET['id']);
}

if ($_POST['add']) {
        if ($_POST['name'] != "") {
            $newData = array(
                'name' => $_POST['name'],
                'activity_id' => $_POST['activity']
            );
            $trailId = $Trail->add($newData);
            header("location: /settings/trails.php");
        }
}

if ($_POST['edit']) {
        if ($_POST['name'] != "") {
            $newData = array(
                'name' => $_POST['name'],
                'activity_id' => $_POST['activity']
            );
            $Trail->updateById($_GET['id'], $newData);
            header("location: /settings/trails.php");
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
                    <input type="text" name="name" value="<?=$trail['name'];?>">
                </td>
            </tr>
            <tr>
                <td>
                    Activity:
                </td>
                <td>
                    <select name="activity">
                        <?
                        foreach ($activityList as $activity) {
                            ?>
                            <option value="<?=$activity['id'];?>"
                                <?
                                if ($activityList['id'] == $trail['activity_id']) {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                ><?=$activity['name'];?></option>
                        <?
                        }
                        ?>
                    </select>
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
            <input class="submit" type="submit" name="add" value="Add Trail">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>