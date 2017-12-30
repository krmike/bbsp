<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 23:22
 */

include("../config.php");

chk_auth();

$Access = new Access();

if ($_GET['id'] != $_COOKIE['user_id']) {
    $Access->checkPageAccess($_COOKIE['user_type'], 1);
}

$User = new User();
$userTypesList = $User->getTypesList();
$header = "Add User";
if ($_GET['id']) {
    $header = "Edit User";
    $user = $User->getById($_GET['id']);
}

if ($_POST['add']) {
    if ($_POST['password'] == $_POST['sec-password'] && $_POST['login'] != "" && $_POST['name'] != "" && $_POST['email'] != "") {
        if ($_POST['active']) {
            $active = 1;
        } else {
            $active = 0;
        }
        $newUser = array(
            'login' => $_POST['login'],
            'password' => md5($_POST['password']),
            'user_type' => $_POST['user_type'],
            'name' => $_POST['name'],
            'surname' => $_POST['surname'],
            'email' => $_POST['email'],
            'active' => $active
        );
        $userId = $User->addUser($newUser);
        header("location: /settings/users.php");
    }
}

if ($_POST['edit']) {

    if ($_POST['name'] != "" && $_POST['email'] != "") {

        if ($_POST['active']) {
            $active = 1;
        } else {
            $active = 0;
        }
        $newUser = array(
            'user_type' => $_POST['user_type'],
            'name' => $_POST['name'],
            'surname' => $_POST['surname'],
            'email' => $_POST['email'],
            'aspa_id' => $_POST['aspa_id'],
            'wwc_vit' => $_POST['wwc_vit'],
            'active' => $active
        );
        if ($_POST['old_password'] != "" && md5($_POST['old_password']) == $user['password'] && $_POST['password'] == $_POST['sec-password']) {
            $newUser['password'] = md5($_POST['password']);
        }
        if ($_COOKIE['user_type'] == 1 && $_POST['password'] == $_POST['sec-password']) {
            $newUser['password'] = md5($_POST['password']);
        }
        $User->updateById($_GET['id'], $newUser);
        header("location: /settings/users.php");
    }
}

page_header(null, $header);

?>
    <form method="post">
        <table class="table">
            <tr>
                <td>Login</td>
                <td>
                    <?
                    if (!$_GET['id']) {
                        ?>
                        <input id="login" type="text" name="login"><div id="login-label"></div>
                    <?
                    } else {
                        echo $user['login'];
                    }
                    ?>
                </td>
            </tr>
            <?
            if ($_GET['id'] && $_GET['id'] != 1 && $_COOKIE['user_type'] != 1) {
                ?>
                <tr>
                    <td>Old password</td>
                    <td><input type="password" name="old_password"></td>
                </tr>
            <?
            }
            ?>
            <?
            if ($_GET['id'] != 1) {
                ?>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password"></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type="password" name="sec-password"></td>
                </tr>
            <?
            }
            ?>
            <tr>
                <td>Name</td>
                <td><input type="text" name="name" value="<?=$user['name'];?>"></td>
            </tr>
            <tr>
                <td>Surname</td>
                <td><input type="text" name="surname" value="<?=$user['surname'];?>"></td>
            </tr>
            <tr>
                <td>User type</td>
                <td>
                    <select name="user_type">
                        <?
                        foreach ($userTypesList as $userType) {
                            ?>
                            <option value="<?=$userType['user_type_id'];?>"
                                    <?
                                    if ($user['user_type'] == $userType['user_type_id']) {
                                        ?>
                                        selected="selected"
                                    <?
                                    }
                                    ?>
                                >
                                <?=$userType['user_type_name'];?>
                            </option>
                        <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>E-mail</td>
                <td><input type="text" name="email" value="<?=$user['email'];?>"></td>
            </tr>
            <tr>
                <td>ASPA Id</td>
                <td><input type="text" name="aspa_id" value="<?=$user['aspa_id'];?>"></td>
            </tr>
            <tr>
                <td>WWC / VIT</td>
                <td><input type="text" name="wwc_vit" value="<?=$user['wwc_vit'];?>"></td>
            </tr>
            <tr>
                <td>Active</td>
                <td><input type="checkbox" name="active"
                        <? if ($user['active'] == 1) { ?>
                        checked="checked"
                        <?}?>
                        >
                </td>
            </tr>

        </table>
        <?
        if ($_GET['id']) {
            ?>
            <input class="submit" type="submit" name="edit" value="Save changes">
        <?
        } else {
            ?>
            <input id="add-user" class="submit" type="submit" name="add" value="Add user">
        <?
        }
        ?>
    </form>
<script>
    settings.addUser();
</script>
<?

page_footer();

?>