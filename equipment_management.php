<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 22:31
 */

include('config.php');

$resourceId = 7; // Equipment Management

$Access = new Access();
$DB = new Db();
$Equip = new Equipment();

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);

$equipList = $Equip->getList();

if ($_POST['save']) {
    if ($_POST['way'] == 1) {
        $way = 1;
    }
    if ($_POST['way'] == 2) {
        $way = 2;
    }
    if ($_POST['return']) {
        $return = 1;
    } else {
        $return = 0;
    }

    $date = explode("-", $_POST['date']);
    $thisdate = $date['2']."-".$date['1']."-".$date['0'];

    $managementData = array(
        "date"          => $thisdate,
        "equipment_id"  => $_POST['equipment'],
        "destination"   => $_POST['destination'],
        "return"        => $return
    );

    $managementId = $Equip->addManagement($managementData);
    header("location:/index.php");
}

page_header(6);

?>
<form method="post">
    <table class="table">
        <tr>
            <td class="right-cell">
                Date:
            </td>
            <td>
                <input type="text" class="date" name="date" data-field="date" value="<?=date("d-m-Y");?>">
                <div id="dtBox"></div>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Equipment:
            </td>
            <td>
                <select name="equipment">
                    <?
                    foreach ($equipList as $equip) {
                        ?>
                        <option value="<?=$equip['id'];?>"><?=$equip['name'];?></option>
                    <?
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Road / Air:
            </td>
            <td>
                <select name="way">
                    <option value="1">Road</option>
                    <option value="2">Air</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Destination:
            </td>
            <td>
                <input type="text" name="destination">
            </td>
        </tr>
        <tr>
            <td class="right-cell">
                Return:
            </td>
            <td>
                <input type="checkbox" name="return">
            </td>
        </tr>
        <tr>
            <td class="right-cell">

            </td>
            <td>
                <input type="submit" class="submit" name="save" value="Save">
            </td>
        </tr>
    </table>
</form>
<?

page_footer();

?>