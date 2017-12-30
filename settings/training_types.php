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

$Training = new Training();
$typesList = $Training->getTypesList();
$categoriesList = $Training->getCategoriesList();

if ($_GET['action'] == "delete") {
    $typeId = $_GET['id'];
    $Training->deleteTypeById((integer)$typeId);
    header("location:training_types.php");
}

page_header(null, "Training Types Settings");

?>
    <a href="edit_training_type.php?action=add">Add type</a>
<table class="table">
    <?
    foreach ($typesList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><?=$categoriesList[$item['category_id']]['name'];?></td>
            <td><a href="edit_training_type.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_training_type" href="training_types.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>