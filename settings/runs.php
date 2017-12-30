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

$Run = new Run();
$Lift = new Lift();
$runList = $Run->getlist();
$liftList = $Lift->getList();

if ($_GET['action'] == "delete") {
    $runId = $_GET['id'];
    $Run->deleteById((integer)$runId);
    header("location:runs.php");
}

page_header(null, "Run Settings");

?>
    <a href="edit_run.php?action=add">Add run</a>
<table class="table">
    <?
    foreach ($runList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><?=$liftList[$item['lift_id']]['name'];?></td>
            <td>
                <?
                if ($item['active'] == 1) {
                    ?>Active<?
                }
                ?>
            </td>
            <td><a href="edit_run.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_run" href="runs.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>