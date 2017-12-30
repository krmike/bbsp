<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

$resourceId = 9; // Sign In / Sign Out Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);

$SignIn = new SignIn();
$User = new User();
$Equipment = new Equipment();
$Radio = new Radio();

$usersList = $User->getList();
$equipmentList = $Equipment->getPEList();
$radioList = $Radio->getList();

$signTypes = array(
    1 => "Sign In",
    2 => "Sign Out"
);

$dateFrom = "01-".date("m-Y");
if ($_COOKIE['signin_date_from']) {
    $dateFrom = $_COOKIE['signin_date_from'];
}
if ($_POST['date_from_d']) {
    $dateFrom = $_POST['date_from_d']."-".$_POST['date_from_m']."-".$_POST['date_from_y'];
}
setcookie("signin_date_from", $dateFrom);

$dateTo = date("d-m-Y");
if ($_COOKIE['signin_date_to']) {
    $dateTo = $_COOKIE['signin_date_to'];
}
if ($_POST['date_to_d']) {
    $dateTo = $_POST['date_to_d']."-".$_POST['date_to_m']."-".$_POST['date_to_y'];
}
setcookie("signin_date_to", $dateTo);

if ($_POST['patroller'] != "all") {
    $userId = $_POST['patroller'];
} else {
    $userId = null;
}
setcookie("signin_user", $userId);

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$report = $SignIn->getReport($fromDate, $toDate, $userId);
page_header(5, "Sign In Report");
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
        <input type="submit" class="submit" value="Show">
        <a class="submit print" target="_blank" href="print_si.php">Print</a>
    </form>
</div>
<?
if ($userId) {
    ?>
    <h4>
        <?=$usersList[$userId]['name']?>&nbsp;<?=$usersList[$userId]['surname'];?> signed in <?=$report['count'];?> time(s).
    </h4>
<?
}
?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>
                    Date
                </th>
                <th>
                    Time
                </th>
                <th>
                    Sign Type
                </th>
                <th>
                    Patroller
                </th>
                <th>
                    Equipment
                </th>
                <th>
                    Radio
                </th>
                <th>
                    Penthrane
                </th>
                <th>
                    Penthrane<br>Used
                </th>
                <th>
                    Penthrane<br>Returned
                </th>
            </tr>
            <?
            foreach ($report['report'] as $sign) {
                ?>
                <tr>
                    <td>
                        <?
                        $datet = explode("-", $sign['date']);
                        $thisdate = $datet['2']."-".$datet['1']."-".$datet['0'];
                        echo $thisdate;
                        ?>
                    </td>
                    <td>
                        <?=$sign['time'];?>
                    </td>
                    <td>
                        <?=$signTypes[$sign['sign_type']];?>
                    </td>
                    <td>
                        <?=$usersList[$sign['patroller_id']]['name'];?>
                        <?=$usersList[$sign['patroller_id']]['surname'];?>
                    </td>
                    <td>
                        <?=$equipmentList[$sign['equipment_id']]['name'];?>
                    </td>
                    <td>
                        <?=$radioList[$sign['radio_id']]['name'];?>
                    </td>
                    <td>
                        <?
                        if ($sign['penthrane']) {
                            ?>
                            <?=$sign['penthrane']?> ml
                        <?
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        if ($sign['penthrane_used']) {
                            ?>
                            <?=$sign['penthrane_used']?> ml
                        <?
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        if ($sign['penthrane_returned']) {
                            ?>
                            <?=$sign['penthrane_returned']?> ml
                        <?
                        }
                        ?>
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