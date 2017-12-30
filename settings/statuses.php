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

$Status = new Status();
$statusList = $Status->getlist();

if ($_GET['action'] == "delete") {
    $statusId = $_GET['id'];
    $Status->deleteById((integer)$statusId);
    header("location:statuses.php");
}

page_header(null, "Status Settings");

?>
    <a href="edit_status.php?action=add">Add status</a>
<table class="table">
    <?
    foreach ($statusList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_status.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_status" href="statuses.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>