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

$Snow = new Snow();
$snowList = $Snow->getlist();

if ($_GET['action'] == "delete") {
    $snowId = $_GET['id'];
    $Snow->deleteById((integer)$snowId);
    header("location:snow.php");
}

page_header(null, "Snow Settings");

?>
    <a href="edit_snow.php?action=add">Add snow</a>
<table class="table">
    <?
    foreach ($snowList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_snow.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_snow" href="snow.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>