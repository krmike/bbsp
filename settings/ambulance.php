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

$Ambulance = new Ambulance();
$destinationList = $Ambulance->getlist();

if ($_GET['action'] == "delete") {
    $destinationId = $_GET['id'];
    $Ambulance->deleteById((integer)$destinationId);
    header("location:ambulance.php");
}

page_header(null, "Ambulance Destination Settings");

?>
    <a href="edit_ambulance.php?action=add">Add ambulance destination</a>
<table class="table">
    <?
    foreach ($destinationList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_ambulance.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_destination" href="ambulance.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>