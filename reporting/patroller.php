<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

$resourceId = 13; // Incidents Report

chk_auth();

$Access = new Access();
if ($_GET['id'] != $_COOKIE['user_id']) {
    $Access->checkPageAccess($_COOKIE['user_type'], $resourceId);
}


$User = new User();
$Training = new Training();
$SignIn = new SignIn();
$User = new User();
$Equipment = new Equipment();
$Radio = new Radio();
$Role = new Role();
$Incident = new Incident();
$Injury = new Injury();

$signTypes = array(
    1 => "Sign In",
    2 => "Sign Out"
);

$equipmentList = $Equipment->getPEList();
$radioList = $Radio->getList();
$rolesList = $Role->getList();
$usersList = $User->getList();
$userTypesList = $User->getTypesList();
$categoriesList = $Training->getCategoriesList();
$typesList = $Training->getTypesList();
$injuryTypesList = $Injury->getTypesList();

if ($_POST['save']) {
    $trainerComments = $_POST['trainer_comment'];
    foreach ($trainerComments as $recId => $comment) {
        $Training->updateTrainerComment($recId, $comment);
    }
}


$dateFrom = "01-".date("m-Y");
if ($_COOKIE['patroller_date_from']) {
    $dateFrom = $_COOKIE['patroller_date_from'];
}
if ($_POST['date_from_d']) {
    $dateFrom = $_POST['date_from_d']."-".$_POST['date_from_m']."-".$_POST['date_from_y'];
}
setcookie("patroller_date_from", $dateFrom);

$dateTo = date("d-m-Y");
if ($_COOKIE['patroller_date_to']) {
    $dateTo = $_COOKIE['patroller_date_to'];
}
if ($_POST['date_to_d']) {
    $dateTo = $_POST['date_to_d']."-".$_POST['date_to_m']."-".$_POST['date_to_y'];
}
setcookie("patroller_date_to", $dateTo);

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$userData = $User->getById($_GET['id']);
$report = $Training->getReport($fromDate, $toDate, $userData['user_id']);
$signReport = $SignIn->getReport($fromDate, $toDate, $userData['user_id']);
$incidentReport = $Incident->getPatrollerIncidents($fromDate, $toDate, $userData['user_id']);
$equipmentReport = $Incident->getPatrollerNCEquipment($fromDate, $toDate, $userData['user_id']);

page_header(5, "Patroller Profile");

?>

Patroller: <?=$userData['name'];?> <?=$userData['surname'];?><br>
User type: <?=$userTypesList[$userData['user_type']]['user_type_name'];?><br>
E-mail: <?=$userData['email'];?><br>
ASPA Id: <?=$userData['aspa_id'];?><br>
WWC / VIT: <?=$userData['wwc_vit'];?>
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
        <input type="submit" class="submit" name="show" value="Show">
        <a class="submit print" target="_blank" href="print_patroller.php?id=<?=$userData['user_id'];?>">Print</a>
    </form>
</div>
    <h4>Training</h4>
    <div class="table-responsive">
        <form method="post">
            <table class="table table-bordered">
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Comment</th>
                    <?
                    if ($userData['user_type'] > $_COOKIE['user_type']){
                        ?><th>&nbsp;</th><?
                    }
                    ?>
                </tr>
                <?
                foreach ($report as $train) {
                    ?>
                    <tr>
                        <td>
                            <?
                            $datet = explode("-", $train['date']);
                            $thisdate = $datet['2']."-".$datet['1']."-".$datet['0'];
                            echo $thisdate;
                            ?>
                        </td>
                        <td>
                            <?=$categoriesList[$train['category_id']]['name'];?>
                        </td>
                        <td>
                            <?=$typesList[$train['type_id']]['name'];?>
                        </td>
                        <td>
                            <?=$train['comment'];?>
                        </td>
                        <?
                        if ($userData['user_type'] > $_COOKIE['user_type']){
                            ?>
                            <td>
                                <textarea name="trainer_comment[<?=$train['record']?>]"><?=$train['trainer_comment'];?></textarea>
                            </td>
                        <?
                        }
                        ?>
                    </tr>
                <?
                }
                ?>
                <?
                if ($userData['user_type'] > $_COOKIE['user_type']) {
                    ?>
                <tr>
                    <td colspan="4">

                    </td>
                    <td>

                        <input type="submit" class="submit" name="save" value="Save">
                    </td>
                </tr>
                <?
                }
                ?>
            </table>
        </form>
    </div>
<h4>Sign In / Sign Out</h4>
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
            foreach ($signReport['report'] as $sign) {
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

    <h4>Incidents</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Role</th>
                <th>Injury Types</th>
                <th>&nbsp;</th>
            </tr>
            <?
            foreach ($incidentReport as $incident) {
                ?>
                <tr>
                    <td><?=$incident['incident_date'];?> <?=$incident['incident_time'];?></td>
                    <td><?=$rolesList[$incident['role_id']]['name'];?></td>
                    <td>
                        <?
                        $injuryTypes = $Incident->getIncidentInjuryTypes($incident['incident_id']);
                        foreach ($injuryTypes as $type) {
                            ?>
                            <?=$injuryTypesList[$type['type_id']]['name'];?><br>
                        <?
                        }
                        ?>
                    </td>
                    <td>
                        <?
                        if ($Access->canAccess($_COOKIE['user_type'], 13)) {
                            ?>
                            <a href="incident.php?id=<?=$incident['incident_id'];?>">
                                <?=$incident['name'];?>
                            </a>
                        <?
                        } else {
                            ?>
                            <a href="sanitized.php?id=<?=$incident['incident_id'];?>">
                                Sanitized
                            </a>
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
    <h4>Major Equipment</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Equipment</th>
                <th>&nbsp;</th>
            </tr>
            <?
            foreach ($equipmentReport as $equipment) {
                ?>
                <tr>
                    <td><?=$equipment['incident_date'];?> <?=$equipment['incident_time'];?></td>
                    <td><?=$equipment['equipment_name'];?></td>
                    <td>
                        <?
                        if ($Access->canAccess($_COOKIE['user_type'], 13)) {
                            ?>
                            <a href="incident.php?id=<?=$equipment['incident_id'];?>">
                                <?=$equipment['incident_name'];?>
                            </a>
                        <?
                        } else {
                            ?>
                            <a href="sanitized.php?id=<?=$equipment['incident_id'];?>">
                                Sanitized
                            </a>
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