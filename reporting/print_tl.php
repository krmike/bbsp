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

$resourceId = 12; // Training Log Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$Training = new Training();

$usersList = $User->getList();
$categoriesList = $Training->getCategoriesList();
$typesList = $Training->getTypesList();



$dateFrom = "01-".date("m-Y");
if ($_COOKIE['training_log_date_from']) {
    $dateFrom = $_COOKIE['training_log_date_from'];
}

$dateTo = date("d-m-Y");
if ($_COOKIE['training_log_date_to']) {
    $dateTo = $_COOKIE['training_log_date_to'];
}

$userId = $_COOKIE['training_log_patroller'];
$categoryId = $_COOKIE['training_log_category'];
$typeId = $_COOKIE['training_log_type'];

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$report = $Training->getReport($fromDate, $toDate, $userId, $categoryId, $typeId);
?>

<h4>&nbsp;&nbsp;From <?=$dateFrom;?> To <?=$dateTo;?>.
    <?
    if ($userId) {
        ?>&nbsp;&nbsp;Patroller: <?=$usersList[$userId]['name'];?>&nbsp;<?=$usersList[$userId]['surname'];?>.<?
    }
    if ($categoryId) {
        ?>&nbsp;&nbsp;Category: <?=$categoriesList[$categoryId]['name'];?>.<?
    }
    if ($typeId) {
        ?>&nbsp;&nbsp;Lift: <?=$typesList[$typeId]['name'];?>.<?
    }
    ?>
</h4>
<table style="max-width: 800px;" class="table table-bordered">
    <tr>
        <th>Date</th>
        <th>Category</th>
        <th>Type</th>
        <th>Patrollers</th>
    </tr>
    <?
    foreach ($report as $log) {
        ?>
        <tr>
            <td>
                <?
                $datet = explode("-", $log['date']);
                $thisdate = $datet['2']."-".$datet['1']."-".$datet['0'];
                echo $thisdate;
                ?>
            </td>
            <td>
                <?=$categoriesList[$log['category_id']]['name'];?>
            </td>
            <td>
                <?=$typesList[$log['type_id']]['name'];?>
            </td>
            <td>
                <?
                $patrollers = $Training->getLogPatrollers($log['id']);
                foreach ($patrollers as $patroller) {
                    ?>
                    <?=$usersList[$patroller['patroller_id']]['name']?>&nbsp;<?=$usersList[$patroller['patroller_id']]['surname'];?><br>
                <?
                }
                ?>
            </td>
        </tr>
    <?
    }
    ?>
</table>