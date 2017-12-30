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

$Transport = new Transport();
$transportList = $Transport->getlist();

if ($_GET['action'] == "delete") {
    $transportId = $_GET['id'];
    $Transport->deleteById((integer)$transportId);
    header("location:transport.php");
}

page_header(null, "Transport Settings");

?>
    <a href="edit_transport.php?action=add">Add transport</a>
<table class="table">
    <?
    foreach ($transportList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_transport.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_transport" href="transport.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>