<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

$resourceId = 14; // Patrollers Training

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();

$usersList = $User->getList();
$userTypesList = $User->getTypesList();

page_header(5, "Patrollers Profile");
?>
<div class="table-responsive">

    <table class="table table-bordered">
        <?
        foreach ($usersList as $user) {
            ?>
            <tr>
                <td><?=$user['login']?></td>
                <td><?=$user['name']?>&nbsp;<?=$user['surname'];?></td>
                <td><?=$userTypesList[$user['user_type']]['user_type_name'];?></td>
                <td><a href="patroller.php?id=<?=$user['user_id'];?>">Details</a></td>
            </tr>
        <?
        }
        ?>
    </table>
</div>
<?

page_footer();

?>