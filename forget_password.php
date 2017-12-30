<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 03.05.2015
 * Time: 21:15
 */

include('config.php');
$DB = new Db();

include('login_header.php');

if ($_POST['submit']) {
    $SQL = "
        SELECT
            *
        FROM
            users
        WHERE
            login = '".$_POST['login']."'
    ";
    $result = $DB->sql_query($SQL);
    $user = mysqli_fetch_assoc($result);
    if ($user['user_id'] > 0) {
        $href = getUrl()."/reset_password.php?rec=".$user['user_id']."&ps=".$user['password']."";
//        echo $href;

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        $headers .= 'From: MtBBSkiPatrol' . "\r\n";
        $mailMessage = "
            Dear ".$user['name']." ".$user['surname']."!<br>
            To reset your password on MT Baw Baw Ski Patrol click <a href=\"".$href."\">here</a>.
        ";
        mail($user['email'], "MtBB Password Reset", $mailMessage, $headers);
        $resetMail = "Check your E-Mail!";
    } else {
        $errorMessage = "Wrong login!";
    }
}

?>

<body>
    <div class="login">
        <form method="post">
            <img class="logo" src="/images/logo.png" width="240px">
            <div class="welcome">
                Welcome to MTBBSP
            </div>
            <div class="enter-login">
                To reset password please enter your login
            </div>
            <div class="input">
                <input type="text" name="login" placeholder="Username">
            </div>
            <div class="input">
                <input class="submit" type="submit" name="submit" value="Submit">
            </div>
            <?
            if ($errorMessage != "") {
                ?>
                <div class="error">
                    <?=$errorMessage;?>
                </div>
            <?
            }
            if ($resetMail != "") {
                ?>
                <div class="error">
                    <?=$resetMail;?>
                </div>
            <?
            }
            ?>
        </form>
    </div>
</body>