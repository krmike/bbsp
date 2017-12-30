<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 22:11
 */

include("../config.php");

chk_auth();

$Access = new Access();

//$Access->checkPageAccess($_COOKIE['user_type'], 1);

if ($_POST[save]) {
    $permissions = $_POST[permission];

    $Access->savePermissions($permissions);
}

$User = new User();
$userTypes = $User->getTypesList();
$resources = $Access->getResources();
$permissions = $Access->getPermissions();

page_header(null,"Permissions");

?>
<form method="post">
    <table class="table table-bordered">

        <tr>
            <td>&nbsp;</td>
            <?
            foreach ($userTypes as $userType) {
                ?><td><?=$userType['user_type_name'];?></td><?
            }
            ?>
        </tr>
        <?
        foreach ($resources as $resource) {
            ?>
            <tr>
                <td><?=$resource['resource_name'];?></td>
                <?
                foreach($userTypes as $userType) {
                    ?>
                    <td>
                        <input type="checkbox" name="permission[<?=$resource['resource_id'];?>][<?=$userType['user_type_id']?>]" value="<?=$userType['user_type_id']?>"
                               <?if ($permissions[$resource['resource_id']][$userType['user_type_id']]=="1") {
                                   ?>checked="checked" <?
                               }?>
                            >
                    </td>
                <?
                }
                ?>
            </tr>
        <?
        }
        ?>
    </table>
    <input class="submit" type="submit" value="Save" name="save">
</form>
<?

page_footer();

?>