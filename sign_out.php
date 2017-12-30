<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 22:31
 */

include('config.php');

$resourceId = 2; // Sing In

$Access = new Access();
$DB = new Db();
$User = new User();
$SingIn = new SignIn();

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);

if ($_POST['save']) {
    $date = explode("-", $_POST['date']);
    $thisdate = $date['2']."-".$date['1']."-".$date['0'];
    $signTime = $_POST['time_hours'].":".$_POST['time_minutes'];
    $singOutData = array(
        "sign_type"             => 2,
        "patroller_id"          => $_COOKIE['user_id'],
        "date"                  => $thisdate,
        "time"                  => $signTime,
        "penthrane_used"        => $_POST['penthrane_used'],
        "penthrane_returned"    => $_POST['penthrane_returned']
    );
    $singOutId = $SingIn->add($singOutData);
    header("location:/index.php");
}

page_header(10, "Sign Out");

?>
<form method="post">
    <table class="table">
        <tr>
            <td class="right-cell">
                Patroller:
            </td>
            <td>
                <?=$User->getName($_COOKIE['user_id']);?>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Date:
            </td>
            <td>
                <input type="hidden" name="date" data-field="date" value="<?=date("d-m-Y");?>"> <?=date("d-m-Y");?>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Time:
            </td>
            <td>
                <select class="hours" name="time_hours">
                    <?
                    for ($i = 0; $i < 24; $i++) {
                        ?>
                        <option value="<?=sprintf("%02d", $i);?>"
                            <?
                            if (date("H") == sprintf("%02d", $i)) {
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
                </select>:<select class="hours" name="time_minutes">
                    <?
                    for ($i = 0; $i < 60; $i++) {
                        ?>
                        <option value="<?=sprintf("%02d", $i);?>"
                            <?
                            if (date("i") == sprintf("%02d", $i)) {
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
                Penthrane used:
            </td>
            <td>
                <select required="required" name="penthrane_used">
                    <option></option>
                    <option value="0">0ml</option>
                    <option value="3">3ml</option>
                    <option value="6">6ml</option>
                    <option value="9">9ml</option>
                    <option value="12">12ml</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Penthrane returned:
            </td>
            <td>
                <select required="required" name="penthrane_returned">
                    <option></option>
                    <option value="0">0ml</option>
                    <option value="3">3ml</option>
                    <option value="6">6ml</option>
                    <option value="9">9ml</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">

            </td>
            <td>
                <input type="submit" class="submit" name="save" value="Sign Out">
            </td>
        </tr>
    </table>
</form>
<?

page_footer();

?>