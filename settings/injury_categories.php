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

if ($_GET['action'] == "delete") {
    $categoryId = $_GET['id'];
    $Injury->deleteCategoryById((integer)$categoryId);
    header("location:injury_categories.php");
}

page_header(null, "Injury Categories Settings");

?>
    <a href="edit_category.php?action=add">Add injury category</a>
<table class="table">
    <?
    foreach ($categoriesList as $categoryId => $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_category.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_category" href="injury_categories.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>