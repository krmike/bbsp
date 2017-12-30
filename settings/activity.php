<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 23:12
 */

include("../config.php");

chk_auth();

$Access = new Access();

$Access->checkPageAccess($_COOKIE['user_type'], 1);

$Activity = new Activity();
$activityList = $Activity->getlist();

if ($_GET['action'] == "delete") {
    $activityId = $_GET['id'];
    $Activity->deleteById((integer)$activityId);
    header("location:activity.php");
}

page_header(null, "Activity Settings");

?>
    <a href="edit_activity.php?action=add">Add activity</a>
<table class="table">
    <?
    foreach ($activityList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <?
            if ($item['id'] != 1 && $item['id'] != 2) {
                ?>
                <td><a href="edit_activity.php?id=<?=$item['id'];?>">Edit</a></td>
                <td><a class="delete_activity" href="activity.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
            <?
            } else {
                ?>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <?
            }
            ?>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>