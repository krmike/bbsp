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

$Weather = new Weather();
$header = "Add Weather";
if ($_GET['id']) {
    $header = "Edit Weather";
    $weather = $Weather->getById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $weatherId = $Weather->add($newData);
        header("location: /settings/weather.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Weather->updateById($_GET['id'], $newData);
        header("location: /settings/weather.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$weather['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Weather">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>