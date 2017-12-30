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

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);

$incidentList = $Incident->getFreeIncidents();

if ($_GET['id']) {
    $logData = $DailyLog->getById($_GET['id']);
}

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

    if ($_GET['id'] && $logData['daily_log_timestamp'] > date("Y-m-d H:i:s", time() - 172800)) {
        $DailyLog->updateById($_GET['id'], $dailyLogData);

        $SQL = "SELECT id FROM incident WHERE daily_log_id = ".$_GET['id']." ";
        $result = $DB->sql_query($SQL);
        for ($incidents = array(); $row = mysqli_fetch_assoc($result); $incidents[] = $row['id']);
        foreach ($incidents as $incident) {
            $SQL = "UPDATE incident SET daily_log_id = NULL WHERE id = ".$incident." ";
        }

        for ($i = 0; $i < count($_POST['incident']); $i++) {
            $Incident->updateById($_POST['incident'][$i], array(
                "daily_log_id" => $_GET['id']
            ));
        }
    }
    header("location:/daily_log.php");
}

page_header(4);

?>
<form method="post"  enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Date:</th>
                <th>Time:</th>
                <th>Comment:</th>
                <th>Incident:</th>
            </tr>
            <tr>
                <td>
                    <?
                    $date = explode("-", $logData['date']);
                    ?>
                    <select id="date_d" class="hours" name="date_d">
                        <?
                        for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                            ?>
                            <option value="<?=sprintf("%02d", $i);?>"
                                <?
                                if ($date['2'] == sprintf("%02d", $i)) {
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
                                if ($date['1'] == sprintf("%02d", $i)) {
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
                                if ($date['0'] == sprintf("%02d", $i)) {
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
                    <?
                    $time = explode(":", $logData['time']);
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
                    <textarea name="comment"><?=$logData['comment'];?></textarea>
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
            </tr>
        </table>
    </div>

    <div>
        <input type="submit" class="submit" name="save" value="Log">
    </div>
</form>

    <script>
        dailyLog.init();
    </script>
<?

page_footer();

?>