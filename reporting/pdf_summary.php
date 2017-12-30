<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 11:36
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


$html = '<h4>&nbsp;&nbsp;From '.$dateFrom.' To '.$dateTo.'.';
if ($userId) {
    $html .= '&nbsp;&nbsp;Patroller: '.$usersList[$userId]['name'].'&nbsp;'.$usersList[$userId]['surname'];
}
$html .= '</h4>';
$html .= '<table class="pdf-table">
        <tr>';
$weekReport = $Incident->getWeekReport($fromDate, $toDate, $userId);
foreach ($week as $dayId => $dayName) {
    $html .= '<th>'.$dayName.'</th>';
}
$html .= '</tr>
        <tr>';
foreach ($week as $dayId => $dayName) {
    $html .= '<td class="amount-red">
       ';
    if ($weekReport[$dayId]) {
        $html .= $weekReport[$dayId];
    } else {
        $html .= '0';
    }
    $html .= '
    </td>';
}
$html .= '        </tr>
    </table> <br>';

$html .= '<table class="pdf-table">
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
                '.$Incident->getTimeReport($fromDate, $toDate, "08:00", "09:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                09:00 - 10:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "09:00", "10:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                10:00 - 11:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "10:00", "11:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                11:00 - 12:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "11:00", "12:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                12:00 - 13:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "12:00", "13:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                13:00 - 14:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "13:00", "14:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                14:00 - 15:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "14:00", "15:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                15:00 - 16:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "15:00", "16:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                16:00 - 17:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "16:00", "17:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                17:00 - 18:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "17:00", "18:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                18:00 - 19:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "18:00", "19:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                19:00 - 20:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "19:00", "20:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                20:00 - 00:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "20:00", "00:00", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                00:00 - 08:00
            </td>
            <td class="amount-red">
                '.$Incident->getTimeReport($fromDate, $toDate, "00:00", "08:00", $userId).'
            </td>
        </tr>
    </table> <br>';

$html .= '<table class="pdf-table">
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
                '.$Incident->getAmbulanceModeReport($fromDate, $toDate, "road", $userId).'
            </td>
        </tr>
        <tr>
            <td>
                Air
            </td>
            <td class="amount-red">
                '.$Incident->getAmbulanceModeReport($fromDate, $toDate, "helicopter", $userId).'
            </td>
        </tr>
    </table> <br>';

$html .= '<table class="pdf-table">
<tr class="rotate-normal">
            <th class="rotateText">Injury Type<br> \\ <br>Activity </th>
';
$report = $Incident->getActivityInjuresReport($fromDate, $toDate, $userId);
foreach ($activityList as $activity) {
    if ($report[$activity['id']]) {
        $html .= '<td class="rotateText">
            <img width="30px" src="../images/activity'.$activity['id'].'.png">
        </td>';
    }
}
$html .= '<th nowrap class="rotateText">Injury<br> Type <br>Sum</th>
        </tr>';
foreach ($injuryTypesList as $type) {
    $injuryCount = 0;
    foreach ($activityList as $activity) {
        if ($report[$activity['id']]) {
            $injuryCount += $report[$activity['id']][$type['id']];
        }
    }
    if ($injuryCount > 0) {
        $html .= '<tr class="rotateText">
            <th>'.$type['name'].'</th>';
            foreach ($activityList as $activity) {
                if ($report[$activity['id']]) {
                    $html .= '<td>';
                    if ($report[$activity['id']][$type['id']]) {
                        $html .= $report[$activity['id']][$type['id']];
                    } else {
                        $html .= '0';
                    }
                    $html .= '</td>';
                }
            }
        $html .= '<td class="amount-red">
                '.$injuryCount.'
            </td>
        </tr>';
    }
}
$html .= '<tr>
            <th>Activity Sum</th>';
foreach ($activityList as $activity) {
    if ($report[$activity['id']]) {
        $html .= '<td class="amount-red rotateText">
            '.array_sum($report[$activity['id']]).'
        </td>';
    }
}
$html .= '<th>&nbsp;</th>
        </tr>
    </table> <br>';

$html .= '<table class="pdf-table">
<tr class="rotate-normal">
            <th class="rotateText">Injury Location<br> \\ <br>Activity </th>
';
$report = $Incident->getActivityInjuryLocationReport($fromDate, $toDate, $userId);
foreach ($activityList as $activity) {
    if ($report[$activity['id']]) {
        $html .= '<td class="rotateText">
            <img width="30px" src="../images/activity'.$activity['id'].'.png">
        </td>';
    }
}
$html .= '<th nowrap class="rotateText">Injury<br> Location <br>Sum</th>
        </tr>';
foreach ($injuryLocationList as $loc) {
    $injuryCount = 0;
    foreach ($activityList as $activity) {
        if ($report[$activity['id']]) {
            $injuryCount += $report[$activity['id']][$loc['id']];
        }
    }
    if ($injuryCount > 0) {
        $html .= '<tr class="rotateText">
            <th>'.$loc['name'].'</th>';
            foreach ($activityList as $activity) {
                if ($report[$activity['id']]) {
                    $html .= '<td>';
                    if ($report[$activity['id']][$loc['id']]) {
                        $html .= $report[$activity['id']][$loc['id']];
                    } else {
                        $html .= '0';
                    }
                    $html .= '</td>';
                }
            }
        $html .= '<td class="amount-red">
                '.$injuryCount.'
            </td>
        </tr>';
    }
}
$html .= '<tr>
            <th>Activity Sum</th>';
foreach ($activityList as $activity) {
    if ($report[$activity['id']]) {
        $html .= '<td class="amount-red rotateText">
            '.array_sum($report[$activity['id']]).'
        </td>';
    }
}
$html .= '<th>&nbsp;</th>
        </tr>
    </table> <br>';

$html .= '<table class="pdf-table">
<tr class="rotate-normal">
            <th class="rotateText">Injury Type<br> \\ <br>Injury Location </th>
';
$report = $Incident->getLocationInjuresReport($fromDate, $toDate, $userId);
foreach ($injuryLocationList as $location) {
    if ($report[$location['id']]) {
        $html .= '<th class="rotateText">
            '.$location['name'].'
        </th>';
    }
}
$html .= '<th nowrap class="rotateText">Injury<br> Type <br>Sum</th>
        </tr>';
foreach ($injuryTypesList as $type) {
    $injuryCount = 0;
    foreach ($injuryLocationList as $location) {
        if ($report[$location['id']]) {
            $injuryCount += $report[$location['id']][$type['id']];
        }
    }
    if ($injuryCount > 0) {
        $html .= '<tr>
            <th class="rotateText">'.$type['name'].'</th>';
        foreach ($injuryLocationList as $location) {
            if ($report[$location['id']]) {
                    $html .= '<td>';
                    if ($report[$location['id']][$type['id']]) {
                        $html .= $report[$location['id']][$type['id']];
                    } else {
                        $html .= '0';
                    }
                    $html .= '</td>';
                }
            }
        $html .= '<td class="amount-red rotateText">
                '.$injuryCount.'
            </td>
        </tr>';
    }
}
$html .= '<tr>
            <th>Injury Location Sum</th>';
foreach ($injuryLocationList as $location) {
    if ($report[$location['id']]) {
        $html .= '<td class="amount-red rotateText">
            '.array_sum($report[$location['id']]).'
        </td>';
    }
}
$html .= '<th>&nbsp;</th>
        </tr>
    </table> <br>';

$html .= '<table class="pdf-table">
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
                '.$Incident->getAgeReport($fromDate, $toDate, "0", "6", $userId).'
            </td>
            <td class="amount-red">
                '.$Incident->getAgeReport($fromDate, $toDate, "6", "13", $userId).'
            </td>
            <td class="amount-red">
                '.$Incident->getAgeReport($fromDate, $toDate, "13", "18", $userId).'
            </td>
            <td class="amount-red">
                '.$Incident->getAgeReport($fromDate, $toDate, "18", "24", $userId).'
            </td>
            <td class="amount-red">
                '.$Incident->getAgeReport($fromDate, $toDate, "24", "35", $userId).'
            </td>
            <td class="amount-red">
                '.$Incident->getAgeReport($fromDate, $toDate, "35", "50", $userId).'
            </td>
            <td class="amount-red">
                '.$Incident->getAgeReport($fromDate, $toDate, "50", null, $userId).'
            </td>
        </tr>
</table> <br>';

$html .= '<table class="pdf-table">
    <tr>
        <th>
            Injury Category
        </th>
        <th>
            Amount
        </th>
    </tr>';
$categoriesReport = $Incident->getInjuryCategoriesReport($fromDate, $toDate, $userId);
foreach ($injuryCategories as $category) {
    $html .= '
    <tr>
        <td>'.$category['name'].'</td>
        <td class="amount-red">';
    if ($categoriesReport[$category['id']]) {
        $html .= $categoriesReport[$category['id']];
    } else {
        $html .= '0';
    }
        $html .= '</td>
    </tr>';
}
$html .= '</table> <br>';

$html .= '
    <table class="pdf-table">
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
                '.$Incident->getHelmetReport($fromDate, $toDate, "1", $userId).'
            </td>
            <td class="amount-red">
                '.$Incident->getHelmetReport($fromDate, $toDate, "0", $userId).'
            </td>
            <td class="amount-red">
                '.$Incident->getHelmetReport($fromDate, $toDate, "2", $userId).'
            </td>
        </tr>
    </table> <br>';


$mpdf->list_indent_first_level = 0;
$mpdf->WriteHTML($html, 2); /*формируем pdf*/
$mpdf->Output('Summary.pdf', 'I');
?>