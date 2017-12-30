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

$Training = new Training();
$categories = $Training->getCategoriesList();

$header = "Add training type";
if ($_GET['id']) {
    $header = "Edit training type";
    $type = $Training->getTypeById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name'],
            'category_id' => $_POST['category']
        );
        $typeId = $Training->addType($newData);
        header("location: /settings/training_types.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name'],
            'category_id' => $_POST['category']
        );
        $Training->updateTypeById($_GET['id'], $newData);
        header("location: /settings/training_types.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$type['name'];?>"> <br>
        Category: <select name="category">
            <?
            foreach ($categories as $category) {
                ?>
                <option value="<?=$category['id'];?>"
                    <?
                    if ($category['id'] == $type['category_id']) {
                        ?>
                        selected="selected"
                    <?
                    }
                    ?>
                    ><?=$category['name'];?></option>
            <?
            }
            ?>
        </select>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add training type">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>