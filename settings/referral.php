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

$Referral = new Referral();
$referralList = $Referral->getlist();

if ($_GET['action'] == "delete") {
    $referralId = $_GET['id'];
    $Referral->deleteById((integer)$referralId);
    header("location:referral.php");
}

page_header(null, "Destination Settings");

?>
    <a href="edit_referral.php?action=add">Add Destination</a>
<table class="table">
    <?
    foreach ($referralList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <?
            if ($item['id'] != 1) {
                ?>
                <td><a href="edit_referral.php?id=<?=$item['id'];?>">Edit</a></td>
                <td><a class="delete_outcome" href="snow.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
            <?
            } else {
                ?>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            <?
            }
            ?>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>