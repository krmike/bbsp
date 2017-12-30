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

$Role = new Role();
$roleList = $Role->getlist();

if ($_GET['action'] == "delete") {
    $roleId = $_GET['id'];
    $Role->deleteById((integer)$roleId);
    header("location:roles.php");
}

page_header(null, "Roles Settings");

?>
    <a href="edit_role.php?action=add">Add role</a>
<table class="table">
    <?
    foreach ($roleList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_role.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_role" href="roles.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>