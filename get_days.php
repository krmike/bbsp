<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 07.05.2015
 * Time: 23:32
 */

include('config.php');
$DB = new Db();
chk_auth();

$day = $_POST['day'];
$month = $_POST['month'];
$year = $_POST['year'];

for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++) {
    ?>
    <option value="<?=sprintf("%02d", $i);?>"
        <?
        if ($day <= $i && $day == sprintf("%02d", $i)) {
            ?>
            selected="selected"
        <?
        }
        if ($day > $i == cal_days_in_month(CAL_GREGORIAN, $month, $year)) {
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