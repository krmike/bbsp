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

$resourceId = 10; // Daily Run Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$DailyRun = new DailyRun();
$Lift = new Lift();
$Run = new Run();
$Status = new Status();

$liftList = $Lift->getList();
$runList = $Run->getList();
$statusList = $Status->getList();
$usersList = $User->getList();



$dateFrom = "01-".date("m-Y");
if ($_COOKIE['run_date_from']) {
    $dateFrom = $_COOKIE['run_date_from'];
}

$dateTo = date("d-m-Y");
if ($_COOKIE['run_date_to']) {
    $dateTo = $_COOKIE['run_date_to'];
}

$userId = $_COOKIE['run_patroller'];
$liftId = $_COOKIE['run_lift'];
$runId = $_COOKIE['run_run'];

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$report = $DailyRun->getReport($fromDate, $toDate, $runId, $liftId, $userId);
?>
<h4>&nbsp;&nbsp;From <?=$dateFrom;?> To <?=$dateTo;?>.
    <?
    if ($userId) {
        ?>&nbsp;&nbsp;Patroller: <?=$usersList[$userId]['name'];?>&nbsp;<?=$usersList[$userId]['surname'];?><?
    }
    if ($runId) {
        ?>&nbsp;&nbsp;Run: <?=$runList[$runId]['name'];?><?
    }
    if ($liftId) {
        ?>&nbsp;&nbsp;Lift: <?=$liftList[$liftId]['name'];?><?
    }
    ?>
</h4>
    <table style="max-width: 800px;" class="table table-bordered">
        <?
        foreach ($report as $date => $dateReport) {
            $g = 0;

            foreach ($dateReport as $time => $timeReport) {
                ?>
                <tr>
                    <th>&nbsp;</th>
                    <?
                    if (count($timeReport) - $g == count($timeReport)) {
                        ?>
                        <th>Date:</th>
                        <th><?
                            $datet = explode("-", $date);
                            $thisdate = $datet['2']."-".$datet['1']."-".$datet['0'];
                            echo $thisdate;
                            ?>

                        </th>
                    <?
                    } else {
                        ?>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    <?
                    }
                    ?>
                    <th colspan="6">Time:&nbsp;<?=$time;?></th>
                </tr>
                <tr>
                    <th>Patroller:</th>
                    <th>Lift</th>
                    <th>Run</th>
                    <th>Status</th>
                    <th>P</th>
                    <th>S</th>
                    <th>F</th>
                    <th>C</th>
                    <th>Comment</th>
                </tr>
                <?
                foreach ($timeReport as $liftId => $liftReport) {
                    $i = 0;

                    foreach ($liftReport as $runId => $runReport) {
                        ?>
                        <tr>
                            <td>
                                <?=$usersList[$runReport['user_id']]['name'];?>
                                <?=$usersList[$runReport['user_id']]['surname'];?>
                            </td>

                            <?
                            if (count($liftReport) - $i == count($liftReport)){
                                ?>
                                <td><?=$liftList[$liftId]['name'];?></td>
                            <?
                            } else {
                                ?>
                                <td>&nbsp;</td>
                            <?
                            }
                            ?>
                            <td><?=$runList[$runId]['name'];?></td>
                            <td><?=$statusList[$runReport['status_id']]['name'];?></td>
                            <td><?=$runReport['poles'];?></td>
                            <td><?=$runReport['signs'];?></td>
                            <td><?=$runReport['fences'];?></td>
                            <td><?=$runReport['cones'];?></td>
                            <td><?=$runReport['comment'];?></td>
                        </tr>
                        <?
                        $i++;
                    }
                }
                $g++;
            }
            ?>
        <?
        }
        ?>
    </table>