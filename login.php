<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 03.05.2015
 * Time: 21:15
 */

include('config.php');
$DB = new Db();

if ($_GET['logout']) {
    setcookie("login", "");
    setcookie("password", "");
    setcookie("user_id", "");
    setcookie("user_type", "");
    header("location:index.php");
}

if((isset($_POST['submit']) || ($_POST['login'] && $_POST['password']))) {
    $errorMessage = '* Wrong login or password';
    $SQL = "SELECT * FROM users WHERE login = '" . $_POST['login'] . "' AND password = '" . md5($_POST['password']) . "'";
    $result = $DB->sql_query($SQL);
    $user_data = mysqli_fetch_assoc($result);

    if ($_POST['login'] == $user_data['login'] && md5($_POST['password']) == $user_data['password']) {
        $errorMessage = "";
        setcookie("login", $_POST['login']);
        setcookie("password", $_POST['password']);
        setcookie("user_id", $user_data['user_id']);
        setcookie("user_type", $user_data['user_type']);
        header("Location: index.php");
    } else {
        setcookie("login", "");
        setcookie("password", "");
        setcookie("user_id", "");
        setcookie("user_type", "");
    }
}
include('login_header.php');

?>

<body>
    <div class="login">
        <form method="post">
            <img class="logo" src="images/logo.png" width="240px">
            <div class="welcome">
                Welcome to MTBBSP
            </div>
            <div class="enter-login">
                Enter login and password
            </div>
            <div class="input">
                <input type="text" name="login" placeholder="Username">
            </div>
            <div class="input">
                <input type="password" name="password" placeholder="Password">
            </div>
            <div class="input">
                <input class="submit" type="submit" name="submit" value="Log in">
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
            <div>
                <a href="forget_password.php">Forget password</a>
            </div>
        </form>
    </div>
</body>