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
$header = "Add injury type";
if ($_GET['id']) {
    $header = "Edit injury type";
    $type = $Injury->getTypeById($_GET['id']);
}

if ($_POST['add']) {
    if ($_POST['name'] != "") {
        $newData = array(
            'name' => $_POST['name'],
            'category_id' => $_POST['category']
        );
        $typeId = $Injury->addType($newData);
        header("location: /settings/injury_types.php");
    }
}

if ($_POST['edit']) {
    if ($_POST['name'] != "") {
        $newData = array(
            'name' => $_POST['name'],
            'category_id' => $_POST['category']
        );
        $Injury->updateTypeById($_GET['id'], $newData);
        header("location: /settings/injury_types.php");
    }
}

page_header(null, $header);

?>
    <form method="post">
        <table class="table">
            <tr>
                <td>
                    Name:
                </td>
                <td>
                    <input type="text" name="name" value="<?=$type['name'];?>">
                </td>
            </tr>
            <tr>
                <td>
                    Category:
                </td>
                <td>
                    <select name="category">
                        <?
                        foreach ($categoriesList as $category) {
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
                </td>
            </tr>
        </table>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add injury type">
        <?
        }
        ?>
    </form>
<?

page_footer();
?>