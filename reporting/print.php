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
<?

$resourceId = 13; // Incidents Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$Incident = new Incident();
$Injury = new Injury();
$Activity = new Activity();
$Referral = new Referral();
$Weather = new Weather();
$Snow = new Snow();
$Visibility = new Visibility();
$Lift = new Lift();
$Run = new Run();
$Role = new Role();
$Injury = new Injury();
$Transport = new Transport();
$Equipment = new Equipment();
$Dr = new Dr();
$Ambulance = new Ambulance();

$usersList = $User->getList();
$injuryTypesList = $Injury->getTypesList();
$activityList = $Activity->getList();
$liftList = $Lift->getList();
$runList = $Run->getList();
$outcomeList = $Referral->getList();
$weatherList = $Weather->getList();
$snowList = $Snow->getList();
$visibilityList = $Visibility->getList();

$rolesList = $Role->getList();
$injuryLocationList = $Injury->getLocationList();
$injuryCategoryList = $Injury->getCategoriesList();
$transportList = $Transport->getList();
$equipmentList = $Equipment->getList();
$drList = $Dr->getList();
$destinationList = $Ambulance->getList();
$week = array(
    "1" => "Monday",
    "2" => "Tuesday",
    "3" => "Wednesday",
    "4" => "Thursday",
    "5" => "Friday",
    "6" => "Saturday",
    "7" => "Sunday"
);
$ynList = array(
    0 => "No",
    1 => "Yes",
    2 => "NA"
);
$genders = array(
    1 => "Male",
    2 => "Female"
);
$week = array(
    "1" => "Monday",
    "2" => "Tuesday",
    "3" => "Wednesday",
    "4" => "Thursday",
    "5" => "Friday",
    "6" => "Saturday",
    "7" => "Sunday"
);


$incident = $Incident->getById($_GET['id']);
$incidentPatrollers = $Incident->getIncidentPatrollers($_GET['id']);
$incidentEquipment = $Incident->getIncidentEquipment($_GET['id']);
$incidentPhones = $Incident->getIncidentPhones($_GET['id']);
$vitals = $Incident->getIncidentVitals($_GET['id']);
$injures = $Incident->getIncidentInjures($_GET['id']);

?>
<style>
    polygon {
        opacity: 0;
    }
    polygon.poly-show {
        opacity: 0.5;
    }

    .report-body {
        position: relative;
        display: inline-block;
        top: -266px;
        width: 317px;
        height: 270px;
    }

    .report-img {
        display: inline-block;
        height: 270px;
    }
</style>
    <table style="max-width: 800px;" class="table table-bordered">
        <tr>
            <th colspan="9">Casualty Details</th>
        </tr>
        <tr>
            <td colspan="2">First Name</td>
            <td colspan="2"><?=$incident['first_name'];?></td>
            <td colspan="2">Last Name</td>
            <td colspan="3"><?=$incident['last_name'];?></td>
        </tr>
        <tr>
            <td>D.O.B.</td>
            <td colspan="2">
                <?
                $date = explode("-", $incident['dob']);
                $thisdate = $date['2']."-".$date['1']."-".$date['0'];
                echo $thisdate;
                ?>
            </td>
            <td>Age</td>
            <td colspan="2"><?=$incident['age'];?></td>
            <td>Gender</td>
            <td colspan="2"><?=$genders[$incident['gender']];?></td>
        </tr>
        <tr>
            <td colspan="2">MtBB Employee</td>
            <td colspan="2"><?=$ynList[$incident['mtbb_employee']];?></td>
            <td colspan="2">Employee On Duty</td>
            <td colspan="3"><?=$ynList[$incident['employee_on_duty']];?></td>
        </tr>
        <tr>
            <td>Address</td>
            <td colspan="3"><?=$incident['street'];?></td>
            <td>City</td>
            <td colspan="4"><?=$incident['city'];?></td>
        </tr>
        <tr>
            <td>State</td>
            <td colspan="2"><?=$incident['state'];?></td>
            <td>Country</td>
            <td colspan="2"><?=$incident['country'];?></td>
            <td>Postcode</td>
            <td colspan="2"><?=$incident['postcode'];?></td>
        </tr>
        <tr>
            <td colspan="2">
                Phone Numbers:
            </td>
            <td colspan="7">
                <?
                foreach ($incidentPhones as $phone) {
                    ?>
                    <?=$phone['phone']?>&nbsp;&nbsp;&nbsp;
                <?
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Allergies</td>
            <td colspan="3"><?=$incident['allergies'];?></td>
            <td>Medications</td>
            <td colspan="4"><?=$incident['medications'];?></td>
        </tr>
        <tr>
            <td>Last meals</td>
            <td colspan="3"><?=$incident['last_meals'];?></td>
            <td>Last bathroom</td>
            <td colspan="4"><?=$incident['last_bathroom'];?></td>
        </tr>
        <tr>
            <td>History</td>
            <td colspan="8"><?=$incident['history'];?></td>
        </tr>
        <tr>
            <th colspan="9">Incident Details</th>
        </tr>
        <tr>
            <td>Incident Date</td>
            <td colspan="2">
                <?
                $date = explode("-", $incident['incident_date']);
                $thisdate = $date['2']."-".$date['1']."-".$date['0'];
                echo $thisdate;
                ?>
            </td>
            <td colspan="2">Incident Time</td>
            <td><?=$incident['incident_time'];?></td>
            <td colspan="2">Patroller Arrival Time</td>
            <td><?=$incident['patroller_arrival_time'];?></td>
        </tr>
        <tr>
            <td colspan="2">Description of Incident</td>
            <td colspan="6"><?=$incident['description'];?></td>
            <td>
                Helmet<hr>
                <?=$ynList[$incident['helmet']];?>
            </td>
        </tr>
        <tr>
            <td colspan="2">Activity</td>
            <td colspan="<?
            if ($incident['activity_id'] == "1" || $incident['activity_id'] == "2" || $incident['activity_id'] == "4" || $incident['activity_id'] == "7") {
                ?>2<?
            } else {
                ?>7<?
            }
            ?>">
                <?=$activityList[$incident['activity_id']]['name'];?>
            </td>
            <?
            if ($incident['activity_id'] == "1") {
                ?>
                <td colspan="1">Bindings Release</td>
                <td colspan="1"><?=$incident['bindings_release'];?></td>
            <?
            }
            if ($incident['activity_id'] == "2") {
                ?>
                <td colspan="1">Snowboard Stance</td>
                <td colspan="1"><?=$incident['sb_stance'];?></td>
            <?
            }
            if ($incident['activity_id'] == "1" || $incident['activity_id'] == "2") {
                ?>
                <td colspan="1">Ability</td>
                <td colspan="2"><?=$incident['ability'];?></td>
            <?
            }
            if ($incident['activity_id'] == "4" || $incident['activity_id'] == "7") {
                ?>
                <td colspan="2">Ability</td>
                <td colspan="3"><?=$incident['ability'];?></td>
            <?
            }
            ?>
        </tr>
        <tr>
            <td colspan="2">
                Equipment Source
            </td>
            <td colspan="<?
            if ($incident['equipment_source'] == "rental") {
                ?>2<?
            } else {
                ?>7<?
            }
            ?>">
                <?=$incident['equipment_source'];?>
            </td>
            <?
            if ($incident['equipment_source'] == "rental") {
                ?>
                <td colspan="2">Rental Source</td>
                <td colspan="3"><?=$incident['rental_source'];?></td>
            <?
            }
            ?>
        </tr>
        <tr>
            <td>Weather</td>
            <td colspan="2"><?=$weatherList[$incident['weather_id']]['name'];?></td>
            <td>Snow</td>
            <td colspan="2"><?=$snowList[$incident['snow_id']]['name'];?></td>
            <td>Visibility</td>
            <td colspan="2"><?=$visibilityList[$incident['visibility_id']]['name'];?></td>
        </tr>
        <tr>
            <td>Map Coordinates</td>
            <td colspan="2"><?=$incident['map_coordinates'];?></td>
            <td>Lift / Category</td>
            <td colspan="2"><?=$liftList[$incident['lift_id']]['name'];?></td>
            <td>Trail / Location</td>
            <td colspan="2"><?=$runList[$incident['run_id']]['name'];?></td>
        </tr>
        <tr>
            <td colspan="5">Patroller</td>
            <td colspan="4">Role</td>
        </tr>
        <?
        $i = 1;
        foreach ($incidentPatrollers as $patroller) {
            ?>
            <tr>
                <td><?=$i;?></td>
                <td colspan="4"><?=$usersList[$patroller['patroller_id']]['name'];?> <?=$usersList[$patroller['patroller_id']]['surname'];?></td>
                <td colspan="4"><?=$rolesList[$patroller['role_id']]['name'];?></td>
            </tr>
            <?
            $i++;
        }
        ?>
        <tr>
            <th colspan="9">Injuries / Illness / Vitals</th>
        </tr>
        <tr>
            <td colspan="2">Symptoms</td>
            <td colspan="7"><?=$incident['symptoms'];?></td>
        </tr>
        <tr>
            <td>LOC</td>
            <td colspan="<?
            if ($incident['loc'] == 1) {
                ?>1<?
            } else {
                ?>8<?
            }
            ?>">
                <?=$ynList[$incident['loc']];?>
            </td>
            <?
            if ($incident['loc'] == 1) {
                ?>
                <td>LOC Comment</td>
                <td colspan="6"><?=$incident['loc_comment'];?></td>
            <?
            }
            ?>
        </tr>
        <tr>
            <td>Spinal</td>
            <td colspan="<?
            if ($incident['spinal_injury'] == 1) {
                ?>1<?
            } else {
                ?>8<?
            }
            ?>">
                <?=$ynList[$incident['spinal_injury']];?>
            </td>
            <?
            if ($incident['spinal_injury'] == 1) {
                ?>
                <td>Spinal Comment</td>
                <td colspan="6"><?=$incident['spinal_comment'];?></td>
            <?
            }
            ?>
        </tr>
        <tr>
            <td colspan="9">Probable injury</td>
        </tr>

        <tr>
            <td colspan="6">
                <table class="table">
                    <tr>
                        <td>Location</td>
                        <td>Category</td>
                        <td>Type</td>
                        <td>Comment</td>
                    </tr>
                    <?
                    foreach ($injures as $injury) {
                        ?>
                        <tr>
                            <td>
                                <?=$injuryLocationList[$injury['location_id']]['name'];?>
                            </td>
                            <td>
                                <?=$injuryCategoryList[$injury['category_id']]['name'];?>
                            </td>
                            <td>
                                <?=$injuryTypesList[$injury['type_id']]['name'];?>
                            </td>
                            <td>
                                <?=$injury['comment'];?>
                            </td>
                        </tr>
                    <?
                    }
                    ?>
                </table>
            </td>
            <td colspan="3">
                <div class="report-img">
                    <img src="../images/body.png">
                    <svg class="report-body">
                        <polygon points="256,34 238,34 234,22 232,14 242,6 252,6 258,14 260,22 258,22" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="72,33 64,38 56,34 53,26 50,12 55,5 65,3 74,9 76,20 76,25 76,24" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="74,43 54,43 53,36 63,36 72,34" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 3) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="238,33 235,42 260,42 255,33" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 3) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="240,40 240,127 255,127 255,40" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 4) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="224,43 224,126 240,126 240,43" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 5 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="254,44 254,127 271,127 271,44" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 5 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="39,42 39,81 89,81 89,42" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 6) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="42,82 42,119 88,119 88,82" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 7) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="40,119 40,149 89,149 89,119" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 8) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="225,128 225,141 273,141 273,128" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 8) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="88,48 88,70 88,76 99,75 99,67 98,58 94,50 90,47" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 9 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="29,75 31,52 35,47 39,46 39,75 39,75" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 9 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="227,47 226,76 212,76 211,67 211,60 216,51 220,48" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 9 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="270,47 269,75 269,74 283,75 284,66 281,56 276,50 276,50" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 9 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="271,72 271,93 291,93 291,72" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 10 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="206,72 206,93 225,93 225,72" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 10 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="87,72 87,93 105,93 105,72" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 10 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="26,72 25,93 41,93 41,72" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 10 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="208,94 208,104 224,104 224,94" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 11 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="271,92 271,103 288,103 288,92" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 11 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="206,104 221,105 222,107 218,118 211,129 200,125 204,119" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 12 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="275,105 290,104 292,113 297,126 286,130 279,119" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 12 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="25,95 39,96 38,107 33,118 28,128 16,126 20,115" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 12 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="89,96 103,95 106,100 107,107 110,114 112,124 101,127 94,114" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 12 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="10,137 15,129 16,121 31,121 23,137 24,141 24,141" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 13 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="106,139 117,133 111,122 97,122 104,131 104,131" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 13 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="193,137 206,139 208,132 211,127 199,124 196,131 197,131" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 13 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="288,142 305,136 296,128 295,121 287,132" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 13 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="289,139 290,153 297,158 303,158 304,143 309,146 305,137 303,135" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 14 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="202,160 208,147 208,138 192,135 187,141 193,145 193,141 194,156 196,159" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 14 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="106,141 105,152 112,158 118,156 119,144 119,139 121,144 126,142 120,134 116,131 103,136" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 14 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="7,132 2,144 5,145 10,142 10,155 13,159 20,156 24,148 24,136 8,132" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 14 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="40,149 48,177 65,177 65,149" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 15 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="65,149 65,177 82,177 89,149" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 15 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="223,142 227,173 248,173 248,142" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 15 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="248,142 248,173 270,173 273,142" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 15 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="229,174 229,204 248,204 248,174" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 16 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="248,174 248,204 270,204 270,174" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 16 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="46,177 46,205 65,205 65,177" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 16 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="65,177 65,205 87,205 87,177" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 16 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="46,206 53,236 65,236 65,206" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 17 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="65,206 65,236 79,236 86,206" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 17 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="229,205 236,239 248,239 248,205" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 17 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="248,205 248,239 262,239 269,205" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 17 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="49,237 49,248 65,248 65,237" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 18 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="65,237 65,248 82,248 82,237" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 18 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="232,238 232,250 248,250 248,238" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 18 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="248,238 248,250 267,250 267,238" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 18 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="229,250 229,257 248,257 248,250" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 19 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="248,250 248,257 269,257 269,250" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 19 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="44,248 44,258 65,258 65,248" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 19 && $injury['side'] == 2) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                        <polygon points="65,248 65,258 85,258 85,248" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"
                            <?
                            foreach ($injures as $injury) {
                                if ($injury['location_id'] == 19 && $injury['side'] == 1) {
                                    ?>
                                    class="poly-show"
                                <?
                                }
                            }
                            ?>
                            />
                    </svg>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="9">Vitals</td>
        </tr>
        <tr>
            <td>Time</td>
            <td>GCS</td>
            <td>Pupils</td>
            <td>BP</td>
            <td>Resp</td>
            <td>Pulse</td>
            <td>Skin</td>
            <td>O2</td>
            <td>Temp</td>
        </tr>
        <?
        foreach ($vitals as $vital) {
            ?>
            <tr>
                <td><?=$vital['time'];?></td>
                <td><?=$vital['gcs'];?></td>
                <td><?=$vital['pupils'];?></td>
                <td><?=$vital['bp'];?></td>
                <td><?=$vital['respiration'];?></td>
                <td><?=$vital['pulse'];?></td>
                <td><?=$vital['skin'];?></td>
                <td><?=$vital['o2'];?></td>
                <td><?=$vital['temp'];?></td>
            </tr>
        <?
        }
        ?>
        <tr>
            <th colspan="9">Treatment / Handover</th>
        </tr>
        <tr>
            <td>Transport</td>
            <td colspan="2"><?=$transportList[$incident['transport_id']]['name'];?></td>
            <td>Penthrane</td>
            <td colspan="<?
            if ($incident['penthrane'] == "0") {
                ?>5<?
            }
            if ($incident['penthrane'] == "3") {
                ?>3<?
            }
            if ($incident['penthrane'] == "6") {
                ?>1<?
            }
            ?>">
                <?
                if ($incident['penthrane'] == 0) {
                    ?>No<?
                } else {
                    ?>
                    <?=$incident['penthrane'];?> ml
                <?
                }
                ?>
            </td>
            <?
            if ($incident['penthrane'] > "0") {
                ?>
                <td>3ml Time</td>
                <td><?=$incident['penthrane_3ml_time'];?></td>
            <?
            }
            if ($incident['penthrane'] == "6") {
                ?>
                <td>6ml Time</td>
                <td><?=$incident['penthrane_6ml_time'];?></td>
            <?
            }
            ?>
        </tr>
        <tr>
            <td>Entonox</td>
            <td colspan="<?
            if ($incident['entonox'] == "1") {
                ?>2<?
            } else {
                ?>8<?
            }
            ?>">
                <?=$ynList[$incident['entonox']];?>
            </td>
            <?
            if ($incident['entonox'] == "1") {
                ?>
                <td>Start Amount</td>
                <td colspan="2"><?=$incident['entonox_start_amount'];?></td>
                <td>End Amount</td>
                <td colspan="2"><?=$incident['entonox_end_amount'];?></td>
            <?
            }
            ?>
        </tr>
        <?
        if ($incident['entonox'] == "1") {
            ?>
            <tr>
                <td colspan="2">
                    Entonox Start Time:
                    <hr>
                    <?=$incident['entonox_start_time'];?>
                </td>
                <td>Witness:</td>
                <td colspan="3">
                    <?=$usersList[$incident['witness_id']]['name'];?> <?=$usersList[$incident['witness_id']]['surname'];?>
                    <img src="/signatures/ws_<?=$_GET['id'];?>.png">
                </td>
                <td colspan="3">
                    <?=$usersList[$incident['witness_sec_id']]['name'];?> <?=$usersList[$incident['witness_sec_id']]['surname'];?>
                    <img src="/signatures/wss_<?=$_GET['id'];?>.png">
                </td>
            </tr>
        <?
        }
        ?>
        <tr>
            <td>Oxygen</td>
            <td colspan="<?
            if ($incident['oxygen'] == "1") {
                ?>2<?
            } else {
                ?>8<?
            }
            ?>">
                <?=$ynList[$incident['oxygen']];?>
            </td>
            <?
            if ($incident['oxygen'] == "1") {
                ?>
                <td>Start Time</td>
                <td colspan="2"><?=$incident['oxygen_start_time'];?></td>
                <td>Flow Rate</td>
                <td colspan="2"><?=$incident['oxygen_flow_rate'];?></td>
            <?
            }
            ?>
        </tr>
        <tr>
            <td colspan="2">Equipment Used</td>
            <td colspan="7">
                <?
                foreach ($incidentEquipment as $equipment) {
                    ?>
                    <?=$equipmentList[$equipment['equipment_id']]['name'];?><br>
                <?
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">Treatment Provided</td>
            <td colspan="7"><?=$incident['treatment_provided'];?></td>
        </tr>
        <tr>
            <td colspan="2">Advice To Casualty</td>
            <td colspan="7"><?=$incident['recommended_advice'];?></td>
        </tr>
        <tr>
            <td colspan="2">Destination</td>
            <td colspan="<?
            if ($incident['referral_outcome_id'] == 6) {
                ?>1<?
            } else {
                ?>7<?
            }
            ?>">
                <?=$outcomeList[$incident['referral_outcome_id']]['name'];?>
            </td>
            <?
            if ($incident['referral_outcome_id'] == 6) {
                ?>
                <td colspan="6">
                    <table class=" table">
                        <tr>
                            <td>Ambulance Mode</td>
                            <td><?=$incident['ambulance_mode'];?></td>
                        </tr>
                        <tr>
                            <td>'000' Call time</td>
                            <td><?=$incident['ambulance_call_time'];?></td>
                        </tr>
                        <tr>
                            <td>Ambulance Arrival Time</td>
                            <td><?=$incident['ambulance_arrival_time'];?></td>
                        </tr>
                        <tr>
                            <td>Ambulance Departure Time</td>
                            <td><?=$incident['ambulance_departure_time'];?></td>
                        </tr>
                        <tr>
                            <td>Ambulance Destination</td>
                            <td><?=$destinationList[$incident['ambulance_destination_id']]['name'];?></td>
                        </tr>
                    </table>
                </td>
            <?
            }
            ?>
        </tr>
        <tr>
            <td colspan="2">Additional Notes</td>
            <td colspan="7"><?=$incident['additional_notes'];?></td>
        </tr>
        <tr>
            <?
            if ($incident['dr_id'] >0) {
                ?>
                <th colspan="3">Doctor</th>
            <?
            }
            ?>
            <th colspan="<?
            if ($incident['dr_id'] >0) {
                ?>3<?
            } else {
                ?>4<?
            }
            ?>">
                Patroller
            </th>
            <th colspan="<?
            if ($incident['dr_id'] >0) {
                ?>3<?
            } else {
                ?>5<?
            }
            ?>">
                Casualty
            </th>
        </tr>
        <tr>
            <?
            if ($incident['dr_id'] >0) {
                ?>
                <td colspan="3">
                    <img src="/signatures/drs_<?=$_GET['id'];?>.png">
                </td>
            <?
            }
            ?>
            <td colspan="<?
            if ($incident['dr_id'] >0) {
                ?>3<?
            } else {
                ?>4<?
            }
            ?>">
                <img src="/signatures/ps_<?=$_GET['id'];?>.png">
            </td>
            <td colspan="<?
            if ($incident['dr_id'] >0) {
                ?>3<?
            } else {
                ?>5<?
            }
            ?>">
                <?
                if ($incident['unable_to_sign'] == 1) {
                    ?>
                    Unable to sign. Reason: <br>
                    <?
                    echo $incident['unable_reason'];
                } else {
                    ?>
                    <img src="/signatures/cs_<?=$_GET['id'];?>.png">
                <?
                }
                ?>
            </td>
        </tr>
        <tr>
            <?
            if ($incident['dr_id'] >0) {
                ?>
                <td colspan="3"><?=$drList[$incident['dr_id']]['name'];?></td>
            <?
            }
            ?>
            <td colspan="<?
            if ($incident['dr_id'] >0) {
                ?>3<?
            } else {
                ?>4<?
            }
            ?>">
                <?=$usersList[$incident['signature_id']]['name'];?> <?=$usersList[$incident['signature_id']]['surname'];?>
            </td>
            <td colspan="<?
            if ($incident['dr_id'] >0) {
                ?>3<?
            } else {
                ?>5<?
            }
            ?>">
                <?
                if ($incident['parent_signature'] == 1) {
                    ?>Parent / Guardian <br><?
                }
                ?>
            </td>
        </tr>
    </table>
