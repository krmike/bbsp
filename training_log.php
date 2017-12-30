<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 22:31
 */

include('config.php');

$resourceId = 8; // training Log

$Access = new Access();
$DB = new Db();
$Training = new Training();
$User = new User();

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);

$usersList = $User->getList();
$categoriesList = $Training->getCategoriesList();
$typesList = $Training->getTypesList();

if ($_POST['save']) {
    $date = $_POST['date_y']."-".$_POST['date_m']."-".$_POST['date_d'];
    $trainingData = array(
        "date"              => $date,
        "user_id"           => $_COOKIE['user_id'],
        "category_id"       => $_POST['category'],
        "type_id"           => $_POST['type']
    );

    $logId = $Training->addLog($trainingData);
    if ($logId > 0) {
        for ($i = 0; $i < count($_POST['patrollers']); $i++) {
            $Training->addPatroller(array(
                "log_id"         => $logId,
                "patroller_id"   => $_POST['patrollers'][$i],
                "comment"        => $_POST['comment'][$_POST['patrollers'][$i]]
            ));
        }
    }
    header("location:/index.php");
}

page_header(7);

?>
<form method="post"  enctype="multipart/form-data">
    <table class="table">
        <tr>
            <td class="right-cell">
                Patroller
            </td>
            <td>
                Comment
            </td>
        </tr>
        <?
        foreach ($usersList as $user) {
            if ($user['active'] == 1) {
                ?>
                <tr>
                    <td class="right-cell">
                        <?=$user['name'];?>&nbsp;<?=$user['surname'];?>
                        <input class="patroller" type="checkbox" name="patrollers[]" value="<?=$user['user_id'];?>">
                    </td>
                    <td>
                        <textarea class="comment" id="comment<?=$user['user_id'];?>" name="comment[<?=$user['user_id'];?>]"></textarea>
                    </td>
                </tr>
            <?
            }
        }
        ?>
        <tr>
            <td class="right-cell">
                Date:
            </td>
            <td>
                <select id="date_d" class="hours" name="date_d">
                    <?
                    for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                        ?>
                        <option value="<?=sprintf("%02d", $i);?>"
                            <?
                            if (date("d") == sprintf("%02d", $i)) {
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
                </select>-<select id="date_m" class="hours" name="date_m">
                    <?
                    for ($i = 1; $i <= 12; $i++) {
                        ?>
                        <option value="<?=sprintf("%02d", $i);?>"
                            <?
                            if (date("m") == sprintf("%02d", $i)) {
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
                </select>-<select id="date_y" class="hours" name="date_y">
                    <?
                    for ($i = date("Y") - 90; $i <= date("Y") + 10; $i++) {
                        ?>
                        <option value="<?=sprintf("%02d", $i);?>"
                            <?
                            if (date("Y") == sprintf("%02d", $i)) {
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
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Category:
            </td>
            <td>
                <select required="required" name="category" id="category">
                    <option></option>
                    <?
                    foreach ($categoriesList as $category) {
                        ?>
                        <option value="<?=$category['id'];?>">
                            <?=$category['name'];?>
                        </option>
                    <?
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Type:
            </td>
            <td>
                <select required="required" name="type" id="type">
                    <option></option>
                    <?
                    foreach ($typesList as $type) {
                        ?>
                        <option class="type type<?=$type['category_id'];?>" value="<?=$type['id'];?>">
                            <?=$type['name'];?>
                        </option>
                    <?
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">

            </td>
            <td>
                <input type="submit" class="submit" name="save" value="Log">
            </td>
        </tr>
    </table>
</form>

    <script>
        dailyLog.init();
    </script>
<?

page_footer();

?>