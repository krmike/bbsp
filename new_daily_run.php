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

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);
$liftList = $Lift->getList();
$runList = $Run->getList();
$statuslist = $Status->getList();

if ($_GET['id']) {
    $runData = $DailyRun->getById($_GET['id']);
}

if ($_POST['save']) {
    $date = explode("-", $_POST['date']);
    $rundate = $date['2']."-".$date['1']."-".$date['0'];

    $runTime = $_POST['time_hours'].":".$_POST['time_minutes'];
    $dailyRunData = array(
        "user_id"       => $_COOKIE['user_id'],
        "date"          => $rundate,
        "time"          => $runTime,
        "lift_id"          => $_POST['lift'],
        "run_id"           => $_POST['run'],
        "status_id"        => $_POST['status'],
        "poles"         => $_POST['poles'],
        "signs"         => $_POST['signs'],
        "fences"        => $_POST['fences'],
        "cones"        => $_POST['cones'],
        "comment"       => $_POST['comment']
    );

    if ($_GET['id'] && $runData['date'] == date("Y-m-d")) {
        $DailyRun->updateById($_GET['id'], $dailyRunData);
    }

    if (!$_GET['id']) {
        $dailyRunId = $DailyRun->add($dailyRunData);
    }

    header("location:/daily_run.php");
}

page_header(2);

?>
<form method="post">

    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Date:</th>
                <th>Time:</th>
                <th>Lift:</th>
                <th>Run:</th>
                <th>Status:</th>
                <th>Poles:</th>
                <th>Signs:</th>
                <th>Fences:</th>
                <th>Cones:</th>
                <th>Comment:</th>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="date" value="<?=date("d-m-Y");?>"> <?=date("d-m-Y");?>
                </td>
                <td style="min-width: 121px;">
                    <?
                    $time = explode(":", $runData['time']);
                    ?>
                    <select class="hours" name="time_hours">
                        <?
                        for ($i = 0; $i < 24; $i++) {
                            ?>
                            <option value="<?=sprintf("%02d", $i);?>"
                                <?
                                if ($time['0'] == sprintf("%02d", $i)) {
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
                    </select>:<select class="hours" name="time_minutes">
                        <?
                        for ($i = 0; $i < 60; $i++) {
                            ?>
                            <option value="<?=sprintf("%02d", $i);?>"
                                <?
                                if ($time['1'] == sprintf("%02d", $i)) {
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
                </td>
                <td>
                    <select class="lrs" name="lift" id="lift">
                        <option>Select option</option>
                        <?
                        foreach ($liftList as $lift) {
                            ?>
                            <option value="<?=$lift['id'];?>"
                                <?
                                if ($runData['lift_id'] == $lift['id']) {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >
                                <?=$lift['name'];?>
                            </option>
                        <?
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select class="lrs" name="run">
                        <option>Select option</option>
                        <option>Select Run</option>
                        <?
                        foreach ($runList as $run) {
                            ?>
                            <option class="runs runs<?=$run['lift_id'];?>" value="<?=$run['id'];?>"
                                <?
                                if ($runData['run_id'] == $run['id']) {
                                    ?>
                                    selected="selected"
                                <?
                                }
                                ?>
                                >
                                <?=$run['name'];?>
                            </option>
                        <?
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select class="lrs" name="status">
                        <option>Select option</option>
                        <?
                        foreach ($statuslist as $status) {
                            ?>
                            <option value="<?=$status['id']?>"
                                <?
                                if ($runData['status_id'] == $status['id']) {
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
                    <input class="psfcc" type="text" name="poles" value="<?=$runData['poles'];?>">
                </td>
                <td>
                    <input class="psfcc" type="text" name="signs" value="<?=$runData['signs'];?>">
                </td>
                <td>
                    <input class="psfcc" type="text" name="fences" value="<?=$runData['fences'];?>">
                </td>
                <td>
                    <input class="psfcc" type="text" name="cones" value="<?=$runData['cones'];?>">
                </td>
                <td>
                    <textarea class="comment" name="comment" placeholder="Comment"><?=$runData['comment'];?></textarea>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <input type="submit" class="submit" name="save" value="Save">
    </div>
</form>
    <script>
        dailyRun.init();
    </script>
<?

page_footer();

?>