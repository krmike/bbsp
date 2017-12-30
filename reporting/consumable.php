<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

$resourceId = 20; // Consumable Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$Equipment = new Equipment();

$equipmentList = $Equipment->getList();


$dateFrom = "01-".date("m-Y");
if ($_COOKIE['consumable_date_from']) {
    $dateFrom = $_COOKIE['consumable_date_from'];
}
if ($_POST['date_from_d']) {
    $dateFrom = $_POST['date_from_d']."-".$_POST['date_from_m']."-".$_POST['date_from_y'];
}
setcookie("consumable_date_from", $dateFrom);

$dateTo = date("d-m-Y");
if ($_COOKIE['consumable_date_to']) {
    $dateTo = $_COOKIE['consumable_date_to'];
}
if ($_POST['date_to_d']) {
    $dateTo = $_POST['date_to_d']."-".$_POST['date_to_m']."-".$_POST['date_to_y'];
}
setcookie("consumable_date_to", $dateTo);

$equipmentType = "all";
if ($_COOKIE['consumable_equipment']) {
    $equipmentType = $_COOKIE['consumable_equipment'];
}
if ($_POST['equipment']) {
    $equipmentType = $_POST['equipment'];
}
setcookie("consumable_equipment", $equipmentType);

if ($equipmentType == '2') {
    $equipmentType = '0';
}

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$report = $Equipment->getConsumableReport($fromDate, $toDate, $equipmentType);

page_header(5, "Consumable Report");

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
            Equipment:&nbsp;<select name="equipment">
                <option value="all"
                    <?
                    if ($equipmentType == "all") {
                        ?>
                        selected="selected"
                    <?
                    }
                    ?>
                    >All</option>
                <option value="1"
                    <?
                    if ($equipmentType == "1") {
                        ?>
                        selected="selected"
                    <?
                    }
                    ?>
                    >Consumable</option>
                <option value="2"
                    <?
                    if ($equipmentType == "0") {
                        ?>
                        selected="selected"
                    <?
                    }
                    ?>
                    >Non consumable</option>
            </select>
        </div>
        <input type="submit" class="submit" value="Show">
        <a class="submit print" target="_blank" href="print_consumable.php">Print</a>
    </form>
</div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Equipment</th>
                <th>Amount</th>
            </tr>
            <?
                foreach ($equipmentList as $equipment) {
                    if (count($report[$equipment['id']]) > 0) {
                        ?>
                        <tr>
                            <td><?=$equipment['name'];?></td>
                            <td><?=$report[$equipment['id']];?></td>
                        </tr>
                    <?
                    }
                }
            ?>
        </table>
    </div>
<?

page_footer();

?>