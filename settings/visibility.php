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

$Visibility = new Visibility();
$visibilityList = $Visibility->getlist();

if ($_GET['action'] == "delete") {
    $visibilityId = $_GET['id'];
    $Visibility->deleteById((integer)$visibilityId);
    header("location:visibility.php");
}

page_header(null, "Visibility Settings");

?>
    <a href="edit_visibility.php?action=add">Add visibility</a>
<table class="table">
    <?
    foreach ($visibilityList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_visibility.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_visibility" href="visibility.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>