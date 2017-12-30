<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

$resourceId = 18; // Penthrane Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$Incident = new Incident();

$usersList = $User->getList();



$dateFrom = "01-".date("m-Y");
if ($_COOKIE['penthrane_date_from']) {
    $dateFrom = $_COOKIE['penthrane_date_from'];
}
if ($_POST['date_from_d']) {
    $dateFrom = $_POST['date_from_d']."-".$_POST['date_from_m']."-".$_POST['date_from_y'];
}
setcookie("penthrane_date_from", $dateFrom);

$dateTo = date("d-m-Y");
if ($_COOKIE['penthrane_date_to']) {
    $dateTo = $_COOKIE['penthrane_date_to'];
}
if ($_POST['date_to_d']) {
    $dateTo = $_POST['date_to_d']."-".$_POST['date_to_m']."-".$_POST['date_to_y'];
}
setcookie("penthrane_date_to", $dateTo);

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$userId = 'all';

if ($_POST['patroller'] != "all") {
    $userId = $_POST['patroller'];
} else {
    $userId = null;
}
setcookie("penthrane_patroller", $userId);

$userData = $User->getById($_GET['id']);
$report = $Incident->getPenthraneReport($fromDate, $toDate, $userId);

page_header(5, "Penthrane Report");

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
            Patroller:
            <select name="patroller">
                <option value="all">All</option>
                <?
                foreach ($usersList as $user) {
                    ?>
                    <option value="<?=$user['user_id'];?>"
                        <?
                        if ($userId == $user['user_id']) {
                            ?>
                            selected="selected"
                            <?
                        }
                        ?>
                        >
                        <?=$user['name'];?>&nbsp;<?=$user['surname'];?>
                    </option>
                <?
                }
                ?>
            </select>
        </div>
        <input type="submit" class="submit" name="show" value="Show">
        <a class="submit print" target="_blank" href="print_penthrane.php">Print</a>
    </form>
</div>
    <div class="table-responsive">
        <form method="post">
            <table class="table table-bordered">
                <tr>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Patroller Signed</th>
                    <th>View</th>
                </tr>
                <?
                foreach ($report as $incident) {
                    ?>
                    <tr>
                        <td>
                            <?
                            $datet = explode("-", $incident['incident_date']);
                            $thisdate = $datet['2']."-".$datet['1']."-".$datet['0'];
                            echo $thisdate;
                            ?>
                        </td>
                        <td>
                            <?=$incident['penthrane'];?>&nbsp;ml.
                        </td>
                        <td>
                            <?=$usersList[$incident['signature_id']]['name'];?> <?=$usersList[$incident['signature_id']]['surname'];?>
                            <img src="../signatures/ps_<?=$incident['in_id'];?>.png" width="200px">
                        </td>
                        <td>
                            <a href="incident.php?id=<?=$incident['in_id'];?>"><?=$incident['name'];?></a>
                        </td>
                    </tr>
                <?
                }
                ?>
            </table>
        </form>
    </div>
<?

page_footer();

?>