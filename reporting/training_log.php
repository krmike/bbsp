<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

$resourceId = 12; // Training Log Report

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$Training = new Training();

$usersList = $User->getList();
$categoriesList = $Training->getCategoriesList();
$typesList = $Training->getTypesList();



$dateFrom = "01-".date("m-Y");
if ($_COOKIE['training_log_date_from']) {
    $dateFrom = $_COOKIE['training_log_date_from'];
}
if ($_POST['date_from_d']) {
    $dateFrom = $_POST['date_from_d']."-".$_POST['date_from_m']."-".$_POST['date_from_y'];
}
setcookie("training_log_date_from", $dateFrom);

$dateTo = date("d-m-Y");
if ($_COOKIE['training_log_date_to']) {
    $dateTo = $_COOKIE['training_log_date_to'];
}
if ($_POST['date_to_d']) {
    $dateTo = $_POST['date_to_d']."-".$_POST['date_to_m']."-".$_POST['date_to_y'];
}
setcookie("training_log_date_to", $dateTo);

if ($_POST['patroller'] != "all") {
    $userId = $_POST['patroller'];
} else {
    $userId = null;
}
setcookie("training_log_patroller", $userId);

if ($_POST['category'] != "all") {
    $categoryId = $_POST['category'];
} else {
    $categoryId = null;
}
setcookie("training_log_category", $categoryId);

if ($_POST['type'] != "all") {
    $typeId = $_POST['type'];
} else {
    $typeId = null;
}
setcookie("training_log_type", $typeId);

$dateF = explode("-", $dateFrom);
$fromDate = $dateF['2']."-".$dateF['1']."-".$dateF['0'];

$dateT = explode("-", $dateTo);
$toDate = $dateT['2']."-".$dateT['1']."-".$dateT['0'];

$report = $Training->getReport($fromDate, $toDate, $userId, $categoryId, $typeId);

page_header(5, "Training Log Report");
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
            Category:&nbsp;<select name="category">
                <option value="all">All</option>
                <?
                foreach ($categoriesList as $category) {
                    ?>
                    <option value="<?=$category['id']?>" <? if($category['id'] == $categoryId) {
                        ?> selected="selected"<?
                    }?>>
                        <?=$category['name']?>
                    </option>
                <?
                }
                ?>
            </select>
        </div>
        <div class="input-left">
            Type:&nbsp;<select name="type">
                <option value="all">All</option>
                <?
                foreach ($typesList as $type) {
                    ?>
                    <option value="<?=$type['id']?>" <? if($type['id'] == $typeId) {
                        ?> selected="selected"<?
                    }?>>
                        <?=$type['name']?>
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
        <a class="submit print" target="_blank" href="print_tl.php">Print</a>
    </form>
</div>

            <?
            foreach ($report as $training_category_id => $trainings) {
                ?>
                <div>
                    <div class="training-title">
                        <b>
                            <?=$categoriesList[$training_category_id]['name'];?>
                        </b>
                    </div>
                    <div class="table-responsive training-category">
                        <table class="table table-bordered">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Type</th>
                                <th>Patrollers</th>
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
                                        <?
                                        $patrollers = $Training->getLogPatrollers($train['id']);
                                        foreach ($patrollers as $patroller) {
                                            ?>
                                            <?=$usersList[$patroller['patroller_id']]['name']?>&nbsp;<?=$usersList[$patroller['patroller_id']]['surname'];?><br>
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

                </div>
                <?php
            }

page_footer();

?>