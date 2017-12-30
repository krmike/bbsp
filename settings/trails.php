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

$Trail = new Trail();
$Activity = new Activity();
$trailsList = $Trail->getlist();
$activityList = $Activity->getList();

if ($_GET['action'] == "delete") {
    $trailId = $_GET['id'];
    $Trail->deleteById((integer)$trailId);
    header("location:trails.php");
}

page_header(null, "Trail Settings");

?>
    <a href="edit_trail.php?action=add">Add trail</a>
<table class="table">
    <?
    foreach ($trailsList as $activityTrail) {
        foreach ($activityTrail as $item){
            ?>
            <tr>
                <td><?=$item['name']?></td>
                <td><?=$activityList[$item['activity_id']]['name'];?></td>
                <td><a href="edit_trail.php?id=<?=$item['id'];?>">Edit</a></td>
                <td><a class="delete_trail" href="trails.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
            </tr>
        <?
        }
    }
    ?>
</table>
<?

page_footer();

?>