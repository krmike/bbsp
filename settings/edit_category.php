<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 23:22
 */

include("../config.php");

chk_auth();

$Access = new Access();

$Access->checkPageAccess($_COOKIE['user_type'], 1);

$Injury = new Injury();
$header = "Add injury category";
if ($_GET['id']) {
    $header = "Edit injury category";
    $category = $Injury->getCategoryById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $categoryId = $Injury->addCategory($newData);
        header("location: /settings/injury_categories.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Injury->updateCategoryById($_GET['id'], $newData);
        header("location: /settings/injury_categories.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$category['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add injury category">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>