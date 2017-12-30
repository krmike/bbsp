<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

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
if ($_POST['date_from_d']) {
    $dateFrom = $_POST['date_from_d']."-".$_POST['date_from_m']."-".$_POST['date_from_y'];
}
setcookie("daily_log_date_from", $dateFrom);

$dateTo = date("d-m-Y");
if ($_COOKIE['daily_log_date_to']) {
    $dateTo = $_COOKIE['daily_log_date_to'];
}
if ($_POST['date_to_d']) {
    $dateTo = $_POST['date_to_d']."-".$_POST['date_to_m']."-".$_POST['date_to_y'];
}
setcookie("daily_log_date_to", $dateTo);

if ($_POST['patroller'] != "all") {
    $userId = $_POST['patroller'];
} else {
    $userId = null;
}

setcookie("daily_log_user_id", $userId);

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$report = $DailyLog->getReport($fromDate, $toDate, $userId);
page_header(5, "Communications Log");
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
        <input type="submit" class="submit" value="Show">
        <a class="submit print" target="_blank" href="print_dl.php">Print</a>
    </form>
</div>

    <div class="table-responsive">
        <table class="table table-bordered">
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
                            <a href="incident.php?id=<?=$incident['id'];?>"><?=$incident['name'];?></a><br>
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
    </div>
<?

page_footer();

?>