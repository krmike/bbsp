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
$categoriesList = $Training->getCategoriesList();

if ($_GET['action'] == "delete") {
    $categoryId = $_GET['id'];
    $Training->deleteCategoryById((integer)$categoryId);
    header("location:training_categories.php");
}

page_header(null, "Training Categories Settings");

?>
    <a href="edit_training_category.php?action=add">Add category</a>
<table class="table">
    <?
    foreach ($categoriesList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_training_category.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_training_category" href="training_categories.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>