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

$Injury = new Injury();
$locationList = $Injury->getLocationList();

if ($_GET['action'] == "delete") {
    $locationId = $_GET['id'];
    $Injury->deleteLocationById((integer)$locationId);
    header("location:injury_location.php");
}

page_header(null, "Injury location Settings");

?>
    <a href="edit_location.php?action=add">Add injury location</a>
<table class="table">
    <?
    foreach ($locationList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_location.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_location" href="injury_location.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>