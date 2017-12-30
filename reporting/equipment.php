<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

$resourceId = 15; // Equipment Management Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$Equip = new Equipment();
$Ambulance = new Ambulance();

$equipmentList = $Equip->getList();
$referralList = $Ambulance->getList();

$ynList = array(
    '1' => "Yes",
    '0' => "No"
);

$where = array();

$dateFrom = "01-".date("m-Y");
if ($_COOKIE['equip_date_from']) {
    $dateFrom = $_COOKIE['equip_date_from'];
}
if ($_POST['date_from_d']) {
    $dateFrom = $_POST['date_from_d']."-".$_POST['date_from_m']."-".$_POST['date_from_y'];
}
setcookie("equip_date_from", $dateFrom);

$dateTo = date("d-m-Y");
if ($_COOKIE['equip_date_to']) {
    $dateTo = $_COOKIE['equip_date_to'];
}
if ($_POST['date_to_d']) {
    $dateTo = $_POST['date_to_d']."-".$_POST['date_to_m']."-".$_POST['date_to_y'];
}
setcookie("equip_date_to", $dateTo);

if ($_POST['equipment'] != "all") {
    $equipmentId = $_POST['equipment'];
} else {
    $equipmentId = null;
}
setcookie("equip_equipment", $equipmentId);

if ($_POST['return'] == "yes") {
    $returnYn = 'yes';
    $returnR = '1';
}
if ($_POST['return'] == "no") {
    $returnYn = 'no';
    $returnR = '0';
}
if ($_POST['return'] == "all") {
    $returnYn = null;
    $returnR = null;
}
setcookie("equip_return", $returnR);

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$report = $Equip->getManagement($fromDate, $toDate, $equipmentId, $returnR);
page_header(5, "Equipment Management Report");
?>

<div>
    <form method="post">
        <div class="input-left">
            From:
            <select id="date_d" class="hours" name="date_from_d">
                <?
                for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateF['0'] == sprintf("%02d", $i)) {
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
            </select>-<select id="date_m" class="hours" name="date_from_m">
                <?
                for ($i = 1; $i <= 12; $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateF['1'] == sprintf("%02d", $i)) {
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
            </select>-<select id="date_y" class="hours" name="date_from_y">
                <?
                for ($i = date("Y") - 90; $i <= date("Y") + 10; $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateF['2'] == sprintf("%02d", $i)) {
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
        </div>
        <div class="input-left">
            To:
            <select id="date_to_d" class="hours" name="date_to_d">
                <?
                for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateT['0'] == sprintf("%02d", $i)) {
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
            </select>-<select id="date_to_m" class="hours" name="date_to_m">
                <?
                for ($i = 1; $i <= 12; $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateT['1'] == sprintf("%02d", $i)) {
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
            </select>-<select id="date_to_y" class="hours" name="date_to_y">
                <?
                for ($i = date("Y") - 90; $i <= date("Y") + 10; $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateT['2'] == sprintf("%02d", $i)) {
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
        </div>
        <div class="input-left">
            Equipment:&nbsp;<select name="equipment">
                <option value="all">All</option>
                <?
                foreach ($equipmentList as $equip) {
                    ?>
                    <option value="<?=$equip['id'];?>" <? if($equip['id'] == $equipmentId) {
                        ?> selected="selected"<?
                    }?>>
                        <?=$equip['name'];?>
                    </option>
                <?
                }
                ?>
            </select>
        </div>
        <div class="input-left">
            Returned:&nbsp;<select name="return">
                <option value="all">All</option>
                <option value="yes"
                    <?
                    if ($returnYn == "yes") {
                        ?>
                        selected="selected"
                    <?
                    }
                    ?>
                    >Yes</option>
                <option value="no"
                    <?
                    if ($returnYn == "no") {
                        ?>
                        selected="selected"
                    <?
                    }
                    ?>
                    >No</option>
            </select>
        </div>
        <input type="submit" class="submit" value="Show">
        <a class="submit print" target="_blank" href="print_equipment.php">Print</a>
    </form>
</div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Equipment</th>
                <th>Destination</th>
                <th>Returned</th>
                <th>&nbsp;</th>
            </tr>
            <?
            foreach ($report as $record) {
                ?>
                <tr>
                    <td>
                        <?
                        $datet = explode("-", $record['date']);
                        $thisdate = $datet['2']."-".$datet['1']."-".$datet['0'];
                        echo $thisdate;
                        ?>
                    </td>
                    <td><?=$equipmentList[$record['equipment_id']]['name'];?></td>
                    <td><?=$referralList[$record['destination']]['name'];?></td>
                    <td><?=$ynList[$record['return']];?></td>
                    <td><a href="edit_management.php?id=<?=$record['id'];?>">Edit</a></td>
                </tr>
            <?
            }
            ?>
        </table>
    </div>
<?

page_footer();

?>