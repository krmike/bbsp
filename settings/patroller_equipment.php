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

$Equip = new Equipment();
$equipList = $Equip->getPEList();

if ($_GET['action'] == "delete") {
    $equipId = $_GET['id'];
    $Equip->deletePEById((integer)$equipId);
    header("location:patroller_equipment.php");
}

page_header(null, "Patroller Equipment Settings");

?>
    <a href="edit_p_equipment.php?action=add">Add patroller equipment</a>
<table class="table">
    <?
    foreach ($equipList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_p_equipment.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_equip" href="patroller_equipment.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>