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

$User = new User();
$userTypesList = $User->getTypesList();

if ($_GET['action'] == "delete") {
    $userId = $_GET['id'];
    if ($userId != 1) {
        $User->deleteById((integer)$userId);
        header("location:users.php");
    }
}

$usersList = $User->getList(null, 'report');

page_header(null, "User Settings");

?>
    <a href="edit_user.php?action=add">Add user</a>
<table class="table">
    <?
    foreach ($usersList as $user) {
        ?>
        <tr>
            <td><?=$user['login']?></td>
            <td><?=$user['name']?>&nbsp;<?=$user['surname'];?></td>
            <td><?=$userTypesList[$user['user_type']]['user_type_name'];?></td>
            <td><a href="edit_user.php?id=<?=$user['user_id'];?>">Edit</a></td>
            <td>
                <?
                if ($user['user_id'] != 1) {
                    ?>
                    <a class="delete_user" href="users.php?action=delete&id=<?=$user['user_id'];?>">Delete</a>
                <?
                }
                ?>
            </td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>