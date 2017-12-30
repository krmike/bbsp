<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

$resourceId = 19; // Incident Summary Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$Incident = new Incident();
$Injury = new Injury();
$Activity = new Activity();
$Referral = new Referral();

$usersList = $User->getList();
$incidentslist = $Incident->getList();
$injuryTypesList = $Injury->getTypesList();
$injuryCategories = $Injury->getCategoriesList();
$injuryLocationList = $Injury->getLocationList();
$activityList = $Activity->getList();
$outcomeList = $Referral->getList();
$week = array(
    "1" => "Monday",
    "2" => "Tuesday",
    "3" => "Wednesday",
    "4" => "Thursday",
    "5" => "Friday",
    "6" => "Saturday",
    "7" => "Sunday"
);



$dateFrom = "01-".date("m-Y");
if ($_COOKIE['summary_date_from']) {
    $dateFrom = $_COOKIE['summary_date_from'];
}
if ($_POST['date_from_d']) {
    $dateFrom = $_POST['date_from_d']."-".$_POST['date_from_m']."-".$_POST['date_from_y'];
}
setcookie("summary_date_from", $dateFrom);

$dateTo = date("d-m-Y");
if ($_COOKIE['summary_date_to']) {
    $dateTo = $_COOKIE['summary_date_to'];
}
if ($_POST['date_to_d']) {
    $dateTo = $_POST['date_to_d']."-".$_POST['date_to_m']."-".$_POST['date_to_y'];
}
setcookie("summary_date_to", $dateTo);

if ($_COOKIE['summary_user']) {
    $userId = $_COOKIE['summary_user'];
}
if ($_POST['patroller'] != "all") {
    $userId = $_POST['patroller'];
} else {
    $userId = null;
}
setcookie("summary_user", $userId);


$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

page_header(5, "Incident Summary");

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
        Patroller:&nbsp;<select name="patroller">
            <option value="all">All</option>
            <?
            foreach ($usersList as $user) {
                ?>
                <option value="<?=$user['user_id']?>" <? if($user['user_id'] == $userId) {
                    ?> selected="selected"<?
                }?>>
                    <?=$user['name']?>&nbsp;<?=$user['surname'];?>
                </option>
            <?
            }
            ?>
        </select>
        <input type="submit" class="submit" value="Show">
        <a class="submit print" target="_blank" href="print_summary.php">Print</a>
        <a class="submit print" download="summary.xls" href="xls_summary.php?">Export to Excel</a>
        <a class="submit print" download="summary.pdf" href="pdf_summary.php?">Export to PDF</a>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <?
            $weekReport = $Incident->getWeekReport($fromDate, $toDate, $userId);
            foreach ($week as $dayId => $dayName) {
                ?>
                <th><?=$dayName;?></th>
            <?
            }
            ?>
        </tr>
        <tr>
            <?
                foreach ($week as $dayId => $dayName) {
                ?>
                <td class="amount-red">
                    <?=$weekReport[$dayId]?$weekReport[$dayId]:"0";?>
                </td>
            <?
            }
            ?>
        </tr>
    </table>
</div>

<div>
    <table class="table table-bordered">
        <tr>
            <th>
                Time
            </th>
            <th>
                Amount
            </th>
        </tr>
        <tr>
            <td>
                08:00 - 09:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "08:00", "09:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                09:00 - 10:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "09:00", "10:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                10:00 - 11:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "10:00", "11:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                11:00 - 12:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "11:00", "12:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                12:00 - 13:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "12:00", "13:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                13:00 - 14:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "13:00", "14:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                14:00 - 15:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "14:00", "15:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                15:00 - 16:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "15:00", "16:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                16:00 - 17:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "16:00", "17:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                17:00 - 18:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "17:00", "18:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                18:00 - 19:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "18:00", "19:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                19:00 - 20:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "19:00", "20:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                20:00 - 00:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "20:00", "00:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                00:00 - 08:00
            </td>
            <td class="amount-red">
                <?=$Incident->getTimeReport($fromDate, $toDate, "00:00", "08:00", $userId);?>
            </td>
        </tr>
    </table>
</div>

<div>
    <table class="table table-bordered">
        <tr>
            <th>
                Ambulance
            </th>
            <th>
                Amount
            </th>
        </tr>
        <tr>
            <td>
                Road
            </td>
            <td class="amount-red">
                <?=$Incident->getAmbulanceModeReport($fromDate, $toDate, "road", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                Air
            </td>
            <td class="amount-red">
                <?=$Incident->getAmbulanceModeReport($fromDate, $toDate, "helicopter", $userId);?>
            </td>
        </tr>
    </table>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr class="rotate-normal">
            <th class="rotateText">Injury Type<br> \ <br>Activity </th>
            <?
            $report = $Incident->getActivityInjuresReport($fromDate, $toDate, $userId);
            foreach ($activityList as $activity) {
                if ($report[$activity['id']]) {
                    ?>
                    <td class="rotateText">
                        <img width="30px" src="../images/activity<?=$activity['id'];?>.png">
                    </td>
                <?
                }
            }
            ?>
            <th nowrap class="rotateText">Injury<br> Type <br>Sum</th>
        </tr>
        <?
        foreach ($injuryTypesList as $type) {
            $injuryCount = 0;
            foreach ($activityList as $activity) {
                if ($report[$activity['id']]) {
                    $injuryCount += $report[$activity['id']][$type['id']];
                }
            }
            if ($injuryCount > 0) {
                ?>
                <tr class="rotateText">
                    <th><?=$type['name'];?></th>
                    <?
                    foreach ($activityList as $activity) {
                        if ($report[$activity['id']]) {
                            ?>
                            <td><?
                                ?>
                                <?=$report[$activity['id']][$type['id']]?$report[$activity['id']][$type['id']]:"0";?>
                            </td>
                        <?
                        }
                    }
                    ?>
                    <td class="amount-red">
                        <?=$injuryCount;?>
                    </td>
                </tr>
            <?
            }
        }
        ?>
        <tr>
            <th>Activity Sum</th>
            <?
            foreach ($activityList as $activity) {
                if ($report[$activity['id']]) {
                    ?>
                    <td class="amount-red rotateText">
                        <?=array_sum($report[$activity['id']]);?>
                    </td>
                <?
                }
            }
            ?>
            <th>&nbsp;</th>
        </tr>
    </table>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr class="rotate-normal">
            <th class="rotateText">Injury Location<br> \ <br>Activity </th>
            <?
            $report = $Incident->getActivityInjuryLocationReport($fromDate, $toDate, $userId);
            foreach ($activityList as $activity) {
                if ($report[$activity['id']]) {
                    ?>
                    <td class="rotateText">
                        <img width="30px" src="../images/activity<?=$activity['id'];?>.png">
                    </td>
                <?
                }
            }
            ?>
            <th nowrap class="rotateText">Injury<br> Location <br>Sum</th>
        </tr>
        <?
        foreach ($injuryLocationList as $loc) {
            $injuryCount = 0;
            foreach ($activityList as $activity) {
                if ($report[$activity['id']]) {
                    $injuryCount += $report[$activity['id']][$loc['id']];
                }
            }
            if ($injuryCount > 0) {
                ?>
                <tr class="rotateText">
                    <th><?=$loc['name'];?></th>
                    <?
                    foreach ($activityList as $activity) {
                        if ($report[$activity['id']]) {
                            ?>
                            <td><?
                                ?>
                                <?=$report[$activity['id']][$loc['id']]?$report[$activity['id']][$loc['id']]:"0";?>
                            </td>
                        <?
                        }
                    }
                    ?>
                    <td class="amount-red">
                        <?=$injuryCount;?>
                    </td>
                </tr>
            <?
            }
        }
        ?>
        <tr>
            <th>Activity Sum</th>
            <?
            foreach ($activityList as $activity) {
                if ($report[$activity['id']]) {
                    ?>
                    <td class="amount-red rotateText">
                        <?=array_sum($report[$activity['id']]);?>
                    </td>
                <?
                }
            }
            ?>
            <th>&nbsp;</th>
        </tr>
    </table>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr class="rotate-normal">
            <th class="rotateText">Injury Type<br> \ <br>Injury Location </th>
            <?
            $report = $Incident->getLocationInjuresReport($fromDate, $toDate, $userId);
            foreach ($injuryLocationList as $location) {
                if ($report[$location['id']]) {
                    ?>
                    <th class="rotateText">
                        <?=$location['name'];?>
                    </th>
                <?
                }
            }
            ?>
            <th nowrap class="rotateText">Injury<br> Type <br>Sum</th>
        </tr>
        <?
        foreach ($injuryTypesList as $type) {
            $injuryCount = 0;
            foreach ($injuryLocationList as $location) {
                if ($report[$location['id']]) {
                    $injuryCount += $report[$location['id']][$type['id']];
                }
            }
            if ($injuryCount > 0) {
                ?>
                <tr class="rotateText">
                    <th><?=$type['name'];?></th>
                    <?
                    foreach ($injuryLocationList as $location) {
                        if ($report[$location['id']]) {
                            ?>
                            <td><?
                                ?>
                                <?=$report[$location['id']][$type['id']]?$report[$location['id']][$type['id']]:"0";?>
                            </td>
                        <?
                        }
                    }
                    ?>
                    <td class="amount-red">
                        <?=$injuryCount;?>
                    </td>
                </tr>
            <?
            }
        }
        ?>
        <tr>
            <th>Injury Location Sum</th>
            <?
            foreach ($injuryLocationList as $location) {
                if ($report[$location['id']]) {
                    ?>
                    <td class="amount-red rotateText">
                        <?=array_sum($report[$location['id']]);?>
                    </td>
                <?
                }
            }
            ?>
            <th>&nbsp;</th>
        </tr>
    </table>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>
                Age
            </th>
            <th>
                0 - 6
            </th>
            <th>
                6 - 13
            </th>
            <th>
                13 - 18
            </th>
            <th>
                18 - 24
            </th>
            <th>
                24 - 35
            </th>
            <th>
                35 - 50
            </th>
            <th>
                50+
            </th>
        </tr>
        <tr>
            <th>
                Amount
            </th>
            <td class="amount-red">
                <?=$Incident->getAgeReport($fromDate, $toDate, "0", "6", $userId);?>
            </td>
            <td class="amount-red">
                <?=$Incident->getAgeReport($fromDate, $toDate, "6", "13", $userId);?>
            </td>
            <td class="amount-red">
                <?=$Incident->getAgeReport($fromDate, $toDate, "13", "18", $userId);?>
            </td>
            <td class="amount-red">
                <?=$Incident->getAgeReport($fromDate, $toDate, "18", "24", $userId);?>
            </td>
            <td class="amount-red">
                <?=$Incident->getAgeReport($fromDate, $toDate, "24", "35", $userId);?>
            </td>
            <td class="amount-red">
                <?=$Incident->getAgeReport($fromDate, $toDate, "35", "50", $userId);?>
            </td>
            <td class="amount-red">
                <?=$Incident->getAgeReport($fromDate, $toDate, "50", null, $userId);?>
            </td>
        </tr>
    </table>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>
                Injury Category
            </th>
            <th>
                Amount
            </th>
        </tr>
        <?
        $categoriesReport = $Incident->getInjuryCategoriesReport($fromDate, $toDate, $userId);
        foreach ($injuryCategories as $category) {
            ?>
            <tr>
                <td><?=$category['name'];?></td>
                <td class="amount-red">
                    <?=$categoriesReport[$category['id']]?$categoriesReport[$category['id']]:0;?>
                </td>
            </tr>
        <?
        }
        ?>
    </table>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <tr>
            <th>
                Helmet Worn
            </th>
            <th>
                Yes
            </th>
            <th>
                No
            </th>
            <th>
                NA
            </th>
        </tr>
        <tr>
            <th>
                Amount
            </th>
            <td class="amount-red">
                <?=$Incident->getHelmetReport($fromDate, $toDate, "1", $userId);?>
            </td>
            <td class="amount-red">
                <?=$Incident->getHelmetReport($fromDate, $toDate, "0", $userId);?>
            </td>
            <td class="amount-red">
                <?=$Incident->getHelmetReport($fromDate, $toDate, "2", $userId);?>
            </td>
        </tr>
    </table>
</div>


<?


page_footer();

?>