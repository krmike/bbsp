<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

$resourceId = 16; // Sanitized Incidents Report

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
if ($_COOKIE['incident_date_from']) {
    $dateFrom = $_COOKIE['incident_date_from'];
}
if ($_POST['date_from_d']) {
    $dateFrom = $_POST['date_from_d']."-".$_POST['date_from_m']."-".$_POST['date_from_y'];
}
setcookie("incident_date_from", $dateFrom);

$dateTo = date("d-m-Y");
if ($_COOKIE['incident_date_to']) {
    $dateTo = $_COOKIE['incident_date_to'];
}
if ($_POST['date_to_d']) {
    $dateTo = $_POST['date_to_d']."-".$_POST['date_to_m']."-".$_POST['date_to_y'];
}
setcookie("incident_date_to", $dateTo);

$timeFrom = "00:00";
if ($_COOKIE['incident_time_from']) {
    $timeFrom = $_COOKIE['incident_time_from'];
}
if ($_POST['time_from_hours']) {
    $timeFrom = $_POST['time_from_hours'].":".$_POST['time_from_minutes'];
}
setcookie("incident_time_from", $timeFrom);

$timeTo = "23:59";
if ($_COOKIE['incident_time_to']) {
    $timeTo = $_COOKIE['incident_time_to'];
}
if ($_POST['time_to_hours']) {
    $timeTo = $_POST['time_to_hours'].":".$_POST['time_to_minutes'];
}
setcookie("incident_time_to", $timeTo);

if ($_POST['weekday'] != "all") {
    $weekday = $_POST['weekday'];
} else {
    $weekday = null;
}

if ($_POST['injury_type'] != "all") {
    $injuryTypeId = $_POST['injury_type'];
} else {
    $injuryTypeId = null;
}

if ($_POST['injury_location'] != "all") {
    $injuryLocationId = $_POST['injury_location'];
} else {
    $injuryLocationId = null;
}

if ($_POST['activity'] != "all") {
    $activityId = $_POST['activity'];
} else {
    $activityId = null;
}

if ($_POST['outcome'] != "all") {
    $outcomeId = $_POST['outcome'];
} else {
    $outcomeId = null;
}

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];
$coords = array();

$report = $Incident->getReport($fromDate, $toDate, $injuryTypeId, $injuryLocationId, $activityId, $outcomeId);
foreach ($report['report'] as $incident) {
    if ($incident['map_coordinates'] != "") {
        $latlng = explode(",", $incident['map_coordinates']);
        if (is_numeric($latlng[0]) && is_numeric($latlng[1])) {
            $coords[] = $incident['map_coordinates'];
        }
    }
}
page_header(5, "Incidents Report");
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
            Injury Type:&nbsp;<select name="injury_type">
                <option value="all">All</option>
                <?
                foreach ($injuryTypesList as $type) {
                    ?>
                    <option value="<?=$type['id']?>" <? if($type['id'] == $typeId) {
                        ?> selected="selected"<?
                    }?>>
                        <?=$type['name']?>
                    </option>
                <?
                }
                ?>
            </select>
        </div>
        <div class="input-left">
            Injury Location:&nbsp;<select name="injury_location">
                <option value="all">All</option>
                <?
                foreach ($injuryLocationList as $location) {
                    ?>
                    <option value="<?=$location['id']?>" <? if($location['id'] == $injuryLocationId) {
                        ?> selected="selected"<?
                    }?>>
                        <?=$location['name']?>
                    </option>
                <?
                }
                ?>
            </select>
        </div>
        <div class="input-left">
            Activity:&nbsp;<select name="activity">
                <option value="all">All</option>
                <?
                foreach ($activityList as $activity) {
                    ?>
                    <option value="<?=$activity['id']?>" <? if($activity['id'] == $activityId) {
                        ?> selected="selected"<?
                    }?>>
                        <?=$activity['name']?>
                    </option>
                <?
                }
                ?>
            </select>
        </div>
        <div class="input-left">
            Referral Outcome:&nbsp;<select name="outcome">
                <option value="all">All</option>
                <?
                foreach ($outcomeList as $outcome) {
                    ?>
                    <option value="<?=$outcome['id']?>" <? if($outcome['id'] == $outcomeId) {
                        ?> selected="selected"<?
                    }?>>
                        <?=$outcome['name']?>
                    </option>
                <?
                }
                ?>
            </select>
        </div>
        <input type="submit" class="submit" value="Show">
    </form>
</div>


    <div>
        From <?=$dateFrom;?> to <?=$dateTo;?> happened <b><?=$report['count'];?></b> incident(s)
    </div>
<div class="report-map" id="reportMapContainer"></div>

</div>

<script>
    $(document).ready(function(){
    var coords = <?=json_encode($coords);?>;
    showReportMap(coords);
    })
</script>

<?


page_footer();

?>