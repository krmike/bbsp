<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

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
if ($_POST['date_from_d']) {
    $dateFrom = $_POST['date_from_d']."-".$_POST['date_from_m']."-".$_POST['date_from_y'];
}
setcookie("run_date_from", $dateFrom);

$dateTo = date("d-m-Y");
if ($_COOKIE['run_date_to']) {
    $dateTo = $_COOKIE['run_date_to'];
}
if ($_POST['date_to_d']) {
    $dateTo = $_POST['date_to_d']."-".$_POST['date_to_m']."-".$_POST['date_to_y'];
}
setcookie("run_date_to", $dateTo);

if ($_POST['run'] != "all") {
    $runId = $_POST['run'];
} else {
    $runId = null;
}

setcookie("run_run", $runId);

if ($_POST['lift'] != "all") {
    $liftId = $_POST['lift'];
} else {
    $liftId = null;
}
setcookie("run_lift", $liftId);

if ($_POST['patroller'] != "all") {
    $userId = $_POST['patroller'];
} else {
    $userId = null;
}
setcookie("run_patroller", $userId);

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$report = $DailyRun->getReport($fromDate, $toDate, $runId, $liftId, $userId);
page_header(5, "Run Conditions Report");
?>

<div>
    <form method="post">
        <div class="input-left">
            From:
            <select id="date_d" class="hours" name="date_from_d">
                <?
                for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateF['0'] == sprintf("%02d", $i)) {
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
            </select>-<select id="date_m" class="hours" name="date_from_m">
                <?
                for ($i = 1; $i <= 12; $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateF['1'] == sprintf("%02d", $i)) {
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
            </select>-<select id="date_y" class="hours" name="date_from_y">
                <?
                for ($i = date("Y") - 90; $i <= date("Y") + 10; $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateF['2'] == sprintf("%02d", $i)) {
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
        <div class="input-left">
            To:
            <select id="date_to_d" class="hours" name="date_to_d">
                <?
                for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateT['0'] == sprintf("%02d", $i)) {
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
            </select>-<select id="date_to_m" class="hours" name="date_to_m">
                <?
                for ($i = 1; $i <= 12; $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateT['1'] == sprintf("%02d", $i)) {
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
            </select>-<select id="date_to_y" class="hours" name="date_to_y">
                <?
                for ($i = date("Y") - 90; $i <= date("Y") + 10; $i++) {
                    ?>
                    <option value="<?=sprintf("%02d", $i);?>"
                        <?
                        if ($dateT['2'] == sprintf("%02d", $i)) {
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
        <div class="input-left">
            Patroller:&nbsp;<select name="patroller">
                <option value="all">All</option>
                <?
                foreach ($usersList as $user) {
                    ?>
                    <option value="<?=$user['user_id']?>" <? if($user['user_id'] == $userId) {
                        ?> selected="selected"<?
                    }?>>
                        <?=$user['name']?>&nbsp;<?=$user['surname'];?>
                    </option>
                <?
                }
                ?>
            </select>
        </div>
        <div class="input-left">
            Lift:&nbsp;<select name="lift" id="lift">
                <option value="all">All</option>
                <?
                foreach ($liftList as $lift) {
                    ?>
                    <option value="<?=$lift['id'];?>" <? if($lift['id'] == $liftId) {
                        ?> selected="selected"<?
                    }?>>
                        <?=$lift['name'];?>
                    </option>
                <?
                }
                ?>
            </select>
        </div>
        <div class="input-left">
            Run:&nbsp;<select name="run">
                <option value="all">All</option>
                <?
                foreach ($runList as $run) {
                    ?>
                    <option class="runs runs<?=$run['lift_id'];?>" value="<?=$run['id'];?>" <? if($run['id'] == $runId) {
                        ?> selected="selected"<?
                    }?>>
                        <?=$run['name'];?>
                    </option>
                <?
                }
                ?>
            </select>
        </div>
        <input type="submit" class="submit" value="Show">
        <a class="submit print" target="_blank" href="print_dr.php">Print</a>
    </form>
</div>

    <div class="table-responsive">
        <table class="table table-bordered">
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
    </div>
<?

page_footer();

?>