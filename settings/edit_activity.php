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

$Activity = new Activity();
$header = "Add Activity";
if ($_GET['id']) {
    $header = "Edit Activity";
    $activity = $Activity->getById($_GET['id']);
}

if ($_POST['add'] && $_GET['id'] != 1 && $_GET['id'] != 2) {
        $newData = array(
            'name' => $_POST['name']
        );
        $activityId = $Activity->add($newData);
        header("location: /settings/activity.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Activity->updateById($_GET['id'], $newData);
        header("location: /settings/activity.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$activity['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Activity">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>