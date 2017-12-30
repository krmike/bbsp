<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 22:31
 */

include("../config.php");

$resourceId = 7; // Equipment Management

$Access = new Access();
$DB = new Db();
$Equip = new Equipment();
$Ambulance = new Ambulance();

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);

$equipList = $Equip->getList();
$referralList = $Ambulance->getList();

if ($_POST['save']) {

    if ($_POST['return']) {
        $return = 1;
    } else {
        $return = 0;
    }

    $date = $_POST['date_y']."-".$_POST['date_m']."-".$_POST['date_d'];

    $managementData = array(
        "date"          => $date,
        "`return`"        => $return,
        "destination"   => $_POST['destination']
    );


    $Equip->updateManagementById($_GET['id'], $managementData);
    header("location:/reporting/equipment.php");
}
if ($_GET['id']) {
    $management = $Equip->getManagementById($_GET['id']);
}
page_header(5, "Equipment Management Report");

?>
<form method="post">
    <table class="table">
        <tr>
            <td class="right-cell">
                Date:
            </td>
            <td><?
                $date = explode("-", $management['date']);
                ?>
                <select id="date_d" class="hours" name="date_d">
                    <?
                    for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                        ?>
                        <option value="<?=sprintf("%02d", $i);?>"
                            <?
                            if ($date['2'] == sprintf("%02d", $i)) {
                                ?>
                                selected="selected"
                            <?
                            }
                            ?>
                            >
                            <?=sprintf("%02d", $i);?>
                        </option>
                    <?
                    }
                    ?>
                </select>-<select id="date_m" class="hours" name="date_m">
                    <?
                    for ($i = 1; $i <= 12; $i++) {
                        ?>
                        <option value="<?=sprintf("%02d", $i);?>"
                            <?
                            if ($date['1'] == sprintf("%02d", $i)) {
                                ?>
                                selected="selected"
                            <?
                            }
                            ?>
                            >
                            <?=sprintf("%02d", $i);?>
                        </option>
                    <?
                    }
                    ?>
                </select>-<select id="date_y" class="hours" name="date_y">
                    <?
                    for ($i = date("Y") - 90; $i <= date("Y") + 10; $i++) {
                        ?>
                        <option value="<?=sprintf("%02d", $i);?>"
                            <?
                            if ($date['0'] == sprintf("%02d", $i)) {
                                ?>
                                selected="selected"
                            <?
                            }
                            ?>
                            >
                            <?=sprintf("%02d", $i);?>
                        </option>
                    <?
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Equipment:
            </td>
            <td>
                <?=$equipList[$management['equipment_id']]['name'];?>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Destination:
            </td>
            <td>
                <select name="destination" >
                    <?
                    foreach ($referralList as $referral) {
                        ?>
                        <option value="<?=$referral['id']?>" <? if($referral['id'] == $management['destination']) {
                            ?> selected="selected"<?
                        }?>
                            >
                            <?=$referral['name'];?>
                        </option>
                    <?
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Returned:
            </td>
            <td>
                <input type="checkbox" name="return"
                    <?
                    if ($management['return'] == 1) {
                        ?>
                    checked="checked"
                    <?
                    }
                    ?>
                    >
            </td>
        </tr>
        <tr>
            <td class="right-cell">

            </td>
            <td>
                <input type="submit" class="submit" name="save" value="Save">
            </td>
        </tr>
    </table>
</form>
<?

page_footer();

?>