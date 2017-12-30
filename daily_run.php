<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 22:31
 */

include('config.php');

$resourceId = 3; // Daily Run Conditions

$Access = new Access();
$DB = new Db();
$DailyRun = new DailyRun();
$Lift = new Lift();
$Run = new Run();
$Status = new Status();
$User = new User();

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);
$liftList = $Lift->getList();
$activeLiftList = $Lift->getActiveList();
$runList = $Run->getList();
$activeRunList = $Run->getActiveList();
$statusList = $Status->getList();
$usersList = $User->getList();
$report = $DailyRun->getEditable();

if ($_POST['save']) {
    $date = explode("-", $_POST['date']);
    $rundate = $date['2']."-".$date['1']."-".$date['0'];
    $runTime = $_POST['time_hours'].":".$_POST['time_minutes'];
    for ($i = 0; $i < count($_POST['run']); $i++) {
        $dailyRunData = array(
            "user_id"       => $_COOKIE['user_id'],
            "date"          => $rundate,
            "time"          => $runTime,
            "lift_id"          => $_POST['lift'][$i],
            "run_id"           => $_POST['run'][$i],
            "status_id"        => $_POST['status'][$i],
            "poles"         => $_POST['poles'][$i],
            "signs"         => $_POST['signs'][$i],
            "fences"        => $_POST['fences'][$i],
            "cones"        => $_POST['cones'][$i],
            "comment"       => $_POST['comment'][$i]
        );

        $dailyRunId = $DailyRun->add($dailyRunData);
    }


    header("location:/daily_run.php");
}

page_header(2);

?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <?
                foreach ($report as $date => $allTime) {
                    foreach ($allTime as $time => $timeReport) {
                        ?>
                        <tr>
                            <th colspan="3">
                                <?=$usersList[$timeReport['user_id']]['name'];?>
                                <?=$usersList[$timeReport['user_id']]['surname'];?>

                            </th>
                            <?
                            if (count($timeReport) - $g == count($timeReport)) {
                                ?>
                                <th colspan="3">Date:
                                    <?
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
                                <th>&nbsp;</th>
                            <?
                            }
                            ?>
                            <th colspan="4">Time:&nbsp;<?=$time;?></th>
                        </tr>
                        <tr>
                            <th>Lift</th>
                            <th>Run</th>
                            <th>Status</th>
                            <th>P</th>
                            <th>S</th>
                            <th>F</th>
                            <th>C</th>
                            <th>Comment</th>
                            <th>Details</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?
                        foreach ($timeReport as $liftId => $liftReport) {
                            $i = 0;

                            foreach ($liftReport as $runId => $runReport) {
                                ?>
                                <tr>

                                    <?
                                    if (count($liftReport) - $i == count($liftReport)){
                                        ?>
                                        <td>
                                            <span hidden class="lift-id"><?=$liftId;?></span>
                                            <?=$liftList[$liftId]['name'];?>
                                        </td>
                                    <?
                                    } else {
                                        ?>
                                        <td>&nbsp;</td>
                                    <?
                                    }
                                    ?>
                                    <td>
                                        <span hidden class="run-id"><?=$runId;?></span>
                                        <?=$runList[$runId]['name'];?>
                                    </td>
                                    <td>
                                        <span hidden class="status-id"><?=$runReport['status_id'];?></span>
                                        <?=$statusList[$runReport['status_id']]['name'];?>
                                    </td>
                                    <td>
                                        <span hidden class="poles"><?=$runReport['poles'];?></span>
                                        <?=$runReport['poles'];?>
                                    </td>
                                    <td>
                                        <span hidden class="signs"><?=$runReport['signs'];?></span>
                                        <?=$runReport['signs'];?>
                                    </td>
                                    <td>
                                        <span hidden class="fences"><?=$runReport['fences'];?></span>
                                        <?=$runReport['fences'];?>
                                    </td>
                                    <td>
                                        <span hidden class="cones"><?=$runReport['cones'];?></span>
                                        <?=$runReport['cones'];?>
                                    </td>
                                    <td>
                                        <span hidden class="comment"><?=$runReport['comment'];?></span>
                                        <?=$runReport['comment'];?>
                                    </td>
                                    <td><a href="new_daily_run.php?id=<?=$runReport['id'];?>">Edit</a></td>
                                    <td><a class="duplicate-run">Duplicate</a></td>
                                </tr>
                                <?
                                $i++;
                            }
                        }
                    }
                }
            ?>
            <form method="post">
            <tr class="new-run">
                            <th colspan="3" class="openingTr">
                                Opening Report <input type="checkbox" checked name="opening[]" id="openingReport">
                            </th>
                            <th colspan="3">Date: <?=date("d-m-Y");?><input name="date" type="hidden" value="<?=date("d-m-Y");?>"></th>
                            <th colspan="4" class="timeTr">Time:
                                <select id="timeHours" class="hours" name="time_hours">
                                    <?
                                    for ($i = 0; $i < 24; $i++) {
                                        ?>
                                        <option value="<?=sprintf("%02d", $i);?>"
                                            <?
                                            if (sprintf("%02d", $i) == '09') {
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
                                </select>:<select id="timeMinutes" class="hours" name="time_minutes">
                                    <?
                                    for ($i = 0; $i < 60; $i++) {
                                        ?>
                                        <option value="<?=sprintf("%02d", $i);?>"
                                            <?
                                            if (sprintf("%02d", $i) == '00') {
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
                        </tr>
            <?
            foreach ($activeLiftList as $lift) {
                if (count($activeRunList[$lift['id']]) > 0) {
                    foreach ($activeRunList[$lift['id']] as $run) {
                        ?>

                        <tr class="new-run">
                            <th>Lift</th>
                            <th>Run</th>
                            <th>Status</th>
                            <th>P</th>
                            <th>S</th>
                            <th>F</th>
                            <th>C</th>
                            <th colspan="3">Comment</th>
                        </tr>
                        <tr class="new-run">
                            <td class="liftTd">
                                <select required="required" class="lrs lift" name="lift[]">
                                    <option></option>
                                    <?
                                    foreach ($liftList as $liftL) {
                                        ?>
                                        <option value="<?=$liftL['id'];?>"
                                                <?
                                                if ($lift['id'] == $liftL['id']) {
                                                    ?>
                                                    selected="selected"
                                                <?
                                                }
                                                ?>
                                            >
                                            <?=$liftL['name'];?>
                                        </option>
                                    <?
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="runTd">
                                <select required="required" class="lrs run" name="run[]">
                                    <option></option>
                                    <?
                                    foreach ($runList as $runL) {
                                        ?>
                                        <option class="runs runs<?=$runL['lift_id'];?>" value="<?=$runL['id'];?>"
                                            <?
                                            if ($run['id'] == $runL['id']) {
                                                ?>
                                                selected="selected"
                                            <?
                                            }
                                            ?>
                                            >
                                            <?=$runL['name'];?>
                                        </option>
                                    <?
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select required="required" class="lrs" id="status" name="status[]">
                                    <option></option>
                                    <?
                                    foreach ($statusList as $status) {
                                        ?>
                                        <option value="<?=$status['id']?>"
                                            <?
                                            if ($status['id'] == 1) {
                                                ?>
                                                selected="selected"
                                            <?
                                            }
                                            ?>
                                            >
                                            <?=$status['name'];?>
                                        </option>
                                    <?
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input required="required" class="psfcc poles" type="text" name="poles[]" value="0">
                            </td>
                            <td>
                                <input required="required" class="psfcc signs" type="text" name="signs[]" value="0">
                            </td>
                            <td>
                                <input required="required" class="psfcc fences" type="text" name="fences[]" value="0">
                            </td>
                            <td>
                                <input required="required" class="psfcc cones" type="text" name="cones[]" value="0">
                            </td>
                            <td colspan="3">
                                <textarea class="run-comment comment" name="comment[]" placeholder="Comment"></textarea>
                            </td>
                        </tr>
                    <?
                    }
                }
            }
            if (count($activeLiftList) > 0) {
                ?>
                <tr class="new-run">
                    <td colspan="10">
                        <input type="submit" id="submit" class="submit" name="save" value="Save">
                    </td>
                </tr>
            <?
            }
            ?>
        </table>
    </div>
<a id="add-run"> Add Runs</a>

</form>
    <script>
        dailyRun.init();
    </script>
<?

page_footer();

?>