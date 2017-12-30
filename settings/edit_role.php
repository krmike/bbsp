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

$Role = new Role();
$header = "Add Role";
if ($_GET['id']) {
    $header = "Edit Role";
    $role = $Role->getById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $roleId = $Role->add($newData);
        header("location: /settings/roles.php");
}

if ($_POST['edit']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Role->updateById($_GET['id'], $newData);
        header("location: /settings/roles.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$role['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Role">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>