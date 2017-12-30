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

$resourceId = 17; // Entonox Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$Incident = new Incident();

$usersList = $User->getList();
$userTypesList = $User->getTypesList();



$dateFrom = "01-".date("m-Y");
if ($_COOKIE['entonox_date_from']) {
    $dateFrom = $_COOKIE['entonox_date_from'];
}

$dateTo = date("d-m-Y");
if ($_COOKIE['entonox_date_to']) {
    $dateTo = $_COOKIE['entonox_date_to'];
}

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$userId = 'all';

$userId = $_COOKIE['entonox_patroller'];
$userData = $User->getById($_GET['id']);
$report = $Incident->getEntonoxReport($fromDate, $toDate, $userId);


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
                <th>Date</th>
                <th>Start Amount</th>
                <th>End Amount</th>
                <th>Patroller Witness</th>
                <th>Patroller Signed</th>
                <th>View</th>
            </tr>
            <?
            foreach ($report as $incident) {
                ?>
                <tr>
                    <td>
                        <?
                        $datet = explode("-", $incident['incident_date']);
                        $thisdate = $datet['2']."-".$datet['1']."-".$datet['0'];
                        echo $thisdate;
                        ?>
                    </td>
                    <td>
                        <?=$incident['entonox_start_amount'];?>
                    </td>
                    <td>
                        <?=$incident['entonox_end_amount'];?>
                    </td>
                    <td>
                        <?=$usersList[$incident['witness_id']]['name'];?> <?=$usersList[$incident['witness_id']]['surname'];?>
                        <img src="../signatures/ws_<?=$incident['in_id'];?>.png" width="200px">
                    </td>
                    <td>
                        <?=$usersList[$incident['signature_id']]['name'];?> <?=$usersList[$incident['signature_id']]['surname'];?>
                        <img src="../signatures/ps_<?=$incident['in_id'];?>.png" width="200px">
                    </td>
                    <td>
                        <a href="incident.php?id=<?=$incident['in_id'];?>"><?=$incident['name'];?></a>
                    </td>
                </tr>
            <?
            }
            ?>
        </table>