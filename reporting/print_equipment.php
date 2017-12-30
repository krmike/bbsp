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

$resourceId = 15; // Equipment Management Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$Equip = new Equipment();
$Ambulance = new Ambulance();

$equipmentList = $Equip->getList();
$referralList = $Ambulance->getList();

$waysList = array(
    1 => "Road",
    2 => "Air"
);
$ynList = array(
    1 => "Yes",
    0 => "No"
);

$where = array();

$dateFrom = "01-".date("m-Y");
if ($_COOKIE['equip_date_from']) {
    $dateFrom = $_COOKIE['equip_date_from'];
}

$dateTo = date("d-m-Y");
if ($_COOKIE['equip_date_to']) {
    $dateTo = $_COOKIE['equip_date_to'];
}

$equipmentId = $_COOKIE['equip_equipment'];
$returnYn = $_COOKIE['equip_return'];

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$report = $Equip->getManagement($fromDate, $toDate, $equipmentId, $returnYn);
?>
<h4>&nbsp;&nbsp;From <?=$dateFrom;?> To <?=$dateTo;?>.
    <?
    if ($equipmentId) {
        ?>&nbsp;&nbsp;Equipment: <?=$equipmentList[$equipmentId]['name'];?><?
    }
    if ($returnYn == '0' || $returnYn == '1') {
        ?>&nbsp;&nbsp;Return: <?=$ynList[$returnYn];?><?
    }
    ?>
</h4>
    <table style="max-width: 800px;" class="table table-bordered">
        <tr>
            <th>Date</th>
            <th>Equipment</th>
            <th>Destination</th>
            <th>Returned</th>
        </tr>
        <?
        foreach ($report as $record) {
            ?>
            <tr>
                <td>
                    <?
                    $datet = explode("-", $record['date']);
                    $thisdate = $datet['2']."-".$datet['1']."-".$datet['0'];
                    echo $thisdate;
                    ?>
                </td>
                <td><?=$equipmentList[$record['equipment_id']]['name'];?></td>
                <td><?=$referralList[$record['destination']]['name'];?></td>
                <td><?=$ynList[$record['return']];?></td>
            </tr>
        <?
        }
        ?>
    </table>
