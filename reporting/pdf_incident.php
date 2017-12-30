<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */


include("../mpdf/mpdf.php");
include("../config.php");


$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/

$stylesheet = file_get_contents('../style/style.css'); /*подключаем css*/

$mpdf->WriteHTML($stylesheet, 1);


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

$html = '
    <table class="pdf-table">
        <tr>
            <th colspan="9">Casualty Details</th>
        </tr>
        <tr>
            <td colspan="2">First Name</td>
            <td colspan="2">'.$incident['first_name'].'</td>
            <td colspan="2">Last Name</td>
            <td colspan="3">'.$incident['last_name'].'</td>
        </tr>
        <tr>
            <td>D.O.B.</td>
            <td colspan="2">
';
$date = explode("-", $incident['dob']);
$thisdate = $date['2']."-".$date['1']."-".$date['0'];
$html .= $thisdate;

$html .= '
            </td>
            <td>Age</td>
            <td colspan="2">'.$incident['age'].'</td>
            <td>Gender</td>
            <td colspan="2">'.$genders[$incident['gender']].'</td>
        </tr>
        <tr>
            <td colspan="2">MtBB Employee</td>
            <td colspan="2">'.$ynList[$incident['mtbb_employee']].'</td>
            <td colspan="2">Employee On Duty</td>
            <td colspan="3">'.$ynList[$incident['employee_on_duty']].'</td>
        </tr>
        <tr>
            <td>Address</td>
            <td colspan="3">'.$incident['street'].'</td>
            <td>City</td>
            <td colspan="4">'.$incident['city'].'</td>
        </tr>
        <tr>
            <td>State</td>
            <td colspan="2">'.$incident['state'].'</td>
            <td>Country</td>
            <td colspan="2">'.$incident['country'].'</td>
            <td>Postcode</td>
            <td colspan="2">'.$incident['postcode'].'</td>
        </tr>
        <tr>
            <td colspan="2">
                Phone Numbers:
            </td>
            <td colspan="7">
';
foreach ($incidentPhones as $phone) {
    $html .= $phone['phone'].'&nbsp;&nbsp;&nbsp;';
}

$html .= '
            </td>
        </tr>
        <tr>
            <td>Allergies</td>
            <td colspan="3">'.$incident['allergies'].'</td>
            <td>Medications</td>
            <td colspan="4">'.$incident['medications'].'</td>
        </tr>
        <tr>
            <td>Last meals</td>
            <td colspan="3">'.$incident['last_meals'].'</td>
            <td>Last bathroom</td>
            <td colspan="4">'.$incident['last_bathroom'].'</td>
        </tr>
        <tr>
            <td>History</td>
            <td colspan="8">'.$incident['history'].'</td>
        </tr>
        <tr>
            <th colspan="9">Incident Details</th>
        </tr>
        <tr>
            <td>Incident Date</td>
            <td colspan="2">
';

$date = explode("-", $incident['incident_date']);
$thisdate = $date['2']."-".$date['1']."-".$date['0'];
$html .= $thisdate;

$html .= '
            </td>
            <td colspan="2">Incident Time</td>
            <td>'.$incident['incident_time'].'</td>
            <td colspan="2">Patroller Arrival Time</td>
            <td>'.$incident['patroller_arrival_time'].'</td>
        </tr>
        <tr>
            <td colspan="2">Description of Incident</td>
            <td colspan="6">'.$incident['description'].'/td>
            <td>
                Helmet<hr>
                '.$ynList[$incident['helmet']].'
            </td>
        </tr>
        <tr>
            <td colspan="2">Activity</td>
            <td colspan="
';

            if ($incident['activity_id'] == "1" || $incident['activity_id'] == "2" || $incident['activity_id'] == "4" || $incident['activity_id'] == "7") {
                $html .= '2';
            } else {
                $html .= '7';
            }
            $html .= '">
                '.$activityList[$incident['activity_id']]['name'].'
            </td>';

            if ($incident['activity_id'] == "1") {
                $html .= '
                <td colspan="1">Bindings Release</td>
                <td colspan="1">'.$incident['bindings_release'].'</td>
                ';
            }
            if ($incident['activity_id'] == "2") {
                $html .= '
                <td colspan="1">Snowboard Stance</td>
                <td colspan="1">'.$incident['sb_stance'].'</td>
                ';
            }
            if ($incident['activity_id'] == "1" || $incident['activity_id'] == "2") {
                $html .= '
                <td colspan="1">Ability</td>
                <td colspan="2">'.$incident['ability'].'</td>';
            }
            if ($incident['activity_id'] == "4" || $incident['activity_id'] == "7") {
                $html .= '
                <td colspan="2">Ability</td>
                <td colspan="3">'.$incident['ability'].'</td>';
            }

$html .= '
        </tr>
        <tr>
            <td colspan="2">
                Equipment Source
            </td>
            <td colspan="
';

            if ($incident['equipment_source'] == "rental") {
                $html .= '2';
            } else {
                $html .= '7';
            }
$html .= '
">
                '.$incident['equipment_source'].'
            </td>
';
            if ($incident['equipment_source'] == "rental") {
                $html .= '
                <td colspan="2">Rental Source</td>
                <td colspan="3">'.$incident['rental_source'].'</td>';
            }
$html .= '
        </tr>
        <tr>
            <td>Weather</td>
            <td colspan="2">'.$weatherList[$incident['weather_id']]['name'].'</td>
            <td>Snow</td>
            <td colspan="2">'.$snowList[$incident['snow_id']]['name'].'</td>
            <td>Visibility</td>
            <td colspan="2">'.$visibilityList[$incident['visibility_id']]['name'].'</td>
        </tr>
        <tr>
            <td>Map Coordinates</td>
            <td colspan="2">'.$incident['map_coordinates'].'</td>
            <td>Lift / Category</td>
            <td colspan="2">'.$liftList[$incident['lift_id']]['name'].'</td>
            <td>Trail / Location</td>
            <td colspan="2">'.$runList[$incident['run_id']]['name'].'</td>
        </tr>
        <tr>
            <td colspan="5">Patroller</td>
            <td colspan="4">Role</td>
        </tr>
';

        $i = 1;
        foreach ($incidentPatrollers as $patroller) {
            $html .= '

            <tr>
                <td>'.$i.'</td>
                <td colspan="4">'.$usersList[$patroller['patroller_id']]['name'].' '.$usersList[$patroller['patroller_id']]['surname'].'</td>
                <td colspan="4">'.$rolesList[$patroller['role_id']]['name'].'</td>
            </tr>
            ';
            $i++;
        }
$html .= '
        <tr>
            <th colspan="9">Injuries / Illness / Vitals</th>
        </tr>
        <tr>
            <td colspan="2">Symptoms</td>
            <td colspan="7">'.$incident['symptoms'].'</td>
        </tr>
        <tr>
            <td>LOC</td>
            <td colspan="

';


            if ($incident['loc'] == 1) {
                $html .= '1';
            } else {
                $html .= '8';
            }
$html .= '">
                '.$ynList[$incident['loc']].'
            </td>
';


            if ($incident['loc'] == 1) {
                $html .= '
                <td>LOC Comment</td>
                <td colspan="6">'.$incident['loc_comment'].'</td>';
            }
$html .= '

        </tr>
        <tr>
            <td>Spinal</td>
            <td colspan="
';


            if ($incident['spinal_injury'] == 1) {
                $html .= '1';
            } else {
                $html .= '8';
            }
$html .= '
">
                '.$ynList[$incident['spinal_injury']].'
            </td>
';



            if ($incident['spinal_injury'] == 1) {
                $html .= '
                <td>Spinal Comment</td>
                <td colspan="6">'.$incident['spinal_comment'].'</td>';
            }
$html .= '
        </tr>
        <tr>
            <td colspan="9">Probable injury</td>
        </tr>
        <tr>
            <td colspan="6">
                <table class="pdf-table">
                    <tr>
                        <td>Location</td>
                        <td>Category</td>
                        <td>Type</td>
                        <td>Comment</td>
                    </tr>
';


                    foreach ($injures as $injury) {
                        $html .= '

                        <tr>
                            <td>
                                '.$injuryLocationList[$injury['location_id']]['name'].'
                            </td>
                            <td>
                                '.$injuryCategoryList[$injury['category_id']]['name'].'
                            </td>
                            <td>
                                '.$injuryTypesList[$injury['type_id']]['name'].'
                            </td>
                            <td>
                                '.$injury['comment'].'
                            </td>
                        </tr>
                        ';
                    }
$html .= '
                </table>
            </td>
            <td colspan="3">
                <div class="pdf-img">
                    <svg height="280px" width="320px">



';
$html .= '<polygon points="5,146,4,143,8,140,9,137,16,130,19,121,22,106,23,103,26,96,29,89,30,79,30,73,31,71,29,62,32,53,38,46,47,45,52,42,54,42,56,39,56,34,55,29,55,28,52,25,50,20,52,20,52,12,58,5,66,3,74,9,77,14,76,18,76,21,77,20,78,23,75,28,73,33,72,38,77,42,86,47,95,49,101,62,100,77,101,90,103,96,107,102,110,116,115,129,118,133,123,140,126,145,123,146,118,141,121,144,119,146,120,145,120,149,121,156,119,158,116,159,115,160,113,158,110,156,108,153,106,147,105,142,106,133,101,127,96,119,92,111,88,102,89,94,88,89,87,87,89,74,85,98,85,104,86,110,86,114,90,126,90,142,87,163,81,182,82,192,83,202,84,215,81,227,77,240,78,248,81,252,84,255,81,258,75,256,70,256,67,254,66,248,67,242,68,231,67,211,69,200,67,193,65,167,65,142,66,194,62,200,64,207,65,214,64,220,64,238,65,244,67,251,64,258,59,257,57,258,49,256,48,253,55,247,56,238,51,227,47,212,48,199,50,189,49,182,43,164,41,144,41,124,45,113,44,107,46,100,45,93,42,89,42,77,41,72,42,88,39,96,39,108,33,122,24,136,25,147,22,154,20,157,20,157,18,160,15,160,12,159,9,158,10,148,11,143" fill="rgb(255, 255, 255)" stroke-width="1" stroke="rgb(0, 0, 0)" class="poly-show"/>';
$html .= '<polygon points="189,146,193,134,199,131,203,121,205,109,206,100,212,91,213,87,212,82,213,73,213,60,219,47,231,45,237,41,239,35,236,25,234,24,233,19,235,16,238,10,246,4,249,5,257,10,261,15,259,20,260,22,261,20,262,23,257,28,255,33,257,39,264,45,270,46,278,52,282,56,285,66,284,72,284,79,285,83,285,90,289,97,294,116,298,126,301,133,305,136,308,141,309,144,308,146,304,142,304,152,304,158,302,159,300,161,298,158,296,159,293,155,291,153,289,140,290,133,286,129,276,113,273,101,273,95,272,90,270,85,270,77,270,87,271,91,270,89,268,95,268,98,269,104,271,110,269,115,271,131,272,137,273,146,273,151,271,163,266,179,265,190,267,204,267,214,264,229,261,243,260,248,262,251,264,253,264,254,259,256,253,255,249,254,249,250,250,245,251,243,252,230,250,219,249,211,251,201,250,194,247,187,249,180,249,170,249,160,249,149,249,141,249,139,254,141,259,142,251,141,248,136,248,129,247,139,241,141,237,140,245,141,249,138,249,151,248,165,248,173,249,182,249,190,247,194,247,202,248,210,246,218,247,229,247,241,250,247,248,254,241,255,235,254,231,253,236,249,239,244,238,237,234,228,230,215,230,201,233,187,230,178,223,150,225,125,227,110,229,98,228,94,225,90,227,77,226,73,226,90,225,100,219,114,212,130,207,136,209,141,207,147,205,153,204,156,201,160,197,160,194,158,194,149,193,142,189,147"  fill="rgb(255, 255, 255)" stroke-width="1" stroke="rgb(0, 0, 0)" class="poly-show"/>';


foreach ($injures as $injury) {
                                if ($injury['location_id'] == 1) {
                                    $html .= '<polygon points="256,34 238,34 234,22 232,14 242,6 252,6 258,14 260,22 258,22" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show"/>';
                                }
                                if ($injury['location_id'] == 2) {
                                    $html .= '<polygon points="72,33 64,38 56,34 53,26 50,12 55,5 65,3 74,9 76,20 76,25 76,24" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 3) {
                                    $html .= '<polygon points="74,43 54,43 53,36 63,36 72,34" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 3) {
                                    $html .= '<polygon points="238,33 235,42 260,42 255,33" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 4) {
                                    $html .= '<polygon points="240,40 240,127 255,127 255,40" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 5 && $injury['side'] == 1) {
                                    $html .= '<polygon points="224,43 224,126 240,126 240,43" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 5 && $injury['side'] == 2) {
                                    $html .= '<polygon points="254,44 254,127 271,127 271,44" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 6) {
                                    $html .= '<polygon points="39,42 39,81 89,81 89,42" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 7) {
                                    $html .= '<polygon points="42,82 42,119 88,119 88,82" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 8) {
                                    $html .= '<polygon points="40,119 40,149 89,149 89,119" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 8) {
                                    $html .= '<polygon points="225,128 225,141 273,141 273,128" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 9 && $injury['side'] == 1) {
                                    $html .= '<polygon points="88,48 88,70 88,76 99,75 99,67 98,58 94,50 90,47" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 9 && $injury['side'] == 2) {
                                    $html .= '<polygon points="29,75 31,52 35,47 39,46 39,75 39,75" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 9 && $injury['side'] == 1) {
                                    $html .= '<polygon points="227,47 226,76 212,76 211,67 211,60 216,51 220,48" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 9 && $injury['side'] == 2) {
                                    $html .= '<polygon points="270,47 269,75 269,74 283,75 284,66 281,56 276,50 276,50" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 10 && $injury['side'] == 2) {
                                    $html .= '<polygon points="271,72 271,93 291,93 291,72" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 10 && $injury['side'] == 1) {
                                    $html .= '<polygon points="206,72 206,93 225,93 225,72" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 10 && $injury['side'] == 1) {
                                    $html .= '<polygon points="87,72 87,93 105,93 105,72" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 10 && $injury['side'] == 2) {
                                    $html .= '<polygon points="26,72 25,93 41,93 41,72" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 11 && $injury['side'] == 1) {
                                    $html .= '<polygon points="208,94 208,104 224,104 224,94" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 11 && $injury['side'] == 2) {
                                    $html .= '<polygon points="271,92 271,103 288,103 288,92" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 12 && $injury['side'] == 1) {
                                    $html .= '<polygon points="206,104 221,105 222,107 218,118 211,129 200,125 204,119" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 12 && $injury['side'] == 2) {
                                    $html .= '<polygon points="275,105 290,104 292,113 297,126 286,130 279,119" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 12 && $injury['side'] == 2) {
                                    $html .= '<polygon points="25,95 39,96 38,107 33,118 28,128 16,126 20,115" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 12 && $injury['side'] == 1) {
                                    $html .= '<polygon points="89,96 103,95 106,100 107,107 110,114 112,124 101,127 94,114" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 13 && $injury['side'] == 2) {
                                    $html .= '<polygon points="10,137 15,129 16,121 31,121 23,137 24,141 24,141" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 13 && $injury['side'] == 1) {
                                    $html .= '<polygon points="106,139 117,133 111,122 97,122 104,131 104,131" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 13 && $injury['side'] == 1) {
                                    $html .= '<polygon points="193,137 206,139 208,132 211,127 199,124 196,131 197,131" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 13 && $injury['side'] == 2) {
                                    $html .= '<polygon points="288,142 305,136 296,128 295,121 287,132" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 14 && $injury['side'] == 2) {
                                    $html .= '<polygon points="289,139 290,153 297,158 303,158 304,143 309,146 305,137 303,135" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 14 && $injury['side'] == 1) {
                                    $html .= '<polygon points="202,160 208,147 208,138 192,135 187,141 193,145 193,141 194,156 196,159" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 14 && $injury['side'] == 1) {
                                    $html .= '<polygon points="106,141 105,152 112,158 118,156 119,144 119,139 121,144 126,142 120,134 116,131 103,136" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 14 && $injury['side'] == 2) {
                                    $html .= '<polygon points="7,132 2,144 5,145 10,142 10,155 13,159 20,156 24,148 24,136 8,132" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 15 && $injury['side'] == 2) {
                                    $html .= '<polygon points="40,149 48,177 65,177 65,149" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 15 && $injury['side'] == 1) {
                                    $html .= '<polygon points="65,149 65,177 82,177 89,149" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 15 && $injury['side'] == 1) {
                                    $html .= '<polygon points="223,142 227,173 248,173 248,142" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 15 && $injury['side'] == 2) {
                                    $html .= '<polygon points="248,142 248,173 270,173 273,142" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 16 && $injury['side'] == 1) {
                                    $html .= '<polygon points="229,174 229,204 248,204 248,174" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 16 && $injury['side'] == 2) {
                                    $html .= '<polygon points="248,174 248,204 270,204 270,174" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 16 && $injury['side'] == 2) {
                                    $html .= '<polygon points="46,177 46,205 65,205 65,177" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 16 && $injury['side'] == 1) {
                                    $html .= '<polygon points="65,177 65,205 87,205 87,177" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 17 && $injury['side'] == 2) {
                                    $html .= '<polygon points="46,206 53,236 65,236 65,206" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 17 && $injury['side'] == 1) {
                                    $html .= '<polygon points="65,206 65,236 79,236 86,206" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 17 && $injury['side'] == 1) {
                                    $html .= '<polygon points="229,205 236,239 248,239 248,205" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 17 && $injury['side'] == 2) {
                                    $html .= '<polygon points="248,205 248,239 262,239 269,205" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 18 && $injury['side'] == 2) {
                                    $html .= '<polygon points="49,237 49,248 65,248 65,237" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 18 && $injury['side'] == 1) {
                                    $html .= '<polygon points="65,237 65,248 82,248 82,237" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 18 && $injury['side'] == 1) {
                                    $html .= '<polygon points="232,238 232,250 248,250 248,238" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 18 && $injury['side'] == 2) {
                                    $html .= '<polygon points="248,238 248,250 267,250 267,238" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 19 && $injury['side'] == 1) {
                                    $html .= '<polygon points="229,250 229,257 248,257 248,250" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 19 && $injury['side'] == 2) {
                                    $html .= '<polygon points="248,250 248,257 269,257 269,250" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 19 && $injury['side'] == 2) {
                                    $html .= '<polygon points="44,248 44,258 65,258 65,248" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                                if ($injury['location_id'] == 19 && $injury['side'] == 1) {
                                    $html .= '<polygon points="65,248 65,258 85,258 85,248" fill="rgb(201, 201, 201)" stroke-width="1" stroke="rgb(201, 201, 201)" class="poly-show" />';
                                }
                            }
$html .= '
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
        ';


        foreach ($vitals as $vital) {
            $html .= '
            <tr>
                <td>'.$vital['time'].'</td>
                <td>'.$vital['gcs'].'</td>
                <td>'.$vital['pupils'].'</td>
                <td>'.$vital['bp'].'</td>
                <td>'.$vital['respiration'].'</td>
                <td>'.$vital['pulse'].'</td>
                <td>'.$vital['skin'].'</td>
                <td>'.$vital['o2'].'</td>
                <td>'.$vital['temp'].'</td>
            </tr>
            ';
        }
$html .= '

        <tr>
            <th colspan="9">Treatment / Handover</th>
        </tr>
        <tr>
            <td>Transport</td>
            <td colspan="2">'.$transportList[$incident['transport_id']]['name'].'</td>
            <td>Penthrane</td>
            <td colspan="
';

            if ($incident['penthrane'] == "0") {
                $html .= '5';
            }
            if ($incident['penthrane'] == "3") {
                $html .= '3';
            }
            if ($incident['penthrane'] == "6") {
                $html .= '1';
            }
$html .= '">';

                if ($incident['penthrane'] == 0) {
                    $html .= 'No';
                } else {
                    $html .= $incident['penthrane'].' ml';
                }
$html .= '
            </td>
';


            if ($incident['penthrane'] > "0") {
                $html .= '
                <td>3ml Time</td>
                <td>'.$incident['penthrane_3ml_time'].'</td>';
            }
            if ($incident['penthrane'] == "6") {
                $html .= '
                <td>6ml Time</td>
                <td>'.$incident['penthrane_6ml_time'].'</td>';
            }
$html .= '
        </tr>
        <tr>
            <td>Entonox</td>
            <td colspan="
';

            if ($incident['entonox'] == "1") {
                $html .= '2';
            } else {
                $html .= '8';
            }
$html .= '">
                '.$ynList[$incident['entonox']].'
            </td>
';

            if ($incident['entonox'] == "1") {
                $html .= '
                <td>Start Amount</td>
                <td colspan="2">'.$incident['entonox_start_amount'].'</td>
                <td>End Amount</td>
                <td colspan="2">'.$incident['entonox_end_amount'].'</td>';
            }
$html .= '
        </tr>';

        if ($incident['entonox'] == "1") {
            $html .= '
            <tr>
                <td colspan="2">
                    Entonox Start Time:
                    <hr>
                    '.$incident['entonox_start_time'].'
                </td>
                <td>Witness:</td>
                <td colspan="3">
                    '.$usersList[$incident['witness_id']]['name'].' '.$usersList[$incident['witness_id']]['surname'].'
                    <img src="/signatures/ws_'.$_GET['id'].'.png">
                </td>
                <td colspan="3">
                    '.$usersList[$incident['witness_sec_id']]['name'].' '.$usersList[$incident['witness_sec_id']]['surname'].'
                    <img src="/signatures/wss_'.$_GET['id'].'.png">
                </td>
            </tr>';
        }
$html .= '
        <tr>
            <td>Oxygen</td>
            <td colspan="';
            if ($incident['oxygen'] == "1") {
                $html .= '2';
            } else {
                $html .= '8';
            }
$html .= '">
                '.$ynList[$incident['oxygen']].'
            </td>
';

            if ($incident['oxygen'] == "1") {
                $html .= '
                <td>Start Time</td>
                <td colspan="2">'.$incident['oxygen_start_time'].'</td>
                <td>Flow Rate</td>
                <td colspan="2">'.$incident['oxygen_flow_rate'].'</td>';
            }
$html .= '

        </tr>
        <tr>
            <td colspan="2">Equipment Used</td>
            <td colspan="7">
';

                foreach ($incidentEquipment as $equipment) {
                    $html .= $equipmentList[$equipment['equipment_id']]['name'].'<br>';
                }
$html .= '
            </td>
        </tr>
        <tr>
            <td colspan="2">Treatment Provided</td>
            <td colspan="7">'.$incident['treatment_provided'].'</td>
        </tr>
        <tr>
            <td colspan="2">Advice To Casualty</td>
            <td colspan="7">'.$incident['recommended_advice'].'</td>
        </tr>
        <tr>
            <td colspan="2">Destination</td>
            <td colspan="
        ';

            if ($incident['referral_outcome_id'] == 6) {
                $html .= '1';
            } else {
                $html .= '7';
            }
$html .= '">
                '.$outcomeList[$incident['referral_outcome_id']]['name'].'
            </td>
';

            if ($incident['referral_outcome_id'] == 6) {
                $html .= '
                <td colspan="6">
                    <table class=" table">
                        <tr>
                            <td>Ambulance Mode</td>
                            <td>'.$incident['ambulance_mode'].'</td>
                        </tr>
                        <tr>
                            <td>\'000\' Call time</td>
                            <td>'.$incident['ambulance_call_time'].'</td>
                        </tr>
                        <tr>
                            <td>Ambulance Arrival Time</td>
                            <td>'.$incident['ambulance_arrival_time'].'</td>
                        </tr>
                        <tr>
                            <td>Ambulance Departure Time</td>
                            <td>'.$incident['ambulance_departure_time'].'</td>
                        </tr>
                        <tr>
                            <td>Ambulance Destination</td>
                            <td>'.$destinationList[$incident['ambulance_destination_id']]['name'].'</td>
                        </tr>
                    </table>
                </td>';
            }
$html .= '
        </tr>
        <tr>
            <td colspan="2">Additional Notes</td>
            <td colspan="7">'.$incident['additional_notes'].'</td>
        </tr>
        <tr>';


            if ($incident['dr_id'] >0) {
                $html .= '
                <th colspan="3">Doctor</th>';
            }
$html .= '
            <th colspan="';

            if ($incident['dr_id'] >0) {
                $html .= '3';
            } else {
                $html .= '4';
            }
$html .= '">
                Patroller
            </th>
            <th colspan="';

            if ($incident['dr_id'] >0) {
                $html .= '3';
            } else {
                $html .= '5';
            }
$html .= '">
                Casualty
            </th>
        </tr>
        <tr>
            ';

            if ($incident['dr_id'] >0) {
                $html .= '
                <td colspan="3">
                    <img src="/signatures/drs_'.$_GET['id'].'.png">
                </td>';
            }
$html .= '
            <td colspan="';

            if ($incident['dr_id'] >0) {
                $html .= '3';
            } else {
                $html .= '4';
            }
$html .= '">
                <img src="/signatures/ps_'.$_GET['id'].'.png">
            </td>
            <td colspan="';

            if ($incident['dr_id'] >0) {
                $html .= '3';
            } else {
                $html .= '5';
            }
$html .= '">
';

                if ($incident['unable_to_sign'] == 1) {
                    $html .= '
                    Unable to sign. Reason: <br>'.$incident['unable_reason'];
                } else {
                    $html .= '<img src="/signatures/cs_'.$_GET['id'].'.png">';
                }
$html .= '
            </td>
        </tr>
        <tr>
';

            if ($incident['dr_id'] >0) {
                $html .= '
                <td colspan="3">'.$drList[$incident['dr_id']]['name'].'</td>';
            }
$html .= '
            <td colspan="';

            if ($incident['dr_id'] >0) {
                $html .= '3';
            } else {
                $html .= '4';
            }
$html .= '">
                '.$usersList[$incident['signature_id']]['name'].' '.$usersList[$incident['signature_id']]['surname'].'
            </td>
            <td colspan="';

            if ($incident['dr_id'] >0) {
                $html .= '3';
            } else {
                $html .= '5';
            }
$html .= '">';

                if ($incident['parent_signature'] == 1) {
                    $html .= 'Parent / Guardian <br>';
                }
$html .= '
            </td>
        </tr>
    </table>';

$mpdf->list_indent_first_level = 0;
$mpdf->WriteHTML($html, 2); /*формируем pdf*/
$mpdf->Output('Incident.pdf', 'I');

?>
