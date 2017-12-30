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

$Dr = new Dr();
$drList = $Dr->getlist();

if ($_GET['action'] == "delete") {
    $drId = $_GET['id'];
    $Dr->deleteById((integer)$drId);
    header("location:dr.php");
}

page_header(null, "MtBB Dr Settings");

?>
    <a href="edit_dr.php?action=add">Add Dr</a>
<table class="table">
    <?
    foreach ($drList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_dr.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_dr" href="dr.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>