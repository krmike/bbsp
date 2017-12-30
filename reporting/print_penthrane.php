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

$resourceId = 18; // Penthrane Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$Penthrane = new Penthrane();

$usersList = $User->getList();
$userTypesList = $User->getTypesList();



$dateFrom = "01-".date("m-Y");
if ($_COOKIE['penthrane_date_from']) {
    $dateFrom = $_COOKIE['penthrane_date_from'];
}

$dateTo = date("d-m-Y");
if ($_COOKIE['penthrane_date_to']) {
    $dateTo = $_COOKIE['penthrane_date_to'];
}

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$operation = $_COOKIE['penthrane_operation'];
$penthrane_operation = null;
if ($operation != 'all') {
    $penthrane_operation = $operation;
}

$userId = $_COOKIE['penthrane_patroller'];
$penthrane_user = null;
if ($userId != 'all') {
    $penthrane_user = $userId;
}

$userData = $User->getById($_GET['id']);
$report = $Penthrane->getReport($fromDate, $toDate, $penthrane_user, $penthrane_operation);


?>
<h4>&nbsp;&nbsp;From <?=$dateFrom;?> To <?=$dateTo;?>.
    <?
    if ($penthrane_user) {
        ?>&nbsp;&nbsp;Patroller: <?=$usersList[$userId]['name'];?>&nbsp;<?=$usersList[$userId]['surname'];?><br/><?
    }
    if ($penthrane_operation) {
        ?>&nbsp;&nbsp;Operation: <?php
        switch($penthrane_operation){
            case 1: echo "Add";
                break;
            case 2: echo "Manually removed";
                break;
            case 3: echo "Used in Incident";
                break;
        }
        ?><br/><?
    }
    ?>
</h4>
<div>
    Stock at <?php echo $dateFrom;?> : <?php echo $report['summary'];?> vile(s).
</div>
        <table style="max-width: 800px;" class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Quantity</th>
                <th>Operation</th>
                <th>Patroller</th>
                <th>Comment</th>
            </tr>
            <?
            foreach ($report['stock'] as $stock) {
                ?>
                <tr>
                    <td>
                        <?
                        echo $stock['date'];
                        ?>
                    </td>
                    <td>
                        <?=$stock['qty'];?>&nbsp;vile(s).
                    </td>
                    <td>
                        <?php
                        switch($stock['operation']){
                            case 1: echo "Add";
                                break;
                            case 2: echo "Manually removed";
                                break;
                            case 3: echo "Used in Incident";
                                break;
                        }
                        ?>
                    </td>
                    <td>
                        <?=$usersList[$stock['user_id']]['name'];?> <?=$usersList[$stock['user_id']]['surname'];?>
                    </td>
                    <td>
                        <?php echo $stock['comment'];?>
                    </td>
                </tr>
                <?
            }
            ?>
        </table>