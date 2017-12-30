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

$Lift = new Lift();
$liftList = $Lift->getlist();


if ($_GET['action'] == "delete") {
    $liftId = $_GET['id'];
    $Lift->deleteById((integer)$liftId);
    header("location:lifts.php");
}

page_header(null, "Lift Settings");

?>
    <a href="edit_lift.php?action=add">Add lift</a>
<table class="table">
    <?
    foreach ($liftList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td>
                <?
                if ($item['active'] == 1) {
                    ?>Active<?
                }
                ?>
            </td>
            <td><a href="edit_lift.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_lift" href="lifts.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>