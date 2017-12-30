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

$Radio = new Radio();
$radioList = $Radio->getlist();

if ($_GET['action'] == "delete") {
    $radioId = $_GET['id'];
    $Radio->deleteById((integer)$radioId);
    header("location:radio.php");
}

page_header(null, "Radio Settings");

?>
    <a href="edit_radio.php?action=add">Add radio</a>
<table class="table">
    <?
    foreach ($radioList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_radio.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_radio" href="radio.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>