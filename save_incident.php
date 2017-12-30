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


if ($_POST['id']) {
    $incidentData = $Incident->getById($_POST['id']);
}

    $thisdate = $_POST['incident_date_y']."-".$_POST['incident_date_m']."-".$_POST['incident_date_d'];

    $name = $thisdate." ".$_POST['location']." ".$_POST['first_name']." ".$_POST['last_name'];

    $date = mktime("01","00", "00", $_POST['incident_date_m'], $_POST['incident_date_d'], $_POST['incident_date_y']);
    $weekday = date("N", $date);

    $dob = $_POST['dob_y']."-".$_POST['dob_m']."-".$_POST['dob_d'];

    $incidentTime = $_POST['incident_time_hours'].":".$_POST['incident_time_minutes'];
    $patrollerArrivalTime = $_POST['patroller_arrival_time_hours'].":".$_POST['patroller_arrival_time_minutes'];

    if ($_POST['id']) {
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

    if ($_POST['id'] && $incidentData['incident_timestamp'] > date("Y-m-d H:i:s", time() - 172800)) {
        $Incident->updateById($_POST['id'], $aData);

        $SQL = "DELETE FROM penthrane_stock WHERE incident_id = ".$_POST['id']." ";
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

        $SQL = "DELETE FROM incident_patrollers WHERE incident_id = ".$_POST['id']." ";
        $result = $DB->sql_query($SQL);

        for ($i = 0; $i < count($_POST['patroller']); $i++) {
            $patrollerData = array(
                "incident_id" => $_POST['id'],
                "patroller_id" => $_POST['patroller'][$i],
                "role_id" => $_POST['patroller_role'][$i]
            );
            $recId = $Incident->addPatroller($patrollerData);
        }

        $SQL = "DELETE FROM incident_phones WHERE incident_id = ".$_POST['id']." ";
        $result = $DB->sql_query($SQL);

        for ($i = 0; $i < count($_POST['phone']); $i++) {
            $phoneData = array(
                "incident_id" => $_POST['id'],
                "phone" => $_POST['phone'][$i]
            );
            $recId = $Incident->addPhone($phoneData);
        }

        $SQL = "DELETE FROM incident_equipment WHERE incident_id = ".$_POST['id']." ";
        $result = $DB->sql_query($SQL);

        for ($i = 0; $i < count($_POST['equipment']); $i++) {
            $equipmentData = array(
                "incident_id" => $_POST['id'],
                "equipment_id" => $_POST['equipment'][$i]
            );
            $recId = $Incident->addEquipment($equipmentData);
        }

        $equipmentLeft = $Incident->getIncidentLeftEquipment($_POST['id']);
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
                    "incident_id"   => $_POST['id'],
                    "date"          => $thisdate,
                    "equipment_id"  => $equip,
                    "destination"   => $_POST['ambulance_destination'],
                    "return"        => '0'
                );
                $managementId = $Equipment->addManagement($managementData);
            }
        }



        $SQL = "DELETE FROM injures WHERE incident_id = ".$_POST['id']." ";
        $result = $DB->sql_query($SQL);

        for ($i = 0; $i < count($_POST['injury_location']); $i++) {
            $injuryData = array(
                "incident_id"   => $_POST['id'],
                "location_id"   => $_POST['injury_location'][$i],
                "side"          => $_POST['injury_side'][$i],
                "category_id"   => $_POST['injury_category'][$i],
                "type_id"       => $_POST['injury_type'][$i],
                "comment"       => $_POST['comment'][$i],
            );
            $injuryId = $Injury->add($injuryData);
        }

        $SQL = "DELETE FROM incident_vitails WHERE incident_id = ".$_POST['id']." ";
        $result = $DB->sql_query($SQL);

        for ($i = 0; $i < count($_POST['vitail_time_hours']); $i++) {
            $vitalTime = $_POST['vitail_time_hours'][$i].":".$_POST['vitail_time_minutes'][$i];
            $vitailsData = array(
                "incident_id"       => $_POST['id'],
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

    if (!$_POST['id']) {
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
    if ($_POST['id']) {

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
        if ($_POST['id'] && $incidentData['incident_timestamp'] > date("Y-m-d H:i:s", time() - 172800) && count($signatures)) {
            $Incident->updateById($_POST['id'], $signatures);
        }
    }

if (!$_POST['id']) {
    echo $incidentId;
} else {
    echo "here";
}


?>
