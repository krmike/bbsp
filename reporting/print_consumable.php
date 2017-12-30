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

$resourceId = 20; // Consumable Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);

$User = new User();
$Equipment = new Equipment();

$equipmentList = $Equipment->getList();


$dateFrom = "01-".date("m-Y");
if ($_COOKIE['consumable_date_from']) {
    $dateFrom = $_COOKIE['consumable_date_from'];
}

$dateTo = date("d-m-Y");
if ($_COOKIE['consumable_date_to']) {
    $dateTo = $_COOKIE['consumable_date_to'];
}

$equipmentType = 'all';
if ($_COOKIE['consumable_equipment']) {
    $equipmentType = $_COOKIE['consumable_equipment'];
}

if ($equipmentType == '2') {
    $equipmentType = '0';
}


$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$report = $Equipment->getConsumableReport($fromDate, $toDate, $equipmentType);

?>
<h4>&nbsp;&nbsp;From <?=$dateFrom;?> To <?=$dateTo;?>.
<?
if ($equipmentType == "1") {
    ?>&nbsp;&nbsp;Consumable Equipment<?
}
if ($equipmentType == "0") {
    ?>&nbsp;&nbsp;Non Consumable Equipment<?
}
?>
</h4>
<table style="max-width: 800px;" class="table table-bordered">
    <tr>
        <th>Equipment</th>
        <th>Amount</th>
    </tr>
    <?
    foreach ($equipmentList as $equipment) {
        if (count($report[$equipment['id']]) > 0) {
            ?>
            <tr>
                <td><?=$equipment['name'];?></td>
                <td><?=$report[$equipment['id']];?></td>
            </tr>
        <?
        }
    }
    ?>
</table>