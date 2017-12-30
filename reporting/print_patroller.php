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

$resourceId = 13; // Incidents Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$Training = new Training();
$SignIn = new SignIn();
$User = new User();
$Equipment = new Equipment();
$Radio = new Radio();
$Role = new Role();
$Incident = new Incident();

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

$dateFrom = "01-".date("m-Y");
if ($_COOKIE['patroller_date_from']) {
    $dateFrom = $_COOKIE['patroller_date_from'];
}

$dateTo = date("d-m-Y");
if ($_COOKIE['patroller_date_to']) {
    $dateTo = $_COOKIE['patroller_date_to'];
}

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$userData = $User->getById($_GET['id']);
$report = $Training->getReport($fromDate, $toDate, $userData['user_id']);
$signReport = $SignIn->getReport($fromDate, $toDate, $userData['user_id']);
$incidentReport = $Incident->getPatrollerIncidents($fromDate, $toDate, $userData['user_id']);
$equipmentReport = $Incident->getPatrollerNCEquipment($fromDate, $toDate, $userData['user_id']);

?>
<h4>&nbsp;&nbsp;From <?=$dateFrom;?> To <?=$dateTo;?>.</h4>
<table style="max-width: 800px;" class="table table-bordered">
    <tr>
        <th>Patroller:</th>
        <th><?=$userData['name'];?> <?=$userData['surname'];?></th>
    </tr>
    <tr>
        <td>User type: </td>
        <td><?=$userTypesList[$userData['user_type']]['user_type_name'];?></td>
    </tr>
    <tr>
        <td>E-mail: </td>
        <td><?=$userData['email'];?></td>
    </tr>
    <tr>
        <td>ASPA Id: </td>
        <td><?=$userData['aspa_id'];?></td>
    </tr>
    <tr>
        <td>WWC / VIT: </td>
        <td><?=$userData['wwc_vit'];?></td>
    </tr>
</table>
<h4>&nbsp;&nbsp;Training</h4>
    <?
    foreach ($report as $training_category_id => $trainings) {
        ?>
        <div class="training-title">
            <b>
                <?=$categoriesList[$training_category_id]['name'];?>
            </b>
        </div>
        <table style="max-width: 800px;" class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Type</th>
                <th>Comment</th>
                <?
                if ($userData['user_type'] > $_COOKIE['user_type']){
                    ?><th>&nbsp;</th><?
                }
                ?>
            </tr>
            <?php
            foreach ($trainings as $train) {
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
                        <?=$typesList[$train['type_id']]['name'];?>
                    </td>
                    <td>
                        <?php echo $train['training_type'];?>
                    </td>
                    <td>
                        <?=$train['comment'];?>
                    </td>
                    <?
                    if ($userData['user_type'] > $_COOKIE['user_type']){
                        ?>
                        <td>
                            <?=$train['trainer_comment'];?>
                        </td>
                        <?
                    }
                    ?>
                </tr>
                <?
            }
            ?>
        </table>
        <?php
    }
    ?>
<h4>&nbsp;&nbsp;Sign In / Sign Out</h4>
<table style="max-width: 800px;" class="table table-bordered">
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

<h4>&nbsp;&nbsp;Incidents</h4>
<table style="max-width: 800px;" class="table table-bordered">
    <tr>
        <th>Date</th>
        <th>Role</th>
    </tr>
    <?
    foreach ($incidentReport as $incident) {
        ?>
        <tr>
            <td><?=$incident['incident_date'];?> <?=$incident['incident_time'];?></td>
            <td><?=$rolesList[$incident['role_id']]['name'];?></td>
        </tr>
    <?
    }
    ?>
</table>
<h4>&nbsp;&nbsp;Major Equipment</h4>
<table style="max-width: 800px;" class="table table-bordered">
    <tr>
        <th>Date</th>
        <th>Equipment</th>
    </tr>
    <?
    foreach ($equipmentReport as $equipment) {
        ?>
        <tr>
            <td><?=$equipment['incident_date'];?> <?=$equipment['incident_time'];?></td>
            <td><?=$equipment['equipment_name'];?></td>
        </tr>
    <?
    }
    ?>
</table>