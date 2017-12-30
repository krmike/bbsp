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

$resourceId = 11; // Daily Log Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$DailyLog = new Dailylog();

$usersList = $User->getList();



$dateFrom = "01-".date("m-Y");
if ($_COOKIE['daily_log_date_from']) {
    $dateFrom = $_COOKIE['daily_log_date_from'];
}

$dateTo = date("d-m-Y");
if ($_COOKIE['daily_log_date_to']) {
    $dateTo = $_COOKIE['daily_log_date_to'];
}

if ($_POST['patroller'] != "all") {
    $userId = $_POST['patroller'];
} else {
    $userId = null;
}


$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$userId = $_COOKIE['daily_log_user_id'];

$report = $DailyLog->getReport($fromDate, $toDate, $userId);
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
                <th>Patroller</th>
                <th>Date</th>
                <th>Time</th>
                <th>Incident</th>
                <th>Comment</th>
            </tr>
            <?
            foreach ($report as $log) {
                ?>
                <tr>
                    <td>
                        <?=$usersList[$log['user_id']]['name'];?>
                        <?=$usersList[$log['user_id']]['surname'];?>
                    </td>
                    <td>
                        <?
                        $date = explode("-", $log['date']);
                        $thisdate = $date['2']."-".$date['1']."-".$date['0'];
                        echo $thisdate;
                        ?>
                    </td>
                    <td>
                        <?=$log['time'];?>
                    </td>
                    <td>
                        <?
                        $incidents = $DailyLog->getLogIncidents($log['id']);
                        foreach ($incidents as $incident) {
                            ?>
                            <?=$incident['name'];?><br>
                        <?
                        }
                        ?>
                    </td>
                    <td>
                        <?=$log['comment'];?>
                    </td>
                </tr>
            <?
            }
            ?>
        </table>