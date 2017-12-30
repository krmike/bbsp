<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 22:31
 */

include('config.php');
require_once ('signature-to-image.php');

$resourceId = 4; // incident Form

$Access = new Access();
$DB = new Db();

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$Activity = new Activity();
$Weather = new Weather();
$Snow = new Snow();
$Visibility = new Visibility();
$Lift = new Lift();
$Run = new Run();
$User = new User();
$Role = new Role();
$Injury = new Injury();
$Transport = new Transport();
$Equipment = new Equipment();
$Ambulance = new Ambulance();
$Dr = new Dr();
$Referral = new Referral();
$Incident = new Incident();
$Penthrane = new Penthrane();

$activityList = $Activity->getList();
$weatherList = $Weather->getList();
$snowList = $Snow->getList();
$visibilityList = $Visibility->getList();
$liftList = $Lift->getList();
$runList = $Run->getList();
$patrollersList = $User->getList();
$patrollersSignatureList = $User->getListCanSign();
$rolesList = $Role->getList();
$injuryLocationList = $Injury->getLocationList();
$injuryCategoriesList = $Injury->getCategoriesList();
$injuryTypesList = $Injury->getTypesList();
$transportList = $Transport->getList();
$equipmentList = $Equipment->getList();
$ambulanceList = $Ambulance->getList();
$drList = $Dr->getList();
$referralList = $Referral->getList();

if ($_REQUEST['id']) {
    $incidentData = $Incident->getById($_REQUEST['id']);
}

if ($_POST['save']) {

    $thisdate = $_POST['incident_date_y']."-".$_POST['incident_date_m']."-".$_POST['incident_date_d'];

    $name = $thisdate." ".$_POST['location']." ".$_POST['first_name']." ".$_POST['last_name'];

    $date = mktime("01","00", "00", $_POST['incident_date_m'], $_POST['incident_date_d'], $_POST['incident_date_y']);
    $weekday = date("N", $date);

    $dob = $_POST['dob_y']."-".$_POST['dob_m']."-".$_POST['dob_d'];

    $incidentTime = $_POST['incident_time_hours'].":".$_POST['incident_time_minutes'];
    $patrollerArrivalTime = $_POST['patroller_arrival_time_hours'].":".$_POST['patroller_arrival_time_minutes'];

    if ($_REQUEST['id']) {
        $timestamp = $incidentData['incident_timestamp'];
    } else {
        $timestamp = date("Y-m-d H:i:s");
    }

    if ($_POST['save'] == "Submit") {
        $submitted = 1;
    } else {
        $submitted = 0;
    }

    $aData = array(
        "submit"                     => $submitted,
        "name"                       => $name,
        "incident_timestamp"         => $timestamp,
        "first_name"                 => $_POST['first_name'],
        "last_name"                  => $_POST['last_name'],
        "mtbb_employee"              => $_POST['mtbb_employee'],
        "gender"                     => $_POST['gender'],
        "street"                     => $_POST['street'],
        "city"                       => $_POST['city'],
        "state"                      => $_POST['state'],
        "country"                    => $_POST['country'],
        "postcode"                   => $_POST['postcode'],
        "dob"                        => $dob,
        "age"                        => $_POST['age'],
        "allergies"                  => $_POST['allergies'],
        "medications"                => $_POST['medications'],
        "last_meals"                 => $_POST['last_meals'],
        "last_bathroom"              => $_POST['last_bathroom'],
        "history"                    => $_POST['history'],
        "incident_date"              => $thisdate,
        "incident_time"              => $incidentTime,
        "incident_weekday"           => $weekday,
        "patroller_arrival_time"     => $patrollerArrivalTime,
        "description"                => $_POST['description'],
        "helmet"                     => $_POST['helmet'],
        "activity_id"                => $_POST['activity'],
        "equipment_source"           => $_POST['equipment_source'],
        "weather_id"                 => $_POST['weather'],
        "snow_id"                    => $_POST['snow'],
        "visibility_id"              => $_POST['visibility'],
        "lift_id"                    => $_POST['lift'],
        "run_id"                     => $_POST['run'],
        "map_coordinates"            => $_POST['map_coordinates'],
        "symptoms"                   => $_POST['symptoms'],
        "spinal_injury"              => $_POST['spinal_injury'],
        "loc"                        => $_POST['loc'],
        "transport_id"               => $_POST['transport'],
        "penthrane"                  => $_POST['penthrane'],
        "witness_id"                 => $_POST['witness'],
        "witness_sec_id"             => $_POST['witness_sec'],
        "entonox"                    => $_POST['entonox'],
        "oxygen"                     => $_POST['oxygen'],
        "treatment_provided"         => $_POST['treatment_provided'],
        "recommended_advice"         => $_POST['recommended_advice'],
        "dr_id"                      => $_POST['dr'],
        "referral_outcome_id"        => $_POST['referral_outcome'],
        "additional_notes"           => $_POST['additional_notes'],
        "signature_id"               => $_POST['signature_id']
    );
    if ($_POST['equipment_source'] == "rental") {
        $aData['rental_source'] = $_POST['rental_source'];
    }
    if ($_POST['activity'] == "1" || $_POST['activity'] == "2" || $_POST['activity'] == "4" || $_POST['activity'] == "7") {
        $aData['ability'] = $_POST['ability'];
    }
    if ($_POST['activity'] == "1") {
        $aData['bindings_release'] = $_POST['bindings_release'];
    }
    if ($_POST['mtbb_employee'] == "1") {
        $aData['employee_on_duty'] = $_POST['employee_on_duty'];
    }
    if ($_POST['activity'] == "2") {
        $aData['sb_stance'] = $_POST['sb_stance'];
    }
    if ($_POST['spinal_injury'] == 1) {
        $aData['spinal_comment'] = $_POST['spinal_comment'];
    }
    if ($_POST['loc'] == 1) {
        $aData['loc_comment'] = $_POST['loc_comment'];
    }
    if ($_POST['penthrane'] == 3) {
        $p3mlTime = $_POST['3ml_time_hours'].":".$_POST['3ml_time_minutes'];
        $aData['penthrane_3ml_time'] = $p3mlTime;
    }
    if ($_POST['penthrane'] == 6) {
        $p3mlTime = $_POST['3ml_time_hours'].":".$_POST['3ml_time_minutes'];
        $p6mlTime = $_POST['6ml_time_hours'].":".$_POST['6ml_time_minutes'];
        $aData['penthrane_3ml_time'] = $p3mlTime;
        $aData['penthrane_6ml_time'] = $p6mlTime;
    }
    if ($_POST['entonox'] == 1) {
        $entonoxTime = $_POST['entonox_start_time_hours'].":".$_POST['entonox_start_time_minutes'];
        $aData['entonox_start_time'] = $entonoxTime;
        $aData['entonox_start_amount'] = $_POST['entonox_start_amount'];
        $aData['entonox_end_amount'] = $_POST['entonox_end_amount'];
    }
    if ($_POST['oxygen'] == 1) {
        $oxygenTime = $_POST['oxygen_start_time_hours'].":".$_POST['oxygen_start_time_minutes'];
        $aData['oxygen_start_time'] = $oxygenTime;
        $aData['oxygen_flow_rate'] = $_POST['oxygen_flow_rate'];
    }
    if ($_POST['referral_outcome'] == 6) {
        $ambulanceCallTime = $_POST['ambulance_call_time_hours'].":".$_POST['ambulance_call_time_minutes'];
        $ambulanceArrivalTime = $_POST['ambulance_arrival_time_hours'].":".$_POST['ambulance_arrival_time_minutes'];
        $ambulanceDepartureTime = $_POST['ambulance_departure_time_hours'].":".$_POST['ambulance_departure_time_minutes'];
        $aData['ambulance_mode'] = $_POST['ambulance_mode'];
        $aData['ambulance_call_time'] = $ambulanceCallTime;
        $aData['ambulance_arrival_time'] = $ambulanceArrivalTime;
        $aData['ambulance_departure_time'] = $ambulanceDepartureTime;
        $aData['ambulance_destination_id'] = $_POST['ambulance_destination'];
    }
    if ($_POST['parent-signature'] == "on") {
        $aData['parent_signature'] = 1;
    } else {
        $aData['parent_signature'] = 0;
    }
    if ($_POST['unable-to-sign'] == "on") {
        $aData['unable_to_sign'] = 1;
        $aData['unable_reason'] = $_POST['unable_reason'];
    } else {
        $aData['unable_to_sign'] = 0;
    }

    if ($_REQUEST['id'] && $incidentData['incident_timestamp'] > date("Y-m-d H:i:s", time() - 172800)) {
        $Incident->updateById($_REQUEST['id'], $aData);

        $SQL = "DELETE FROM penthrane_stock WHERE incident_id = ".$_REQUEST['id']." ";
        $result = $DB->sql_query($SQL);
        if ($_POST['penthrane'] == 3 ) {
            $penthrane_data = array(
                "operation"     => 3, // 1 - add, 2 - remove, 3 - used
                "qty"			=> 1,
                "date"			=> $timestamp,
                "user_id"  		=> $_POST['signature_id'],
                "comment"		=> "Used In Incident ". $incidentId,
                "incident_id"   => intval($incidentId)
            );
        }
        if ($_POST['penthrane'] == 6 ) {
            $penthrane_data = array(
                "operation"     => 3, // 1 - add, 2 - remove, 3 - used
                "qty"			=> 2,
                "date"			=> $timestamp,
                "user_id"  		=> $_POST['signature_id'],
                "comment"		=> "Used In Incident ". $incidentId,
                "incident_id"   => intval($incidentId)
            );
        }

        $SQL = "DELETE FROM incident_patrollers WHERE incident_id = ".$_REQUEST['id']." ";
        $result = $DB->sql_query($SQL);

        for ($i = 0; $i < count($_POST['patroller']); $i++) {
            $patrollerData = array(
                "incident_id" => $_REQUEST['id'],
                "patroller_id" => $_POST['patroller'][$i],
                "role_id" => $_POST['patroller_role'][$i]
            );
            $recId = $Incident->addPatroller($patrollerData);
        }

        $SQL = "DELETE FROM incident_phones WHERE incident_id = ".$_REQUEST['id']." ";
        $result = $DB->sql_query($SQL);

        for ($i = 0; $i < count($_POST['phone']); $i++) {
            $phoneData = array(
                "incident_id" => $_REQUEST['id'],
                "phone" => $_POST['phone'][$i]
            );
            $recId = $Incident->addPhone($phoneData);
        }

        $SQL = "DELETE FROM incident_equipment WHERE incident_id = ".$_REQUEST['id']." ";
        $result = $DB->sql_query($SQL);

        for ($i = 0; $i < count($_POST['equipment']); $i++) {
            $equipmentData = array(
                "incident_id" => $_REQUEST['id'],
                "equipment_id" => $_POST['equipment'][$i]
            );
            $recId = $Incident->addEquipment($equipmentData);
        }

        $equipmentLeft = $Incident->getIncidentLeftEquipment($_REQUEST['id']);
        $oldEquip = array();
        $oldReady = array();
        foreach ($equipmentLeft as $equip) {
            $oldEquip[] = $equip['equipment_id'];
            $oldReady[$equip['equipment_id']] = $equip['id'];
        }
        $newEquip = $_POST['equipment-left'];
        if (count($oldEquip) > 0 || count($newEquip) > 0) {
            $equipToRemove = array_diff($oldEquip, $newEquip);
            $equipToAdd = array_diff($newEquip, $oldEquip);
            foreach ($equipToRemove as $equip) {
                $recId = $oldReady[$equip];
                $Equipment->deleteManagementById($recId);
            }
            foreach ($equipToAdd as $equip) {
                $managementData = array(
                    "incident_id"   => $_REQUEST['id'],
                    "date"          => $thisdate,
                    "equipment_id"  => $equip,
                    "destination"   => $_POST['ambulance_destination'],
                    "return"        => '0'
                );
                $managementId = $Equipment->addManagement($managementData);
            }
        }



        $SQL = "DELETE FROM injures WHERE incident_id = ".$_REQUEST['id']." ";
        $result = $DB->sql_query($SQL);

        for ($i = 0; $i < count($_POST['injury_location']); $i++) {
            $injuryData = array(
                "incident_id"   => $_REQUEST['id'],
                "location_id"   => $_POST['injury_location'][$i],
                "side"          => $_POST['injury_side'][$i],
                "category_id"   => $_POST['injury_category'][$i],
                "type_id"       => $_POST['injury_type'][$i],
                "comment"       => $_POST['comment'][$i],
            );
            $injuryId = $Injury->add($injuryData);
        }

        $SQL = "DELETE FROM incident_vitails WHERE incident_id = ".$_REQUEST['id']." ";
        $result = $DB->sql_query($SQL);

        for ($i = 0; $i < count($_POST['vitail_time_hours']); $i++) {
            $vitalTime = $_POST['vitail_time_hours'][$i].":".$_POST['vitail_time_minutes'][$i];
            $vitailsData = array(
                "incident_id"       => $_REQUEST['id'],
                "time"              => $vitalTime,
                "gcs"              => $_POST['gcs'][$i],
                "pupils"              => $_POST['pupils'][$i],
                "bp"              => $_POST['bp'][$i],
                "respiration"        => $_POST['respiration'][$i],
                "pulse"              => $_POST['pulse'][$i],
                "skin"              => $_POST['skin'][$i],
                "temp"              => $_POST['temp'][$i],
                "o2"              => $_POST['o2'][$i]
            );
            $vitailId = $Incident->addVitail($vitailsData);
        }

        for ($i = 0; $i < count ($_FILES['evidence']['name']); $i++) {
            if ($_FILES['evidence']['size'][$i] > 0) {
                $filename = $_FILES['evidence']['tmp_name'][$i];
                if(is_uploaded_file($filename)) {
                    $evidenceData = array(
                        "incident_id"   => $incidentId,
                        "file_name"     => $_FILES['evidence']['name'][$i]
                    );
                    $fileId = $DB->addData("incident_evidence", $evidenceData);
                    move_uploaded_file($filename, 'evidence/'.$fileId."_".$_FILES['evidence']['name'][$i]);
                }

            }
        }
    }

    if (!$_REQUEST['id']) {
        $incidentId = $Incident->add($aData);

        if ($_POST['penthrane'] == 3 ) {
            $penthrane_data = array(
                "operation"     => 3, // 1 - add, 2 - remove, 3 - used
                "qty"			=> 1,
                "date"			=> $timestamp,
                "user_id"  		=> $_POST['signature_id'],
                "comment"		=> "Used In Incident ". $incidentId,
                "incident_id"   => intval($incidentId)
            );
        }

        if ($_POST['penthrane'] == 6 ) {
            $penthrane_data = array(
                "operation"     => 3, // 1 - add, 2 - remove, 3 - used
                "qty"			=> 2,
                "date"			=> $timestamp,
                "user_id"  		=> $_POST['signature_id'],
                "comment"		=> "Used In Incident ". $incidentId,
                "incident_id"   => intval($incidentId)
            );
        }
        if ($penthrane_data) {
            $Penthrane->add($penthrane_data);
        }

        $signatures = array();

        if ($_POST['entonox'] == 1 && $_POST['witness'] > 0) {
            if (count($_POST['witness_signature']) > 0) {
                $json = $_POST['witness_signature'];

                $img = sigJsonToImage($json);

                imagepng($img, 'signatures/ws.png');

                $wscontent = base64_encode(file_get_contents('signatures/ws.png'));
                $signatures['ws'] = $wscontent;

                imagedestroy($img);
            }
        }

        if ($_POST['entonox'] == 1 && $_POST['witness_sec'] > 0) {
            if (count($_POST['witness_sec_signature']) > 0) {
                $json = $_POST['witness_sec_signature'];

                $img = sigJsonToImage($json);

                imagepng($img, 'signatures/wss.png');

                $wsscontent = base64_encode(file_get_contents('signatures/wss.png'));
                $signatures['wss'] = $wsscontent;

                imagedestroy($img);
            }
        }

        if ($_POST['signature_id'] > 0) {
            if( $_POST['patroller_signature'] != "") {
                $json = $_POST['patroller_signature'];

                $img = sigJsonToImage($json);

                imagepng($img, 'signatures/ps.png');

                $pscontent = base64_encode(file_get_contents('signatures/ps.png'));
                $signatures['ps'] = $pscontent;

                imagedestroy($img);
            }
        }

        if ($_POST['dr'] > 0) {
            if (count($_POST['dr_signature']) > 0) {
                $json = $_POST['dr_signature'];

                $img = sigJsonToImage($json);

                imagepng($img, 'signatures/drs.png');

                $drscontent = base64_encode(file_get_contents('signatures/drs.png'));
                $signatures['drs'] = $drscontent;

                imagedestroy($img);
            }
        }

        if ($_POST['c_signature'] != "") {
            $json = $_POST['c_signature'];

            $img = sigJsonToImage($json);

            imagepng($img, 'signatures/cs.png');

            $cscontent = base64_encode(file_get_contents('signatures/cs.png'));
            $signatures['cs'] = $cscontent;

            imagedestroy($img);
        }

        if (count($signatures)) {
            echo "update<br/>";
            echo "<pre>";
            print_r($signatures);
            echo "</pre>";
            $Incident->updateById($incidentId, $signatures);
        }

        for ($i = 0; $i < count($_POST['patroller']); $i++) {
            $patrollerData = array(
                "incident_id" => $incidentId,
                "patroller_id" => $_POST['patroller'][$i],
                "role_id" => $_POST['patroller_role'][$i]
            );
            $recId = $Incident->addPatroller($patrollerData);
        }

        for ($i = 0; $i < count($_POST['phone']); $i++) {
            $phoneData = array(
                "incident_id" => $incidentId,
                "phone" => $_POST['phone'][$i]
            );
            $recId = $Incident->addPhone($phoneData);
        }

        for ($i = 0; $i < count($_POST['equipment']); $i++) {
            $equipmentData = array(
                "incident_id" => $incidentId,
                "equipment_id" => $_POST['equipment'][$i]
            );
            $recId = $Incident->addEquipment($equipmentData);
        }

        for ($i = 0; $i < count($_POST['equipment-left']); $i++) {
            $managementData = array(
                "incident_id"   => $incidentId,
                "date"          => $thisdate,
                "equipment_id"  => $_POST['equipment-left'][$i],
                "destination"   => $_POST['ambulance_destination'],
                "return"        => '0'
            );
            $managementId = $Equipment->addManagement($managementData);
        }

        for ($i = 0; $i < count ($_FILES['evidence']['name']); $i++) {
            if ($_FILES['evidence']['size'][$i] > 0) {
                $filename = $_FILES['evidence']['tmp_name'][$i];
                if(is_uploaded_file($filename)) {
                    $evidenceData = array(
                        "incident_id"   => $incidentId,
                        "file_name"     => $_FILES['evidence']['name'][$i]
                    );
                    $fileId = $DB->addData("incident_evidence", $evidenceData);
                    move_uploaded_file($filename, 'evidence/'.$fileId."_".$_FILES['evidence']['name'][$i]);
                }

            }
        }

        for ($i = 0; $i < count($_POST['injury_location']); $i++) {
            $injuryData = array(
                "incident_id"   => $incidentId,
                "location_id"   => $_POST['injury_location'][$i],
                "side"          => $_POST['injury_side'][$i],
                "category_id"   => $_POST['injury_category'][$i],
                "type_id"       => $_POST['injury_type'][$i],
                "comment"       => $_POST['comment'][$i],
            );
            $injuryId = $Injury->add($injuryData);
        }

        for ($i = 0; $i < count($_POST['vitail_time_hours']); $i++) {
            $vitalTime = $_POST['vitail_time_hours'][$i].":".$_POST['vitail_time_minutes'][$i];
            $vitailsData = array(
                "incident_id"       => $incidentId,
                "time"              => $vitalTime,
                "gcs"              => $_POST['gcs'][$i],
                "pupils"              => $_POST['pupils'][$i],
                "bp"              => $_POST['bp'][$i],
                "respiration"        => $_POST['respiration'][$i],
                "pulse"              => $_POST['pulse'][$i],
                "skin"              => $_POST['skin'][$i],
                "temp"              => $_POST['temp'][$i],
                "o2"              => $_POST['o2'][$i]
            );
            $vitailId = $Incident->addVitail($vitailsData);
        }
    }
    if ($_REQUEST['id']) {

        $signatures = array();

        if ($_POST['entonox'] == 1 && $_POST['witness'] > 0 && $_POST['witness_signature'] && is_null($incidentData['ws'])) {
            if (count($_POST['witness_signature']) > 0) {
                $json = $_POST['witness_signature'];

                $img = sigJsonToImage($json);

                imagepng($img, 'signatures/ws.png');

                $wscontent =  file_get_contents('signatures/ws.png');
                $signatures['ws'] = $wscontent;

                imagedestroy($img);
            }
        }

        if ($_POST['entonox'] == 1 && $_POST['witness_sec'] > 0 && $_POST['witness_sec_signature'] && is_null($incidentData['wss'])) {
            if (count($_POST['witness_sec_signature']) > 0) {
                $json = $_POST['witness_sec_signature'];

                $img = sigJsonToImage($json);

                imagepng($img, 'signatures/wss.png');

                $wsscontent =  file_get_contents('signatures/wss.png');
                $signatures['wss'] = $wsscontent;

                imagedestroy($img);
            }
        }

        if ($_POST['dr'] > 0 && $_POST['dr_signature'] && is_null($incidentData['drs']) && $incidentData['dr_id'] < 1) {
            if (count($_POST['dr_signature']) > 0) {
                $json = $_POST['dr_signature'];

                $img = sigJsonToImage($json);

                imagepng($img, 'signatures/drs.png');

                $drscontent = base64_encode(file_get_contents('signatures/drs.png'));
                $signatures['drs'] = $drscontent;

                imagedestroy($img);
            }
        }

        if ($_POST['signature_id'] > 0 && $_POST['patroller_signature'] &&  is_null($incidentData['ps'])) {
            if ($_POST['patroller_signature'] != "") {
                $json = $_POST['patroller_signature'];

                $img = sigJsonToImage($json);

                imagepng($img, 'signatures/ps.png');

                $pscontent = base64_encode(file_get_contents('signatures/ps.png'));
                $signatures['ps'] = $pscontent;

                imagedestroy($img);
            }
        }
        if ($_POST['c_signature'] != "" && is_null($incidentData['cs'])) {
            $json = $_POST['c_signature'];

            $img = sigJsonToImage($json);

            imagepng($img, 'signatures/cs.png');

            $cscontent = base64_encode(file_get_contents('signatures/cs.png'));
            $signatures['cs'] = $cscontent;

            imagedestroy($img);
        }
        if ($_REQUEST['id'] && $incidentData['incident_timestamp'] > date("Y-m-d H:i:s", time() - 172800) && count($signatures)) {
            $Incident->updateById($_REQUEST['id'], $signatures);
        }
    }

    header("location:/incidents.php");
}

page_header(3);

?>

<div id="incident-form">
<form method="post" id="incident_form" enctype="multipart/form-data" data-id="<?php echo $_REQUEST['id'];?>" data-submitted="<?php echo $incidentData['submit'];?>"
    <?
    if ($_REQUEST['id']) {
        ?>
        id="incident<?=$_REQUEST['id'];?>"
    <?
    } else {
        ?>
        id="incidentForm"
    <?
    }
    ?>
    >
    <input type="hidden" id="incident_id" name="id" value="<?php echo $_REQUEST['id'];?>">
    <?
    if ($_REQUEST['id']) {
        ?>
        <input type="hidden" id="incidentId" name="incidentId" value="<?=$_REQUEST['id'];?>">
    <?
    }
    ?>
   <div id="tabs">
       <ul>
           <li>
               <a href="#patient-tab">Casualty details</a>
           </li>
           <li>
               <a href="#incident-tab">Incident Details</a>
           </li>
           <li>
               <a href="#injures-tab">Injuries / Illness / Vitals</a>
           </li>
           <li>
               <a href="#treatment-tab">Treatment / Handover</a>
           </li>
       </ul>

       <div id="patient-tab">
           <div class="incident-form">
               <div class="incident">
                   <div id="first_name_l" class="label">
                       First Name:
                   </div>
                   <div class="input">
                       <input id="first_name" type="text" name="first_name" value="<?=$incidentData['first_name'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div id="surname_l" class="label">
                       Surname:
                   </div>
                   <div class="input">
                       <input id="surname" type="text" name="last_name" value="<?=$incidentData['last_name'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div class="label">
                       MtBB Employee:
                   </div>
                   <div class="input">
                       <select name="mtbb_employee" id="mtbb_employee">
                           <option value="0"
                               <?
                               if ($incidentData['mtbb_employee'] == 0) {
                                   ?> selected="selected"<?
                               }
                               ?>
                               >No</option>
                           <option value="1"
                               <?
                               if ($incidentData['mtbb_employee'] == 1) {
                                   ?> selected="selected"<?
                               }
                               ?>
                               >Yes</option>
                       </select>
                   </div>
               </div>
               <div class="incident" id="employee_on_duty">
                   <div class="label">
                       Employee On Duty:
                   </div>
                   <div class="input">
                       <select name="employee_on_duty">
                           <option value="0"
                               <?
                               if ($incidentData['employee_on_duty'] == 0) {
                                   ?> selected="selected"<?
                               }
                               ?>
                               >No</option>
                           <option value="1"
                               <?
                               if ($incidentData['employee_on_duty'] == 1) {
                                   ?> selected="selected"<?
                               }
                               ?>
                               >Yes</option>
                       </select>
                   </div>
               </div>
               <div class="incident">
                   <div id="gender_l" class="label">
                       Gender:
                   </div>
                   <div class="input">
                       <select id="gender" name="gender">
                           <option></option>
                           <option value="1"
                               <?
                               if ($incidentData['gender'] == 1) {
                                   ?> selected="selected"<?
                               }
                               ?>
                               >Male</option>
                           <option value="2"
                               <?
                               if ($incidentData['gender'] == 2) {
                                   ?> selected="selected"<?
                               }
                               ?>
                               >Female</option>
                       </select>
                   </div>
               </div>
               <div class="incident">
                   <div id="street_l" class="label">
                       Street Address:
                   </div>
                   <div class="input">
                       <input id="street" type="text" name="street" value="<?=$incidentData['street'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div id="city_l" class="label">
                       City:
                   </div>
                   <div class="input">
                       <input id="city" type="text" name="city" value="<?=$incidentData['city'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div id="state_l" class="label">
                       State:
                   </div>
                   <div class="input">
                       <input id="state" type="text" name="state" value="<?=$incidentData['state'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div id="country_l" class="label">
                       Country:
                   </div>
                   <div class="input">
                       <input id="country" type="text" name="country" value="<?
                       if ($_REQUEST['id']) {
                           ?><?=$incidentData['country'];?><?
                       } else {
                           ?>Australia<?
                       }
                       ?>">
                   </div>
               </div>
               <div class="incident">
                   <div id="postcode_l" class="label">
                       Postcode:
                   </div>
                   <div class="input">
                       <input id="postcode" type="text" name="postcode" value="<?=$incidentData['postcode'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div class="phones" id="phones">
                           <?
                           if($_REQUEST['id']) {
                               $phones = $Incident->getIncidentPhones($_REQUEST['id']);
                               foreach ($phones as $phone) {
                                   ?>
                                   <div class="incident" id="one_phone">
                                       <div class="label">
                                           Phone:
                                       </div>
                                       <div class="input">
                                           <input class="i_phone" type="text" name="phone[]" value="<?=$phone['phone'];?>">
                                           <div class="remove-phone"><a>-</a></div>
                                       </div>
                                   </div>
                                   <?
                               }
                           } else {
                               ?>
                               <div class="incident" id="one_phone">
                                   <div class="label">
                                       Phone:
                                   </div>
                                   <div class="input">
                                       <input class="i_phone" type="text" name="phone[]">
                                       <div class="remove-phone"><a>-</a></div>
                                   </div>
                               </div>
                           <?
                           }
                           ?>
                   </div>
                   <div id="add_phone" class="add_multiple"><a>+</a></div>
               </div>
               <div class="incident">
                   <div class="label">
                       DOB:
                   </div>
                   <div class="input">
                       <?
                       $date = explode("-", $incidentData['dob']);
                       ?>
                       <select id="dob_d" class="hours" name="dob_d">
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
                       </select>-<select id="dob_m" class="hours" name="dob_m">
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
                       </select>-<select id="dob_y" class="hours" name="dob_y">
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
                   </div>
               </div>
               <div class="incident">
                   <div class="label">
                       Age:
                   </div>
                   <div class="input">
                       <span id="age-l"><?=$incidentData['age'];?></span>
                       <input id="age" name="age" type="hidden" value="<?=$incidentData['age'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div id="allergies_l" class="label">
                       Allergies:
                   </div>
                   <div class="input">
                       <input id="allergies" type="text" name="allergies" value="<?=$incidentData['allergies'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div id="medications_l" class="label">
                       Medications:
                   </div>
                   <div class="input">
                       <input id="medications" type="text" name="medications" value="<?=$incidentData['medications'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div id="last_meals_l" class="label">
                       Last meals:
                   </div>
                   <div class="input">
                       <input id="last_meals" type="text" name="last_meals" value="<?=$incidentData['last_meals'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div id="last_bath_l" class="label">
                       Last bathroom use:
                   </div>
                   <div class="input">
                       <input id="last_bath" type="text" name="last_bathroom" value="<?=$incidentData['last_bathroom'];?>">
                   </div>
               </div>
               <div class="incident">
                   <div id="i_history_l" class="label">
                       Previous Medical History:
                   </div>
                   <div class="input">
                       <textarea id="i_history" name="history"><?=$incidentData['history'];?></textarea>
                   </div>
               </div>
           </div>
       </div>
       <div id="incident-tab">
           <div class="incident-form">
               <div class="incident">
                   <div class="label">
                       Incident Date:
                   </div>
                   <div class="input">
                       <?
                       $date = explode("-", $incidentData['incident_date']);
                       ?>
                       <select id="incident_date_d" class="hours" name="incident_date_d">
                           <?
                           for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                               ?>
                               <option value="<?=sprintf("%02d", $i);?>"
                                   <?
                                   if ($_REQUEST['id'] && $date['2'] == sprintf("%02d", $i)) {
                                       ?>
                                       selected="selected"
                                   <?
                                   } else {
                                       if (!$_REQUEST['id'] && date("d") == sprintf("%02d", $i)) {
                                           ?>
                                           selected="selected"
                                       <?
                                       }
                                   }
                                   ?>
                                   >
                                   <?=sprintf("%02d", $i);?>
                               </option>
                           <?
                           }
                           ?>
                       </select>-<select id="incident_date_m" class="hours" name="incident_date_m">
                           <?
                           for ($i = 1; $i <= 12; $i++) {
                               ?>
                               <option value="<?=sprintf("%02d", $i);?>"
                                   <?
                                   if ($_REQUEST['id'] && $date['1'] == sprintf("%02d", $i)) {
                                       ?>
                                       selected="selected"
                                   <?
                                   } else {
                                       if (!$_REQUEST['id'] && date("m") == sprintf("%02d", $i)) {
                                           ?>
                                           selected="selected"
                                       <?
                                       }
                                   }
                                   ?>
                                   >
                                   <?=sprintf("%02d", $i);?>
                               </option>
                           <?
                           }
                           ?>
                       </select>-<select id="incident_date_y" class="hours" name="incident_date_y">
                           <?
                           for ($i = date("Y") - 90; $i <= date("Y") + 10; $i++) {
                               ?>
                               <option value="<?=sprintf("%02d", $i);?>"
                                   <?
                                   if ($_REQUEST['id'] && $date['0'] == sprintf("%02d", $i)) {
                                       ?>
                                       selected="selected"
                                   <?
                                   } else {
                                       if (!$_REQUEST['id'] && date("Y") == sprintf("%02d", $i)) {
                                           ?>
                                           selected="selected"
                                       <?
                                       }
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
               </div>
               <div class="incident">
                   <div class="label">
                       Incident Time:
                   </div>
                   <div class="input">
                       <?
                       if ($_REQUEST['id']) {
                           $time = explode(":", $incidentData['incident_time']);
                       }
                       ?>
                       <select class="hours" id="incident_time_hours" name="incident_time_hours">
                           <?
                           for ($i = 0; $i < 24; $i++) {
                               ?>
                               <option value="<?=sprintf("%02d", $i);?>"
                                   <?
                                   if ($_REQUEST['id'] && $time['0'] == sprintf("%02d", $i)) {
                                       ?>
                                       selected="selected"
                                   <?
                                   } else {
                                       if (!$_REQUEST['id'] && date("H") == sprintf("%02d", $i)) {
                                           ?>
                                           selected="selected"
                                       <?
                                       }
                                   }
                                   ?>
                                   >
                                   <?=sprintf("%02d", $i);?>
                               </option>
                           <?
                           }
                           ?>
                       </select>:<select class="hours" id="incident_time_minutes" name="incident_time_minutes">
                           <?
                           for ($i = 0; $i < 60; $i++) {
                               ?>
                               <option value="<?=sprintf("%02d", $i);?>"
                                   <?
                                   if ($_REQUEST['id'] && $time['1'] == sprintf("%02d", $i)) {
                                       ?>
                                       selected="selected"
                                   <?
                                   } else {
                                       if (!$_REQUEST['id'] && date("i") == sprintf("%02d", $i)) {
                                           ?>
                                           selected="selected"
                                       <?
                                       }
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
               </div>
               <div class="incident">
                   <div class="label">
                       Patroller Arrival Time:
                   </div>
                   <div class="input">
                       <?
                       if ($_REQUEST['id']) {
                           $time = explode(":", $incidentData['patroller_arrival_time']);
                       }
                       ?>
                       <select class="hours" id="patroller_arrival_time_hours" name="patroller_arrival_time_hours">
                           <?
                           for ($i = 0; $i < 24; $i++) {
                               ?>
                               <option value="<?=sprintf("%02d", $i);?>"
                                   <?
                                   if ($_REQUEST['id'] && $time['0'] == sprintf("%02d", $i)) {
                                       ?>
                                       selected="selected"
                                   <?
                                   } else {
                                       if (!$_REQUEST['id'] && date("H") == sprintf("%02d", $i)) {
                                           ?>
                                           selected="selected"
                                       <?
                                       }
                                   }
                                   ?>
                                   >
                                   <?=sprintf("%02d", $i);?>
                               </option>
                           <?
                           }
                           ?>
                       </select>:<select class="hours" id="patroller_arrival_time_minutes" name="patroller_arrival_time_minutes">
                           <?
                           for ($i = 0; $i < 60; $i++) {
                               ?>
                               <option value="<?=sprintf("%02d", $i);?>"
                                   <?
                                   if ($_REQUEST['id'] && $time['1'] == sprintf("%02d", $i)) {
                                       ?>
                                       selected="selected"
                                   <?
                                   } else {
                                       if (!$_REQUEST['id'] && date("i") == sprintf("%02d", $i)) {
                                           ?>
                                           selected="selected"
                                       <?
                                       }
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
               </div>
               <div class="incident">
                   <div id="i_description_l" class="label">
                       Description of Incident:
                   </div>
                   <div class="input">
                       <textarea id="i_description" name="description"><?=$incidentData['description'];?></textarea>
                   </div>
               </div>
               <div class="incident">
                   <div id="helmet_worn_l" class="label">
                       Helmet Worn:
                   </div>
                   <div class="input">
                       <select id="helmet_worn" name="helmet">
                           <option></option>
                           <option value="1"
                               <?
                               if ($incidentData['helmet'] == "1"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >Yes</option>
                           <option value="0"
                               <?
                               if ($incidentData['helmet'] == "0"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >No</option>
                           <option value="2"
                               <?
                               if ($incidentData['helmet'] == "2"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >NA</option>
                       </select>
                   </div>
               </div>
               <div class="incident">
                   <div id="activity_l" class="label">
                       Activity:
                   </div>
                   <div class="input">
                       <select name="activity" id="activity">
                           <option></option>
                           <?
                           foreach($activityList as $activity) {
                               ?>
                               <option value="<?=$activity['id'];?>"
                                   <?
                                   if ($incidentData['activity_id'] == $activity['id']) {
                                       ?>
                                       selected="selected"
                                   <?
                                   }
                                   ?>
                                   >
                                   <?=$activity['name'];?>
                               </option>
                           <?
                           }
                           ?>
                       </select>
                   </div>
               </div>
               <div id="ability" class="incident">
                   <div id="i_ability_l" class="label">
                       Ability:
                   </div>
                   <div class="input">
                       <select id="i_ability" name="ability">
                           <option></option>
                           <option value="Novice"
                               <?
                               if ($incidentData['ability'] == "Novice") {
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >
                               Novice
                           </option>
                           <option value="Beginner"
                               <?
                               if ($incidentData['ability'] == "Beginner") {
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >
                               Beginner
                           </option>
                           <option value="Intermediate"
                               <?
                               if ($incidentData['ability'] == "Intermediate") {
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >
                               Intermediate
                           </option>
                           <option value="Advanced"
                               <?
                               if ($incidentData['ability'] == "Advanced") {
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >
                               Advanced
                           </option>
                       </select>
                   </div>
               </div>
               <div class="incident" id="bindings_release">
                   <div id="i_bindings_release_l" class="label">
                       Bindings Release:
                   </div>
                   <div class="input">
                       <select id="i_bindings_release" name="bindings_release">
                           <option value="1"
                               <?
                               if ($incidentData['bindings_release'] == "1"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >Yes</option>
                           <option value="0"
                               <?
                               if ($incidentData['bindings_release'] == "0"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >No</option>
                       </select>
                   </div>
               </div>
               <div class="incident">
                   <div id="equipment_source_l" class="label">
                       Equipment Source:
                   </div>
                   <div class="input">
                       <select name="equipment_source" id="equipment_source">
                           <option></option>
                           <option value="na"
                               <?
                               if ($incidentData['equipment_source'] == "na"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >NA</option>
                           <option value="own"
                               <?
                               if ($incidentData['equipment_source'] == "own"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >Own</option>
                           <option value="rental"
                               <?
                               if ($incidentData['equipment_source'] == "rental"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >Rental</option>
                           <option value="borrowed"
                               <?
                               if ($incidentData['equipment_source'] == "borrowed"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >Borrowed</option>
                       </select>
                   </div>
               </div>
               <div class="incident" id="rental_source">
                   <div id="i_rental_source_l" class="label">
                       Rental Source:
                   </div>
                   <div class="input">
                       <input id="i_rental_source" type="text" name="rental_source" value="<?=$incidentData['rental_source'];?>">
                   </div>
               </div>

               <div class="incident" id="sb_stance">
                   <div id="i_sb_stance_l" class="label">
                       Snowboard Stance:
                   </div>
                   <div class="input">
                       <select id="i_sb_stance" name="sb_stance">
                           <option value="Natural (Left Foot Forwards)"
                               <?
                               if ($incidentData['sb_stance'] == "Natural (Left Foot Forwards)"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >Natural (Left Foot Forwards)</option>
                           <option value="Goofy (Right Foot Forwards)"
                               <?
                               if ($incidentData['sb_stance'] == "Goofy (Right Foot Forwards)"){
                                   ?>
                                   selected="selected"
                               <?
                               }
                               ?>
                               >Goofy (Right Foot Forwards)</option>
                       </select>
                   </div>
               </div>
               <div class="incident">
                   <div id="weather_l" class="label">
                       Weather:
                   </div>
                   <div class="input">
                       <select id="weather" name="weather">
                           <option></option>
                           <?
                           foreach($weatherList as $weather) {
                               ?>
                               <option value="<?=$weather['id'];?>"
                                   <?
                                   if ($incidentData['weather_id'] == $weather['id']) {
                                       ?>
                                       selected="selected"
                                   <?
                                   }
                                   ?>
                                   >
                                   <?=$weather['name'];?>
                               </option>
                           <?
                           }
                           ?>
                       </select>
                   </div>
               </div>
               <div class="incident">
                   <div id="snow_l" class="label">
                       Snow:
                   </div>
                   <div class="input">
                       <select id="snow" name="snow">
                           <option></option>
                           <?
                           foreach($snowList as $snow) {
                               ?>
                               <option value="<?=$snow['id'];?>"
                                   <?
                                   if ($incidentData['snow_id'] == $snow['id']) {
                                       ?>
                                       selected="selected"
                                   <?
                                   }
                                   ?>
                                   >
                                   <?=$snow['name'];?>
                               </option>
                           <?
                           }
                           ?>
                       </select>
                   </div>
               </div>
               <div class="incident">
                   <div id="visibility_l" class="label">
                       Visibility:
                   </div>
                   <div class="input">
                       <select id="visibility" name="visibility">
                           <option></option>
                           <?
                           foreach($visibilityList as $visibility) {
                               ?>
                               <option value="<?=$visibility['id'];?>"
                                   <?
                                   if ($incidentData['visibility_id'] == $visibility['id']) {
                                       ?>
                                       selected="selected"
                                   <?
                                   }
                                   ?>
                                   >
                                   <?=$visibility['name'];?>
                               </option>
                           <?
                           }
                           ?>
                       </select>
                   </div>
               </div>
               <div class="incident">
                   <div id="lift_l" class="label">
                       Lift / Category:
                   </div>
                   <div class="input">
                       <select name="lift" id="lift">
                           <option></option>
                           <?
                           foreach ($liftList as $lift) {
                               ?>
                               <option value="<?=$lift['id'];?>"
                                   <?
                                   if ($incidentData['lift_id'] == $lift['id']) {
                                       ?>
                                       selected="selected"
                                   <?
                                   }
                                   ?>
                                   >
                                   <?=$lift['name'];?>
                               </option>
                           <?
                           }
                           ?>
                       </select>
                   </div>
               </div>
               <div class="incident">
                   <div id="run_l" class="label">
                       Trail / Location:
                   </div>
                   <div class="input">
                       <select id="run" name="run">
                           <option></option>
                           <?
                           foreach ($runList as $run) {
                               ?>
                               <option class="runs runs<?=$run['lift_id'];?>" value="<?=$run['id'];?>"
                                   <?
                                   if ($incidentData['run_id'] == $run['id']) {
                                       ?>
                                       selected="selected"
                                   <?
                                   }
                                   ?>
                                   >
                                   <?=$run['name'];?>
                               </option>
                           <?
                           }
                           ?>
                       </select>
                   </div>
               </div>
               <div class="incident">
                   <div id="incident_coordinates_l" class="label">
                       Map Coordinates:
                   </div>
                   <div class="input">
                       <input id="incident_coordinates" type="text" data-toggle="modal" data-target="#mapModal" name="map_coordinates" value="<?=$incidentData['map_coordinates'];?>">
                   </div>

                   <!-- Modal -->
                   <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                       <div class="modal-dialog">
                           <div class="modal-content">
                               <div class="modal-header">
                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                   <h4 class="modal-title" id="myModalLabel">Mt BAW BAW Map</h4>
                               </div>
                               <div class="modal-body map-container">
                                       <div id="GMapContainer"></div>
                                   </div>
                               <div class="modal-footer">
                                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
               <div class="incident">
                   <div class="label">
                       Patrollers:
                   </div>
                   <div class="input  table-responsive">
                       <table class="table table-bordered" id="patrollers">
                           <tr>
                               <th>Patroller</th>
                               <th>Role</th>
                               <th>&nbsp;</th>
                           </tr>
                           <?
                           if ($_REQUEST['id']) {
                               $ipatrollers = $Incident->getIncidentPatrollers($_REQUEST['id']);
                               foreach ($ipatrollers as $ipatroller) {
                                   ?>
                                   <tr id="one_patroller">
                                       <td>
                                           <select class="i_patrollers" name="patroller[]">
                                               <option></option>
                                               <?
                                               foreach($patrollersList as $patroller) {
                                                   ?>
                                                   <option value="<?=$patroller['user_id'];?>"
                                                       <?
                                                       if ($ipatroller['patroller_id'] == $patroller['user_id']) {
                                                           ?>
                                                           selected="selected"
                                                       <?
                                                       }
                                                       ?>
                                                       <?php if ($patroller['user_type'] == 5) {
                                                           ?>
                                                       class="ptrl-trainee"
                                                           <?php
                                                       }?>
                                                   >
                                                       <?=$patroller['name'];?>&nbsp;<?=$patroller['surname'];?>
                                                   </option>
                                               <?
                                               }
                                               ?>
                                           </select>
                                       </td>
                                       <td>
                                           <select class="i_roles" name="patroller_role[]">
                                               <option></option>
                                               <?
                                               foreach($rolesList as $role) {
                                                   ?>
                                                   <option value="<?=$role['id'];?>"
                                                       <?
                                                       if ($ipatroller['role_id'] == $role['id']) {
                                                           ?>
                                                           selected="selected"
                                                       <?
                                                       }
                                                       ?>
                                                       >
                                                       <?=$role['name'];?>
                                                   </option>
                                               <?
                                               }
                                               ?>
                                           </select>
                                       </td>
                                       <td><a class="remove-patroller">-</a></td>
                                   </tr>
                               <?
                               }
                           } else {
                               ?>
                               <tr id="one_patroller">
                                       <td>
                                           <select class="i_patrollers" name="patroller[]">
                                               <option></option>
                                               <?
                                               foreach($patrollersList as $patroller) {
                                                   if ($patroller['active'] == 1) {
                                                       ?>
                                                       <option value="<?=$patroller['user_id'];?>"
                                                       <?php if ($patroller['user_type'] == 5) {
                                                           echo 'class="ptrl-trainee"';
                                                       }?>
                                                       >
                                                           <?=$patroller['name'];?>&nbsp;<?=$patroller['surname'];?>
                                                       </option>
                                                   <?
                                                   }
                                               }
                                               ?>
                                           </select>
                                       </td>
                                       <td>
                                           <select class="i_roles" name="patroller_role[]">
                                               <option></option>
                                               <?
                                               foreach($rolesList as $role) {
                                                   ?>
                                                   <option value="<?=$role['id'];?>">
                                                       <?=$role['name'];?>
                                                   </option>
                                               <?
                                               }
                                               ?>
                                           </select>
                                       </td>
                                   <td><a class="remove-patroller">-</a></td>
                                   </tr>
                               <?
                           }
                           ?>
                       </table>
                       <div class="add_multiple" id="add_patroller"><a>+</a></div>
                   </div>
               </div>
               <?
               if ($_REQUEST['id']) {
                   $evidenceFiles = $Incident->getEvidence($_REQUEST['id']);
                   ?>
                   <div>
                       <div class="incident">
                           <div class="label">
                               Evidence:
                           </div>
                           <div class="input">
                               <?
                               if (count($evidenceFiles) > 0) {
                                   foreach ($evidenceFiles as $file) {
                                       ?>
                                   <?=$file['file_name'];?><br>
                                   <?
                                   }
                               } else {
                                   ?>No files added<?
                               }
                               ?>
                           </div>
                       </div>
                   </div>
               <?
               }
               ?>
               <div>
                   <div class="evidences" id="evidences">
                       <div class="incident" id="one_evidence">
                           <div class="incident">
                               <div class="label">
                                   Evidence:
                               </div>
                               <div class="input">
                                   <input type="file" name="evidence[]">
                               </div>
                           </div>
                       </div>
                   </div>
                   <div class="add_multiple" id="add_evidence"><a>+</a></div>
               </div>
           </div>
       </div>
        <div id="injures-tab">
            <div class="incident-form">
                <div class="incident">
                    <div id="symptoms_l" class="label">
                        Symptoms:
                    </div>
                    <div class="input">
                        <textarea id="symptoms" tabindex name="symptoms"><?=$incidentData['symptoms'];?></textarea>
                    </div>
                </div>
                <div class="incident">
                    <div id="spinal_injury_l" class="label">
                        Suspected Spinal Injury:
                    </div>
                    <div class="input">
                        <select name="spinal_injury" id="spinal_injury">
                            <option></option>
                            <option value="0"
                                <?
                                if ($incidentData['spinal_injury'] == "0") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                            >No</option>
                            <option value="1"
                                <?
                                if ($incidentData['spinal_injury'] == "1") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >Yes</option>
                        </select>
                    </div>
                </div>
                <div class="incident" id="spinal_comment">
                    <div id="i_spinal_comment_l" class="label">
                        Spinal Comment:
                    </div>
                    <div class="input">
                        <input id="i_spinal_comment" type="text" name="spinal_comment" value="<?=$incidentData['spinal_comment'];?>">
                    </div>
                </div>
                <div class="incident">
                    <div id="loc_l" class="label">
                        LOC:
                    </div>
                    <div class="input">
                        <select name="loc" id="loc">
                            <option></option>
                            <option value="0"
                                <?
                                if ($incidentData['loc'] == "0") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >No</option>
                            <option value="1"
                                <?
                                if ($incidentData['loc'] == "1") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >Yes</option>
                        </select>
                    </div>
                </div>
                <div class="incident" id="loc_comment">
                    <div id="i_loc_comment_l" class="label">
                        LOC Comment:
                    </div>
                    <div class="input">
                        <input id="i_loc_comment" type="text" name="loc_comment" value="<?=$incidentData['loc_comment'];?>">
                    </div>
                </div>
                <div class="incident table-responsive">
                    <div class="label">
                       Probable Injures:
                    </div>
                    <div class="input">
                        <div class="injures">
                            <table class="table table-bordered" id="injures">
                                <tr>
                                    <th>Location:</th>
                                    <th>Category:</th>
                                    <th>Injury:</th>
                                    <th>Comment:</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <?
                                if ($_REQUEST['id']) {
                                    $injures = $Incident->getIncidentInjures($_REQUEST['id']);
                                    $ki = 0;
                                    foreach ($injures as $injury) {
                                        $ki++;
                                        ?>
                                        <tr
                                            <?
                                            if ($ki == 1) {
                                                ?>id="one_injury"<?
                                            }
                                            if ($ki > 1) {
                                                ?>id="one_injury<?=$ki;?>"<?
                                            }
                                            ?>
                                             count="<?=$ki;?>">
                                            <td>
                                                <input type="hidden" class="location_id" name="injury_location[]" value="<?=$injury['location_id'];?>">
                                                <input type="hidden" class="location_side" name="injury_side[]" value="<?=$injury['side'];?>">
                                                <span class="location_name">
                                                    <?=$injuryLocationList[$injury['location_id']]['name'];?>
                                                    <?
                                                    if ($injury['side'] == 1) {
                                                        ?> L<?
                                                    }
                                                    if ($injury['side'] == 2) {
                                                        ?> R<?
                                                    }
                                                    ?>
                                                </span>
                                                <a data-toggle="modal" data-target="#locationModal" class="select-location" >Select Location</a>


                                            </td>
                                            <td>
                                                <select name="injury_category[]" class="injury_category" id="injury_category">
                                                    <option></option>
                                                    <?
                                                    foreach($injuryCategoriesList as $category) {
                                                        ?>
                                                        <option value="<?=$category['id'];?>"
                                                            <?
                                                            if ($injury['category_id'] == $category['id']) {
                                                                ?>
                                                                selected="selected"
                                                            <?
                                                            }
                                                            ?>
                                                            >
                                                            <?=$category['name'];?>
                                                        </option>
                                                    <?
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="injury_type[]" class="inj_type">
                                                    <option></option>
                                                    <?
                                                    foreach($injuryTypesList as $type) {
                                                        ?>
                                                        <option class="types types<?=$type['category_id'];?>" value="<?=$type['id'];?>"
                                                            <?
                                                            if ($injury['type_id'] == $type['id']) {
                                                                ?>
                                                                selected="selected"
                                                            <?
                                                            }
                                                            ?>
                                                            >
                                                            <?=$type['name'];?>
                                                        </option>
                                                    <?
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <textarea name="comment[]"><?=$injury['comment'];?></textarea>
                                            </td>
                                            <td>
                                                <a class="remove-injury">-</a>
                                            </td>
                                        </tr>
                                    <?
                                    }
                                } else {
                                    ?>
                                    <tr id="one_injury" count="1">
                                        <td>
                                            <input type="hidden" class="location_id" name="injury_location[]">
                                            <input type="hidden" class="location_side" name="injury_side[]" value="0">
                                            <span class="location_name"></span>
                                            <a data-toggle="modal" data-target="#locationModal" class="select-location">Select Location</a>

                                        </td>
                                        <td>
                                            <select name="injury_category[]" class="injury_category">
                                                <option></option>
                                                <?
                                                foreach($injuryCategoriesList as $category) {
                                                    ?>
                                                    <option value="<?=$category['id'];?>">
                                                        <?=$category['name'];?>
                                                    </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="type-tr">
                                            <select name="injury_type[]" class="inj_type">
                                                <option></option>
                                                <?
                                                foreach($injuryTypesList as $type) {
                                                    ?>
                                                    <option class="types types<?=$type['category_id'];?>" value="<?=$type['id'];?>">
                                                        <?=$type['name'];?>
                                                    </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <textarea name="comment[]"></textarea>
                                        </td>
                                        <td>
                                            <a class="remove-injury">-</a>
                                        </td>
                                    </tr>
                                <?
                                }
                                ?>
                            </table>
                            <div id="add_injury" count="1" class="add_multiple"><a>+</a></div>
                        </div>
                    </div>

                </div>

                <div class="vitails" id="vitails">
                    <?
                    if ($_REQUEST['id']) {
                        $vitals = $Incident->getIncidentVitals($_REQUEST['id']);
                        foreach ($vitals as $vital) {
                            ?>
                            <div class="incident" id="one_vital">
                                <div class="incident">
                                    <div class="label">
                                        Vital Time:
                                    </div>
                                    <div class="input">
                                        <?
                                        $time = explode(":", $vital['time']);
                                        ?>
                                        <select class="hours" name="vitail_time_hours[]">
                                            <?
                                            for ($i = 0; $i < 24; $i++) {
                                                ?>
                                                <option value="<?=sprintf("%02d", $i);?>"
                                                    <?
                                                    if ($time['0'] == sprintf("%02d", $i)) {
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
                                        </select>:<select class="hours" name="vitail_time_minutes[]">
                                            <?
                                            for ($i = 0; $i < 60; $i++) {
                                                ?>
                                                <option value="<?=sprintf("%02d", $i);?>"
                                                    <?
                                                    if ($time['1'] == sprintf("%02d", $i)) {
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
                                </div>
                                <div class="incident">
                                    <div class="label">
                                        GCS:
                                    </div>
                                    <div class="input">
                                        <input class="v_gcs" type="text" name="gcs[]" value="<?=$vital['gcs'];?>">
                                    </div>
                                </div>
                                <div class="incident">
                                    <div class="label">
                                        Pupils:
                                    </div>
                                    <div class="input">
                                        <input class="v_pupils" type="text" name="pupils[]" value="<?=$vital['pupils'];?>">
                                    </div>
                                </div>
                                <div class="incident">
                                    <div class="label">
                                        BP:
                                    </div>
                                    <div class="input">
                                        <input class="v_bp" type="text" name="bp[]" value="<?=$vital['bp'];?>">
                                    </div>
                                </div>
                                <div class="incident">
                                    <div class="label">
                                        Respiration:
                                    </div>
                                    <div class="input">
                                        <input class="v_respiration" type="text" name="respiration[]" value="<?=$vital['respiration'];?>">
                                    </div>
                                </div>
                                <div class="incident">
                                    <div class="label">
                                        Pulse:
                                    </div>
                                    <div class="input">
                                        <input class="v_pulse" type="text" name="pulse[]" value="<?=$vital['pulse'];?>">
                                    </div>
                                </div>
                                <div class="incident">
                                    <div class="label">
                                        Skin:
                                    </div>
                                    <div class="input">
                                        <input class="v_skin" type="text" name="skin[]" value="<?=$vital['skin'];?>">
                                    </div>
                                </div>
                                <div class="incident">
                                    <div class="label">
                                        Temp:
                                    </div>
                                    <div class="input">
                                        <input class="v_temp" type="text" name="temp[]" value="<?=$vital['temp'];?>">
                                    </div>
                                </div>
                                <div class="incident">
                                    <div class="label">
                                        O2:
                                    </div>
                                    <div class="input">
                                        <input class="v_otwo" type="text" name="o2[]" value="<?=$vital['o2'];?>">
                                    </div>
                                </div>
                                <div class="incident">
                                    <div class="label">
                                    </div>
                                    <div class="input">
                                        <a class="remove-vital">-</a>
                                    </div>
                                </div>
                            </div>
                        <?
                        }
                    } else {
                        ?>
                        <div class="incident" id="one_vital">
                            <div class="incident">
                                <div class="label">
                                    Vital Time:
                                </div>
                                <div class="input">
                                    <select class="hours" name="vitail_time_hours[]">
                                        <?
                                        for ($i = 0; $i < 24; $i++) {
                                            ?>
                                            <option value="<?=sprintf("%02d", $i);?>"
                                                <?
                                                if (date("H") == sprintf("%02d", $i)) {
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
                                    </select>:<select class="hours" name="vitail_time_minutes[]">
                                        <?
                                        for ($i = 0; $i < 60; $i++) {
                                            ?>
                                            <option value="<?=sprintf("%02d", $i);?>"
                                                <?
                                                if (date("i") == sprintf("%02d", $i)) {
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
                            </div>
                            <div class="incident">
                                <div class="label">
                                    GCS:
                                </div>
                                <div class="input">
                                    <input class="v_gcs" type="text" name="gcs[]">
                                </div>
                            </div>
                            <div class="incident">
                                <div class="label">
                                    Pupils:
                                </div>
                                <div class="input">
                                    <input class="v_pupils" type="text" name="pupils[]">
                                </div>
                            </div>
                            <div class="incident">
                                <div class="label">
                                    BP:
                                </div>
                                <div class="input">
                                    <input class="v_bp" type="text" name="bp[]">
                                </div>
                            </div>
                            <div class="incident">
                                <div class="label">
                                    Respiration:
                                </div>
                                <div class="input">
                                    <input class="v_respiration" type="text" name="respiration[]">
                                </div>
                            </div>
                            <div class="incident">
                                <div class="label">
                                    Pulse:
                                </div>
                                <div class="input">
                                    <input class="v_pulse" type="text" name="pulse[]">
                                </div>
                            </div>
                            <div class="incident">
                                <div class="label">
                                    Skin:
                                </div>
                                <div class="input">
                                    <input class="v_skin" type="text" name="skin[]">
                                </div>
                            </div>
                            <div class="incident">
                                <div class="label">
                                    Temp:
                                </div>
                                <div class="input">
                                    <input class="v_temp" type="text" name="temp[]">
                                </div>
                            </div>
                            <div class="incident">
                                <div class="label">
                                    O2:
                                </div>
                                <div class="input">
                                    <input class="v_otwo" type="text" name="o2[]">
                                </div>
                            </div>
                            <div class="incident">
                                <div class="label">
                                </div>
                                <div class="input">
                                    <a class="remove-vital">-</a>
                                </div>
                            </div>
                        </div>
                    <?
                    }
                    ?>
                </div>
                <div id="add_vitail" class="add_multiple"><a>+</a></div>
            </div>
        </div>
        <div id="treatment-tab">
            <div class="incident-form">
                <div class="incident">
                    <div id="transport_l" class="label">
                        Transport:
                    </div>
                    <div class="input">
                        <select id="transport" name="transport">
                            <option></option>
                            <?
                            foreach($transportList as $transport) {
                                ?>
                                <option value="<?=$transport['id'];?>"
                                    <?
                                    if ($incidentData['transport_id'] == $transport['id']) {
                                        ?>
                                        selected="selected"
                                    <?
                                    }
                                    ?>
                                    >
                                    <?=$transport['name'];?>
                                </option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="incident">
                    <div id="penthrane_l" class="label">
                        Penthrane:
                    </div>
                    <div class="input">
                        <select name="penthrane" id="penthrane">
                            <option></option>
                            <option value="0"
                                <?
                                if ($incidentData['penthrane'] == "0") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >NO</option>
                            <option value="3"
                                <?
                                if ($incidentData['penthrane'] == "3") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >3ml</option>
                            <option value="6"
                                <?
                                if ($incidentData['penthrane'] == "6") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >6ml</option>
                        </select>
                    </div>
                </div>
                <div class="incident" id="3ml_time">
                    <div class="label">
                        Penthrane 3ml Time:
                    </div>
                    <div class="input">
                        <?
                        if ($_REQUEST['id']) {
                            $time = explode(":", $incidentData['penthrane_3ml_time']);
                        }
                        ?>
                        <select class="hours" id="3ml_time_hours" name="3ml_time_hours">
                            <?
                            for ($i = 0; $i < 24; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['0'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("H") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
                                    }
                                    ?>
                                    >
                                    <?=sprintf("%02d", $i);?>
                                </option>
                            <?
                            }
                            ?>
                        </select>:<select class="hours" id="3ml_time_minutes" name="3ml_time_minutes">
                            <?
                            for ($i = 0; $i < 60; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['1'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("i") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
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
                </div>
                <div class="incident" id="6ml_time">
                    <div class="label">
                        Penthrane 6ml Time:
                    </div>
                    <div class="input">
                        <?
                        if ($_REQUEST['id']) {
                            $time = explode(":", $incidentData['penthrane_6ml_time']);
                        }
                        ?>
                        <select class="hours" id="6ml_time_hours" name="6ml_time_hours">
                            <?
                            for ($i = 0; $i < 24; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['0'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("H") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
                                    }
                                    ?>
                                    >
                                    <?=sprintf("%02d", $i);?>
                                </option>
                            <?
                            }
                            ?>
                        </select>:<select class="hours" id="6ml_time_minutes" name="6ml_time_minutes">
                            <?
                            for ($i = 0; $i < 60; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['1'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("i") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
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
                </div>
                <div class="incident">
                    <div id="entonox_l" class="label">
                        Entonox:
                    </div>
                    <div class="input">
                        <select name="entonox" id="entonox">
                            <option></option>
                            <option value="0"
                                <?
                                if ($incidentData['entonox'] == "0") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >NO</option>
                            <option value="1"
                                <?
                                if ($incidentData['entonox'] == "1") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >YES</option>
                        </select>
                    </div>
                </div>

                <div class="incident entonox">
                    <div id="witness_l" class="label">
                        First Witness:
                    </div>
                    <div class="input">
                        <select id="witness" name="witness">
                            <option></option>
                            <?
                            foreach($patrollersList as $patroller) {
                                if ($patroller['active'] == 1) {
                                    ?>
                                    <option value="<?=$patroller['user_id'];?>"
                                        <?
                                        if ($incidentData['witness_id'] == $patroller['user_id']) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
                                        ?>
                                        >
                                        <?=$patroller['name'];?>&nbsp;<?=$patroller['surname'];?>
                                    </option>
                                <?
                                }
                            }
                            ?>
                        </select>
                        <?
                        if ($_REQUEST['id'] && $incidentData['entonox'] == "1" && !is_null($incidentData['ws'])) {
                            ?>
                            <img src="data:image/jpeg;base64,<?php echo $incidentData['ws'];?>"/>
                        <?
                        } else {
                            ?>
                            <div class="witPad">
                                <ul class="sigNav">
                                    <li class="drawIt"><a href="#draw-it" class="current">Draw It</a></li>
                                    <li class="clearButton"><a href="#clear">Clear</a></li>
                                </ul>
                                <div class="sig sigWrapper">
                                    <div class="typed"></div>
                                    <canvas class="pad" width="300" height="80"></canvas>
                                    <input type="hidden" name="witness_signature" class="output">
                                </div>
                            </div>
                        <?
                        }
                        ?>

                    </div>
                </div>

                <div class="incident entonox">
                    <div id="witnessSec_l" class="label">
                        Second Witness:
                    </div>
                    <div class="input">
                        <select id="witnessSec" name="witness_sec">
                            <option></option>
                            <?
                            foreach($patrollersList as $patroller) {
                                if ($patroller['active'] == 1) {
                                    ?>
                                    <option value="<?=$patroller['user_id'];?>"
                                        <?
                                        if ($incidentData['witness_sec_id'] == $patroller['user_id']) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
                                        ?>
                                        >
                                        <?=$patroller['name'];?>&nbsp;<?=$patroller['surname'];?>
                                    </option>
                                <?
                                }
                            }
                            ?>
                        </select>
                        <?
                        if ($_REQUEST['id'] && $incidentData['entonox'] == "1" && !is_null($incidentData['wss'])) {
                            ?>
                            <img src="data:image/jpeg;base64,<?php echo $incidentData['wss'];?>"/>
                        <?
                        } else {
                            ?>
                            <div class="witPad">
                                <ul class="sigNav">
                                    <li class="drawIt"><a href="#draw-it" class="current">Draw It</a></li>
                                    <li class="clearButton"><a href="#clear">Clear</a></li>
                                </ul>
                                <div class="sig sigWrapper">
                                    <div class="typed"></div>
                                    <canvas class="pad" width="300" height="80"></canvas>
                                    <input type="hidden" name="witness_sec_signature" class="output">
                                </div>
                            </div>
                        <?
                        }
                        ?>

                    </div>
                </div>
                <div class="incident entonox">
                    <div class="label">
                        Entonox Start Time:
                    </div>
                    <div class="input">
                        <?
                        if ($_REQUEST['id']) {
                            $time = explode(":", $incidentData['entonox_start_time']);
                        }
                        ?>
                        <select class="hours" id="entonox_start_time_hours" name="entonox_start_time_hours">
                            <?
                            for ($i = 0; $i < 24; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['0'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("H") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
                                    }
                                    ?>
                                    >
                                    <?=sprintf("%02d", $i);?>
                                </option>
                            <?
                            }
                            ?>
                        </select>:<select class="hours" id="entonox_start_time_minutes" name="entonox_start_time_minutes">
                            <?
                            for ($i = 0; $i < 60; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['1'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("i") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
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
                </div>
                <div class="incident entonox">
                    <div id="entonox_start_amount_l" class="label">
                        Entonox Start Amount:
                    </div>
                    <div class="input">
                        <input type="text" id="entonox_start_amount" name="entonox_start_amount" value="<?=$incidentData['entonox_start_amount'];?>">
                    </div>
                </div>
                <div class="incident entonox">
                    <div id="entonox_end_amount_l" class="label">
                        Entonox End Amount:
                    </div>
                    <div class="input">
                        <input type="text" id="entonox_end_amount" name="entonox_end_amount" value="<?=$incidentData['entonox_end_amount'];?>">
                    </div>
                </div>
                <div class="incident">
                    <div id="oxygen_l" class="label">
                        Oxygen:
                    </div>
                    <div class="input">
                        <select name="oxygen" id="oxygen">
                            <option></option>
                            <option value="0"
                                <?
                                if ($incidentData['oxygen'] == "0") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >NO</option>
                            <option value="1"
                                <?
                                if ($incidentData['oxygen'] == "1") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >YES</option>
                        </select>
                    </div>
                </div>
                <div class="incident oxygen">
                    <div class="label">
                        Oxygen Start Time:
                    </div>
                    <div class="input">
                        <?
                        if ($_REQUEST['id']) {
                            $time = explode(":", $incidentData['oxygen_start_time']);
                        }
                        ?>
                        <select class="hours" id="oxygen_start_time_hours" name="oxygen_start_time_hours">
                            <?
                            for ($i = 0; $i < 24; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['0'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("H") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
                                    }
                                    ?>
                                    >
                                    <?=sprintf("%02d", $i);?>
                                </option>
                            <?
                            }
                            ?>
                        </select>:<select class="hours" id="oxygen_start_time_minutes" name="oxygen_start_time_minutes">
                            <?
                            for ($i = 0; $i < 60; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['1'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("i") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
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
                </div>
                <div class="incident oxygen">
                    <div id="oxygen_flow_rate_l" class="label">
                        Oxygen Flow Rate:
                    </div>
                    <div class="input">
                        <input id="oxygen_flow_rate" type="text" name="oxygen_flow_rate" value="<?=$incidentData['oxygen_flow_rate'];?>">
                    </div>
                </div>
                <div class="incident">
                    <div class="label">
                        Equipment Used:
                    </div>
                    <div class="input">
                        <select id="incident-equipment" multiple="multiple" name="equipment[]">
                            <?
                            if ($_REQUEST['id']) {
                                $equipmentAll = $Incident->getIncidentEquipment($_REQUEST['id']);
                            }
                            foreach($equipmentList as $equipment) {
                                ?>
                                <option class="incident_equipment" value="<?=$equipment['id'];?>"
                                    <?
                                    if ($_REQUEST['id']) {
                                        foreach ($equipmentAll as $equipmentOne){
                                            if ($equipmentOne['equipment_id'] == $equipment['id']) {
                                                ?>
                                                selected="selected"
                                            <?
                                            }
                                        }
                                    }
                                    ?>
                                    >
                                    <?=$equipment['name'];?>
                                </option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="incident">
                    <div class="label">
                        Equipment left with Casualty:
                    </div>
                    <div class="input">
                        <?
                        if ($_REQUEST['id']) {
                            $equipmentAll = $Incident->getIncidentLeftEquipment($_REQUEST['id']);
                        }
                            ?>
                            <select id="equipment-left" multiple="multiple" name="equipment-left[]">
                                <?
                                foreach($equipmentList as $equipment) {
                                    if ($equipment['consumable'] == 0) {
                                        ?>
                                        <option value="<?=$equipment['id'];?>"
                                            <?
                                            if ($_REQUEST['id']) {
                                                foreach ($equipmentAll as $equipmentOne){
                                                    if ($equipmentOne['equipment_id'] == $equipment['id']) {
                                                        ?>
                                                        selected="selected"
                                                    <?
                                                    }
                                                }
                                            }
                                            ?>
                                            >
                                            <?=$equipment['name'];?>
                                        </option>
                                    <?
                                    }
                                }
                                ?>
                            </select>
                    </div>
                </div>
                <div class="incident">
                    <div id="treatment_provided_l" class="label">
                        Treatment Provided:
                    </div>
                    <div class="input">
                        <textarea id="treatment_provided" name="treatment_provided"><?=$incidentData['treatment_provided'];?></textarea>
                    </div>
                </div>
                <div class="incident">
                    <div id="recommended_advice_l" class="label">
                        Advice To Casualty:
                    </div>
                    <div class="input">
                        <textarea id="recommended_advice" name="recommended_advice"><?=$incidentData['recommended_advice'];?></textarea>
                    </div>
                </div>
                <div class="incident">
                    <div id="referral_outcome_l" class="label">
                        Destination:
                    </div>
                    <div class="input">
                        <select name="referral_outcome" id="referral_outcome">
                            <option></option>
                            <?
                            foreach($referralList as $referral) {
                                ?>
                                <option value="<?=$referral['id'];?>"
                                    <?
                                    if ($incidentData['referral_outcome_id'] == $referral['id']) {
                                        ?>
                                        selected="selected"
                                    <?
                                    }
                                    ?>
                                    >
                                    <?=$referral['name'];?>
                                </option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="incident ambulance">
                    <div class="label">
                        Ambulance Mode:
                    </div>
                    <div class="input">
                        <select id="ambulance_mode" name="ambulance_mode">
                            <option value="road"
                                <?
                                if ($incidentData['amblance_mode'] == "road") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >Road</option>
                            <option value="helicopter"
                                <?
                                if ($incidentData['amblance_mode'] == "helicopter") {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >Helicopter</option>
                        </select>
                    </div>
                </div>
                <div class="incident ambulance">
                    <div class="label">
                        Ambulance Call Time:
                    </div>
                    <div class="input">
                        <?
                        if ($_REQUEST['id']) {
                            $time = explode(":", $incidentData['ambulance_call_time']);
                        }
                        ?>
                        <select class="hours" id="ambulance_call_time_hours" name="ambulance_call_time_hours">
                            <?
                            for ($i = 0; $i < 24; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['0'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("H") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
                                    }
                                    ?>
                                    >
                                    <?=sprintf("%02d", $i);?>
                                </option>
                            <?
                            }
                            ?>
                        </select>:<select class="hours" id="ambulance_call_time_minutes" name="ambulance_call_time_minutes">
                            <?
                            for ($i = 0; $i < 60; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['1'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("i") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
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
                </div>
                <div class="incident ambulance">
                    <div class="label">
                        Ambulance Arrival Time:
                    </div>
                    <div class="input">
                        <?
                        if ($_REQUEST['id']) {
                            $time = explode(":", $incidentData['ambulance_arrival_time']);
                        }
                        ?>
                        <select class="hours" id="ambulance_arrival_time_hours" name="ambulance_arrival_time_hours">
                            <?
                            for ($i = 0; $i < 24; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['0'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("H") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
                                    }
                                    ?>
                                    >
                                    <?=sprintf("%02d", $i);?>
                                </option>
                            <?
                            }
                            ?>
                        </select>:<select class="hours" id="ambulance_arrival_time_minutes" name="ambulance_arrival_time_minutes">
                            <?
                            for ($i = 0; $i < 60; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['1'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("i") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
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
                </div>
                <div class="incident ambulance">
                    <div class="label">
                        Ambulance Departure Time:
                    </div>
                    <div class="input">
                        <?
                        if ($_REQUEST['id']) {
                            $time = explode(":", $incidentData['ambulance_departure_time']);
                        }
                        ?>
                        <select class="hours" id="ambulance_departure_time_hours" name="ambulance_departure_time_hours">
                            <?
                            for ($i = 0; $i < 24; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['0'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("H") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
                                    }
                                    ?>
                                    >
                                    <?=sprintf("%02d", $i);?>
                                </option>
                            <?
                            }
                            ?>
                        </select>:<select class="hours" id="ambulance_departure_time_minutes" name="ambulance_departure_time_minutes">
                            <?
                            for ($i = 0; $i < 60; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if ($_REQUEST['id'] && $time['1'] == sprintf("%02d", $i)) {
                                        ?>
                                        selected="selected"
                                    <?
                                    } else {
                                        if (!$_REQUEST['id'] && date("i") == sprintf("%02d", $i)) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
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
                </div>
                <div class="incident ambulance">
                    <div id="ambulance_destination_l" class="label">
                        Ambulance destination:
                    </div>
                    <div class="input">
                        <select id="ambulance_destination" name="ambulance_destination">
                            <option></option>
                            <?
                            foreach ($ambulanceList as $ambulance) {
                                ?>
                                <option value="<?=$ambulance['id'];?>"
                                    <?
                                    if ($incidentData['ambulance_destination_id'] == $ambulance['id']) {
                                        ?>
                                        selected="selected"
                                    <?
                                    }
                                    ?>
                                    >
                                    <?=$ambulance['name'];?>
                                </option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="incident">
                    <div class="label">
                        Additional Notes:
                    </div>
                    <div class="input">
                        <textarea id="additional_notes" name="additional_notes"><?=$incidentData['additional_notes'];?></textarea>
                    </div>
                </div>
                <div class="incident">
                    <div id="patroller_signature_l" class="label">
                        Patroller Signature:
                    </div>
                    <div class="input">
                        <select id="patroller_signature" name="signature_id">
                            <option></option>
                            <?
                            foreach($patrollersSignatureList as $patroller) {
                                if ($patroller['active'] == 1) {
                                    ?>
                                    <option value="<?=$patroller['user_id'];?>"
                                        <?
                                        if ($incidentData['signature_id'] == $patroller['user_id']) {
                                            ?>
                                            selected="selected"
                                        <?
                                        }
                                        ?>
                                        >
                                        <?=$patroller['name'];?>&nbsp;<?=$patroller['surname'];?>
                                    </option>
                                <?
                                }
                            }
                            ?>
                        </select>
                        <?
                        if ($_REQUEST['id'] && $incidentData['signature_id'] > 0 && !is_null($incidentData['ps'])) {
                            ?>
                            <img src="data:image/jpeg;base64,<?php echo $incidentData['ps'];?>"/>
                        <?
                        } else {
                            ?>
                            <div class=" sigPad">
                                <ul class="sigNav">
                                    <li class="drawIt"><a href="#draw-it" class="current">Draw It</a></li>
                                    <li class="clearButton"><a href="#clear">Clear</a></li>
                                </ul>
                                <div class="sig sigWrapper">
                                    <div class="typed"></div>
                                    <canvas id="psCanvas" class="pad" width="300" height="80"></canvas>
                                    <input id="psInput" type="hidden" name="patroller_signature" class="output">
                                </div>
                            </div>
                        <?
                        }
                        ?>
                    </div>
                </div>
                <div class="incident">
                    <div id="dr_l" class="label">
                        Dr Signature:&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="input">
                        <select id="dr" name="dr">
                            <option></option>
                            <?
                            foreach($drList as $dr) {
                                ?>
                                <option value="<?=$dr['id'];?>"
                                    <?
                                    if ($incidentData['dr_id'] == $dr['id']) {
                                        ?>
                                        selected="selected"
                                    <?
                                    }
                                    ?>
                                    >
                                    <?=$dr['name'];?>
                                </option>
                            <?
                            }
                            ?>
                        </select> <br>
                        <?
                        if ($_REQUEST['id'] && $incidentData['dr_id'] > 0 && !is_null($incidentData['drs'])) {
                            ?>
                            <img src="data:image/jpeg;base64,<?php echo $incidentData['drs'];?>"/>
                        <?
                        } else {
                            ?>
                            <div id="drSignature">
                                <ul class="sigNav">
                                    <li class="drawIt"><a href="#draw-it" class="current">Draw It</a></li>
                                    <li class="clearButton"><a href="#clear">Clear</a></li>
                                </ul>
                                <div class="sig sigWrapper">
                                    <div class="typed"></div>
                                    <canvas id="drsCanvas" class="pad" width="300" height="80"></canvas>
                                    <input id="drsInput" type="hidden" name="dr_signature" class="output">
                                </div>
                            </div>
                        <?
                        }
                        ?>
                    </div>
                </div>
                <div class="incident">
                    <div class="label" id="casualty_signature_l">
                        Casualty Signature:
                    </div>
                    <div class="input" id="casualty">
                            <?
                            if ($_REQUEST['id'] && $incidentData['unable_to_sign'] == 0 && !is_null($incidentData['cs'])) {
                                ?>
                                <img src="data:image/jpeg;base64,<?php echo $incidentData['cs'];?>"/>
                            <?
                            } elseif ($incidentData['submit'] == 0) {
                                ?>
                                <div id="casPad" id="casualty-signature">
                                    <ul class="sigNav">
                                        <li class="drawIt"><a href="#draw-it" class="current">Draw It</a></li>
                                        <li class="clearButton"><a href="#clear">Clear</a></li>
                                    </ul>
                                    <div class="sig sigWrapper">
                                        <div class="typed"></div>
                                        <canvas id="csCanvas" class="pad" width="300" height="80"></canvas>
                                        <input id="csInput" type="hidden" name="c_signature" class="output">
                                    </div>
                                </div>
                                <div>
                                    <input type="checkbox" name="parent-signature"
                                        <?
                                        if ($incidentData['parent_signature'] == 1) {
                                            ?>
                                            checked="checked"
                                        <?
                                        }
                                        ?>
                                        > Parent / Guardian
                                </div>
                            <?
                            }
                        if ($incidentData['submit'] == 0) {
                            ?>
                            <div>
                                <input type="checkbox" id="unable-to-sign" name="unable-to-sign"
                                    <?
                                    if ($incidentData['unable_to_sign'] == 1) {
                                        ?>
                                        checked="checked"
                                    <?
                                    }
                                    ?>
                                    > Unable To Sign
                            </div>
                        <?
                        }
                        if ($incidentData['submit'] == 1 && $incidentData['unable_to_sign'] == 1) {
                            ?>
                            <div>
                                <input type="hidden" name="unable-to-sign" value="on">
                                Unable To Sign
                            </div>
                        <?
                        }
                        ?>
                        <?
                        if ($_REQUEST['id'] && $incidentData['unable_to_sign'] == 1) {
                            ?>
                            <div id="unable-reason">Reason: <textarea name="unable_reason"><?=$incidentData['unable_reason'];?></textarea></div>
                        <?
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
   </div>
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Mt BAW BAW Map</h4>
                </div>
                <div class="modal-body injury-location">
                    <img src="images/body.png" usemap=".injuryLocation">
                    <svg class="injuryLocation">
                        <polygon class="pLocation ploc1 location_head" points="256,34 238,34 234,22 232,14 242,6 252,6 258,14 260,22 258,22" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0,0)"/>
                        <polygon class="pLocation ploc2 location_face" points="72,33 64,38 56,34 53,26 50,12 55,5 65,3 74,9 76,20 76,25 76,24" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc3 location_neck" points="74,43 54,43 53,36 63,36 72,34" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc3 location_neck" points="238,33 235,42 260,42 255,33" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc4 location_spine" points="240,40 240,127 255,127 255,40" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc5-l location_back-l" points="224,43 224,126 240,126 240,43" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc5-r location_back-r" points="254,44 254,127 271,127 271,44" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc6 location_thorax" points="39,42 39,81 89,81 89,42" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc7 location_abdomen" points="42,82 42,119 88,119 88,82" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc8 location_pelvis" points="40,119 40,149 89,149 89,119" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc8 location_pelvis" points="225,128 225,141 273,141 273,128" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc9-l location_shoulder-l" points="88,48 88,70 88,76 99,75 99,67 98,58 94,50 90,47" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc9-r location_shoulder-r" points="29,75 31,52 35,47 39,46 39,75 39,75" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc9-l location_shoulder-l" points="227,47 226,76 212,76 211,67 211,60 216,51 220,48" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc9-r location_shoulder-r" points="270,47 269,75 269,74 283,75 284,66 281,56 276,50 276,50" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc10-r location_upperarm-r" points="271,72 271,93 291,93 291,72" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc10-l location_upperarm-l" points="206,72 206,93 225,93 225,72" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc10-l location_upperarm-l" points="87,72 87,93 105,93 105,72" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc10-r location_upperarm-r" points="26,72 25,93 41,93 41,72" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc11-l location_elbow-l" points="208,94 208,104 224,104 224,94" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc11-r location_elbow-r" points="271,92 271,103 288,103 288,92" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc12-l location_forearm-l" points="206,104 221,105 222,107 218,118 211,129 200,125 204,119" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc12-r location_forearm-r" points="275,105 290,104 292,113 297,126 286,130 279,119" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc12-r location_forearm-r" points="25,95 39,96 38,107 33,118 28,128 16,126 20,115" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc12-l location_forearm-l" points="89,96 103,95 106,100 107,107 110,114 112,124 101,127 94,114" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc13-r location_wrist-r" points="10,137 15,129 16,121 31,121 23,137 24,141 24,141" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc13-l location_wrist-l" points="106,139 117,133 111,122 97,122 104,131 104,131" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc13-l location_wrist-l" points="193,137 206,139 208,132 211,127 199,124 196,131 197,131" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc13-r location_wrist-r" points="288,142 305,136 296,128 295,121 287,132" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc14-r location_hand-r" points="289,139 290,153 297,158 303,158 304,143 309,146 305,137 303,135" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc14-l location_hand-l" points="202,160 208,147 208,138 192,135 187,141 193,145 193,141 194,156 196,159" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc14-l location_hand-l" points="106,141 105,152 112,158 118,156 119,144 119,139 121,144 126,142 120,134 116,131 103,136" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc14-r location_hand-r" points="7,132 2,144 5,145 10,142 10,155 13,159 20,156 24,148 24,136 8,132" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc15-r location_thigh-r" points="40,149 48,177 65,177 65,149" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc15-l location_thigh-l" points="65,149 65,177 82,177 89,149" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc15-l location_thigh-l" points="223,142 227,173 248,173 248,142" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc15-r location_thigh-r" points="248,142 248,173 270,173 273,142" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc16-l location_knee-l" points="229,174 229,204 248,204 248,174" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc16-r location_knee-r" points="248,174 248,204 270,204 270,174" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc16-r location_knee-r" points="46,177 46,205 65,205 65,177" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc16-l location_knee-l" points="65,177 65,205 87,205 87,177" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc17-r location_lowerleg-r" points="46,206 53,236 65,236 65,206" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc17-l location_lowerleg-l" points="65,206 65,236 79,236 86,206" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc17-l location_lowerleg-l" points="229,205 236,239 248,239 248,205" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc17-r location_lowerleg-r" points="248,205 248,239 262,239 269,205" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc18-r location_ankle-r" points="49,237 49,248 65,248 65,237" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc18-l location_ankle-l" points="65,237 65,248 82,248 82,237" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc18-l location_ankle-l" points="232,238 232,250 248,250 248,238" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc18-r location_ankle-r" points="248,238 248,250 267,250 267,238" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc19-l location_foot-l" points="229,250 229,257 248,257 248,250" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc19-r location_foot-r" points="248,250 248,257 269,257 269,250" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc19-r location_foot-r" points="44,248 44,258 65,258 65,248" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>
                        <polygon class="pLocation ploc19-l location_foot-l" points="65,248 65,258 85,258 85,248" fill="rgb(0,0,0)" stroke-width="1" stroke="rgb(0,0,0)"/>

                    </svg>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <input id="submitted" type="hidden" name="submitted" value="0">
    <input  type="submit" name="save" value="Save" class="submit">
    <input id="submit_incident" type="submit" name="save" value="Submit" class="submit">
</form>
</div>
    <script>
        incident.init();
        $(document).ready(function(){
            $('#incident-form').show();
        })
        google.maps.event.addDomListener(window, 'load', initialize);
        dailyRun.init();
        incidentId = $('#incidentId').val();
        if (incidentId > 0) {
            $('.incident'+incidentId).sisyphus( {timeout: 10 } );
        } else {
            $('#incidentFrom').sisyphus( {timeout: 10 } );
        }
    </script>
<?

page_footer();

?>