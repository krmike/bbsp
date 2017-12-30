<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 22:31
 */

include('config.php');

$resourceId = 5; // Daily Log

$Access = new Access();
$DB = new Db();
$DailyLog = new DailyLog();
$Incident = new Incident();
$User = new User();

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);
if ($_POST['save']) {

    $date = $_POST['date_y']."-".$_POST['date_m']."-".$_POST['date_d'];

    $time = $_POST['time_hours'].":".$_POST['time_minutes'];
    $timestamp = $date." ".$time.":00";

    $dailyLogData = array(
        "daily_log_timestamp"   => $timestamp,
        "user_id"               => $_COOKIE['user_id'],
        "date"                  => $date,
        "time"                  => $time,
        "comment"               => $_POST['comment']
    );

    $dailyLogId = $DailyLog->add($dailyLogData);
    for ($i = 0; $i < count($_POST['incident']); $i++) {
        $Incident->updateById($_POST['incident'][$i], array(
            "daily_log_id" => $dailyLogId
        ));
    }
    header("location:/daily_log.php");
}

$usersList = $User->getList();
$report = $DailyLog->getEditable();
$incidentList = $Incident->getFreeIncidents();



page_header(4);
?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Patroller</th>
                <th>Date</th>
                <th>Time</th>
                <th>Comment</th>
                <th>Incident</th>
                <th>Details</th>
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
                        <?=$log['comment'];?>
                    </td>
                    <td>
                        <?
                        $incidents = $DailyLog->getLogIncidents($log['id']);
                        foreach ($incidents as $incident) {
                            ?>
                            <a href="incident.php?id=<?=$incident['id'];?>"><?=$incident['name'];?></a><br>
                        <?
                        }
                        ?>
                    </td>
                    <td>
                        <a href="new_daily_log.php?id=<?=$log['id']?>">Edit</a>
                    </td>
                </tr>

            <?
            }
            ?>
            <form method="post">
                <tr class="new-log">
                    <td>
                        <input type="hidden" name="user" value="<?=$_COOKIE['user_id'];?>">
                        <?=$usersList[$_COOKIE['user_id']]['name'];?>
                        <?=$usersList[$_COOKIE['user_id']]['surname'];?>
                    </td>
                    <td>
                        <select id="date_d" class="hours" name="date_d">
                            <?
                                for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                                    ?>
                                    <option value="<?=sprintf("%02d", $i);?>"
                                        <?
                                        if (date("d") == sprintf("%02d", $i)) {
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
                        </select>-<select id="date_m" class="hours" name="date_m">
                            <?
                            for ($i = 1; $i <= 12; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if (date("m") == sprintf("%02d", $i)) {
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
                        </select>-<select id="date_y" class="hours" name="date_y">
                            <?
                            for ($i = date("Y") - 90; $i <= date("Y") + 10; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if (date("Y") == sprintf("%02d", $i)) {
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
                        <select class="hours" name="time_hours">
                            <?
                            for ($i = 0; $i < 24; $i++) {
                                ?>
                                <option value="<?=sprintf("%02d", $i);?>"
                                    <?
                                    if (date("H") == sprintf("%02d", $i)) {
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
                                    if (date("i") == sprintf("%02d", $i)) {
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
                        <textarea name="comment"></textarea>
                    </td>
                    <td>
                        <select id="daily_log_incidents" multiple="multiple" name="incident[]">
                            <option>Select option</option>
                            <?
                            foreach ($incidentList as $incident) {
                                ?>
                                <option value="<?=$incident['id'];?>">
                                    <?=$incident['name'];?>
                                </option>
                            <?
                            }
                            ?>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>
        </table>
    </div>
<a id="add-log">Add communication log</a>
<input type="submit" id="submit" class="submit" name="save" value="Save">
</form>
    <script>
        dailyLog.init();
    </script>
<?

page_footer();

?>