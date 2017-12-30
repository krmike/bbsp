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
$categoriesList = $Injury->getCategoriesList();
$typesList = $Injury->getTypesList();

if ($_GET['action'] == "delete") {
    $typeId = $_GET['id'];
    $Injury->deleteTypeById((integer)$typeId);
    header("location:injury_types.php");
}

page_header(null, "Injury Types Settings");

?>
    <a href="edit_injury_type.php?action=add">Add injury type</a>
<table class="table">
    <?
    foreach ($typesList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><?=$categoriesList[$item['category_id']]['name'];?></td>
            <td><a href="edit_injury_type.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_injury_type" href="injury_types.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>