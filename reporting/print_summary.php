<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");
?>
<head>
    <link rel="icon" type="image/ico" href="/images/favicon.ico" />
    <link rel="stylesheet" href="/style/bootstrap.css" type="text/css">
</head>
<style>
    .rotateText {
        text-align: center;
        vertical-align: middle !important;
    }
</style>
<?

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

$dateTo = date("d-m-Y");
if ($_COOKIE['summary_date_to']) {
    $dateTo = $_COOKIE['summary_date_to'];
}
$userId = $_COOKIE['summary_user'];

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

?>
<h4>&nbsp;&nbsp;From <?=$dateFrom;?> To <?=$dateTo;?>.
    <?
    if ($userId) {
        ?>&nbsp;&nbsp;Patroller: <?=$usersList[$userId]['name'];?>&nbsp;<?=$usersList[$userId]['surname'];?><?
    }
    ?>
</h4>

    <table style="max-width: 800px;" class="table table-bordered">
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
                <td>
                    <?=$weekReport[$dayId]?$weekReport[$dayId]:"0";?>
                </td>
            <?
            }
            ?>
        </tr>
    </table>

    <table style="max-width: 800px;" class="table table-bordered">
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
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "08:00", "09:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                09:00 - 10:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "09:00", "10:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                10:00 - 11:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "10:00", "11:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                11:00 - 12:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "11:00", "12:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                12:00 - 13:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "12:00", "13:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                13:00 - 14:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "13:00", "14:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                14:00 - 15:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "14:00", "15:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                15:00 - 16:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "15:00", "16:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                16:00 - 17:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "16:00", "17:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                17:00 - 18:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "17:00", "18:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                18:00 - 19:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "18:00", "19:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                19:00 - 20:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "19:00", "20:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                20:00 - 00:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "20:00", "00:00", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                00:00 - 08:00
            </td>
            <td>
                <?=$Incident->getTimeReport($fromDate, $toDate, "00:00", "08:00", $userId);?>
            </td>
        </tr>
    </table>

    <table style="max-width: 800px;" class="table table-bordered">
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
            <td>
                <?=$Incident->getAmbulanceModeReport($fromDate, $toDate, "road", $userId);?>
            </td>
        </tr>
        <tr>
            <td>
                Air
            </td>
            <td>
                <?=$Incident->getAmbulanceModeReport($fromDate, $toDate, "helicopter", $userId);?>
            </td>
        </tr>
    </table>

    <table style="max-width: 800px;" class="table table-bordered">
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

    <table style="max-width: 800px;" class="table table-bordered">
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

<table style="max-width: 800px;" class="table table-bordered">
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

    <table style="max-width: 800px;" class="table table-bordered">
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
            <td>
                <?=$Incident->getAgeReport($fromDate, $toDate, "0", "6", $userId);?>
            </td>
            <td>
                <?=$Incident->getAgeReport($fromDate, $toDate, "6", "13", $userId);?>
            </td>
            <td>
                <?=$Incident->getAgeReport($fromDate, $toDate, "13", "18", $userId);?>
            </td>
            <td>
                <?=$Incident->getAgeReport($fromDate, $toDate, "18", "24", $userId);?>
            </td>
            <td>
                <?=$Incident->getAgeReport($fromDate, $toDate, "24", "35", $userId);?>
            </td>
            <td>
                <?=$Incident->getAgeReport($fromDate, $toDate, "35", "50", $userId);?>
            </td>
            <td>
                <?=$Incident->getAgeReport($fromDate, $toDate, "50", null, $userId);?>
            </td>
        </tr>
    </table>

    <table style="max-width: 800px;" class="table table-bordered">
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
                <td>
                    <?=$categoriesReport[$category['id']]?$categoriesReport[$category['id']]:0;?>
                </td>
            </tr>
        <?
        }
        ?>
    </table>

    <table style="max-width: 800px;" class="table table-bordered">
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
            <td>
                <?=$Incident->getHelmetReport($fromDate, $toDate, "1", $userId);?>
            </td>
            <td>
                <?=$Incident->getHelmetReport($fromDate, $toDate, "0", $userId);?>
            </td>
            <td>
                <?=$Incident->getHelmetReport($fromDate, $toDate, "2", $userId);?>
            </td>
        </tr>
    </table>