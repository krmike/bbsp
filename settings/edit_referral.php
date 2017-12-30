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

$Referral = new Referral();
$header = "Add Destination";
if ($_GET['id']) {
    $header = "Edit Destination";
    $referral = $Referral->getById($_GET['id']);
}

if ($_POST['add']) {
        $newData = array(
            'name' => $_POST['name']
        );
        $referralId = $Referral->add($newData);
        header("location: /settings/referral.php");
}

if ($_POST['edit'] && $_GET['id'] != 1) {
        $newData = array(
            'name' => $_POST['name']
        );
        $Referral->updateById($_GET['id'], $newData);
        header("location: /settings/referral.php");
}

page_header(null, $header);

?>
    <form method="post">
        Name: <input type="text" name="name" value="<?=$referral['name'];?>"> <br>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input class="submit" type="submit" name="add" value="Add Destination">
        <?
        }
        ?>
    </form>
<?

page_footer();

?>