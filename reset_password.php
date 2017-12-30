<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 03.05.2015
 * Time: 21:15
 */

include('config.php');
$DB = new Db();


if ($_POST['submit-new']) {
    if ($_POST['password'] == $_POST['password-c']) {
        $pwdHash = md5($_POST['password']);
        $SQL = "
        UPDATE
            users
        SET
            password = '".$pwdHash."'
        WHERE
            user_id = '".$_GET['rec']."'
    ";
        $result = $DB->sql_query($SQL);
        header("location: login.php");
    } else {
        $errorMessage = "Different password values!";
    }
}

if ($_GET['rec']) {
    $SQL = "
        SELECT
            *
        FROM
            users
        WHERE
            user_id = '".$_GET['rec']."'
    ";
    $result = $DB->sql_query($SQL);
    $user = mysqli_fetch_assoc($result);

include('login_header.php');
    ?>

<body>
<div class="login">
    <?
    if ($user['password'] == $_GET['ps']) {
        ?>
        <form method="post">
            <img class="logo" src="/images/logo.png" width="240px">
            <div class="welcome">
                Welcome back, <?=$user['name']." ".$user['surname'];?>
            </div>
            <div class="enter-login">
                Please enter new password
            </div>
            <div class="input">
                <input type="password" name="password">
            </div>
            <div class="enter-login">
                Confirm new password
            </div>
            <div class="input">
                <input type="password" name="password-c">
            </div>
            <div class="input">
                <input class="submit" type="submit" name="submit-new" value="Reset">
            </div>
            <?
            if ($errorMessage != "") {
                ?>
                <div class="error">
                    <?=$errorMessage;?>
                </div>
            <?
            }
            ?>
        </form>
    <?
    }
}

?>

    </div>
</body>