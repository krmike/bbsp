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
$Penthrane = new Penthrane();

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
if ($_COOKIE['penthrane_patroller']) {
    $userId = $_COOKIE['penthrane_patroller'];
}
if ($_POST['patroller']) {
    $userId = $_POST['patroller'];
}
$penthrane_user = null;
if ($userId != 'all') {
    $penthrane_user = $userId;
}
setcookie("penthrane_patroller", $userId);

$operation = 'all';
if ($_COOKIE['penthrane_operation']) {
    $operation = $_COOKIE['penthrane_operation'];
}
if ($_POST['operation']) {
    $operation = $_POST['operation'];
}
setcookie("penthrane_operation", $operation);
$penthrane_operation = null;
if ($operation != 'all') {
    $penthrane_operation = $operation;
}

$userData = $User->getById($_GET['id']);
$report = $Penthrane->getReport($fromDate, $toDate, $penthrane_user, $penthrane_operation);


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
        <div class="input-left">
            Operation: 
            <select name="operation">
                <option value="all"
                <?php if ($operation == 'all') {
                    ?>selected="selected"<?php
                }?>
                >
                    All
                </option>
                <option value="1"
                    <?php if ($operation == '1') {
                    ?>selected="selected"<?php
                }?>
                >
                    Add
                </option>
                <option value="2"
                    <?php if ($operation == '2') {
                    ?>selected="selected"<?php
                }?>
                >
                    Manually removed
                </option>
                <option value="3"
                    <?php if ($operation == '3') {
                    ?>selected="selected"<?php
                }?>
                >
                    Used in incidents
                </option>
            </select>
        </div>
        <input type="submit" class="submit" name="show" value="Show">
        <a class="submit print" target="_blank" href="print_penthrane.php">Print</a>
    </form>
</div>
    <div class="table-responsive">
        <div>
            Stock at <?php echo $dateFrom;?> : <?php echo $report['summary'];?> vile(s).
        </div>
        <?php
        if (count($report['stock'])) {
            ?>
            <table class="table table-bordered">
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
            <?php
        }?>
    </div>
<?

page_footer();

?>