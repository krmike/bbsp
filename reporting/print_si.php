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

$dateTo = date("d-m-Y");
if ($_COOKIE['signin_date_to']) {
    $dateTo = $_COOKIE['signin_date_to'];
}

if ($_POST['patroller'] != "all") {
    $userId = $_POST['patroller'];
} else {
    $userId = null;
}

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$userId = $_COOKIE["signin_user"];

$report = $SignIn->getReport($fromDate, $toDate, $userId);


?>

<h4>&nbsp;&nbsp;From <?=$dateFrom;?> To <?=$dateTo;?>.
    <?
    if ($userId) {
        ?>&nbsp;&nbsp;Patroller: <?=$usersList[$userId]['name'];?>&nbsp;<?=$usersList[$userId]['surname'];?><?
    }
    ?>
</h4>
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