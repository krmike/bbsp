<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");


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
$outcomeList = $Referral->getList();
$weatherList = $Weather->getList();
$snowList = $Snow->getList();
$visibilityList = $Visibility->getList();
$liftList = $Lift->getList();
$runList = $Run->getList();
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
            opacity: 0.3;
        }
    </style>

        <table border="1px solid" id="incidentTable" class="table table-bordered">
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
                            <td colspan="2">Location</td>
                            <td colspan="2">Category</td>
                            <td colspan="2">Type</td>
                            <td colspan="3">Comment</td>
                        </tr>
                        <?
                        foreach ($injures as $injury) {
                            ?>
                            <tr>
                                <td colspan="2">
                                    <?=$injuryLocationList[$injury['location_id']]['name'];?>
                                </td>
                                <td colspan="2">
                                    <?=$injuryCategoryList[$injury['category_id']]['name'];?>
                                </td>
                                <td colspan="2">
                                    <?=$injuryTypesList[$injury['type_id']]['name'];?>
                                </td>
                                <td colspan="3">
                                    <?=$injury['comment'];?>
                                </td>
                            </tr>
                        <?
                        }
                        ?>
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
<!--                        <img src="/Margarita/signatures/ws_--><?//=$_GET['id'];?><!--.png">-->
                    </td>
                    <td colspan="3">
                        <?=$usersList[$incident['witness_sec_id']]['name'];?> <?=$usersList[$incident['witness_sec_id']]['surname'];?>
<!--                        <img src="/Margarita/signatures/ws_--><?//=$_GET['id'];?><!--.png">-->
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
<!--                        <img src="/Margarita/signatures/drs_--><?//=$_GET['id'];?><!--.png">-->
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
<!--                    <img src="/Margarita/signatures/ps_--><?//=$_GET['id'];?><!--.png">-->
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
                        <img src="/Margarita/signatures/cs_<?=$_GET['id'];?>.png">
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

<!--<script>-->
<!--    tableToExcel('incidentTable', 'W3C Example Table');-->
<!--</script>-->
