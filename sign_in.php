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
$Equip = new Equipment();
$Radio = new Radio();
$SingIn = new SignIn();

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);

$equipList = $Equip->getPEList();
$radioList = $Radio->getList();

if ($_POST['save']) {
    $date = explode("-", $_POST['date']);
    $thisdate = $date['2']."-".$date['1']."-".$date['0'];
    $signTime = $_POST['time_hours'].":".$_POST['time_minutes'];
    $singInData = array(
        "sign_type"     => 1, //sign in
        "patroller_id"  => $_COOKIE['user_id'],
        "date"          => $thisdate,
        "time"          => $signTime,
        "equipment_id"  => $_POST['equipment'],
        "radio_id"      => $_POST['radio'],
        "penthrane"     => $_POST['penthrane']
    );
    $singInId = $SingIn->add($singInData);
    header("location:/index.php");
}

page_header(1, "Sign In");

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
                Equipment:
            </td>
            <td>
                <select required="required" name="equipment">
                    <option></option>
                    <?
                    foreach ($equipList as $equip){
                        ?>
                        <option value="<?=$equip['id'];?>"><?=$equip['name']?></option>
                    <?
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Radio:
            </td>
            <td>
                <select required="required" name="radio">
                    <option></option>
                    <?
                    foreach ($radioList as $radio){
                        ?>
                        <option value="<?=$radio['id'];?>"><?=$radio['name']?></option>
                    <?
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Penthrane:
            </td>
            <td>
                <select required="required" name="penthrane">
                    <option></option>
                    <option value="0">0ml</option>
                    <option value="3">3ml</option>
                    <option value="6">6ml</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">

            </td>
            <td>
                <input type="submit" class="submit" name="save" value="Sign In">
            </td>
        </tr>
    </table>
</form>
<?

page_footer();

?>