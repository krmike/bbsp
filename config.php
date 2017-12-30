<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 03.05.2015
 * Time: 21:23
 */
require_once("classes/Db.php");
require_once("classes/Access.php");
require_once("classes/Activity.php");
require_once("classes/Ambulance.php");
require_once("classes/DailyLog.php");
require_once("classes/DailyRun.php");
require_once("classes/Dr.php");
require_once("classes/Equipment.php");
require_once("classes/Incident.php");
require_once("classes/Injury.php");
require_once("classes/Lift.php");
require_once("classes/Logger.php");
require_once("classes/Menu.php");
require_once("classes/Radio.php");
require_once("classes/Referral.php");
require_once("classes/Role.php");
require_once("classes/Run.php");
require_once("classes/SignIn.php");
require_once("classes/Snow.php");
require_once("classes/Status.php");
require_once("classes/Trail.php");
require_once("classes/Training.php");
require_once("classes/Transport.php");
require_once("classes/User.php");
require_once("classes/Visibility.php");
require_once("classes/Weather.php");
require 'pdfcrowd.php';
function chk_auth()
{
    $DB = new Db();
    if ($_COOKIE[login] == "" || $_COOKIE[password] == "" || $_COOKIE[user_id] == "")
    {
        setcookie("login", "");
        setcookie("password", "");
        setcookie("user_id", "");
        setcookie("user_type", "");
        header("location:login.php");
    }
}


function page_header($menuItemId = null, $pageName = null) {
    $settingsResourceId = 1; // Settings
    $Access = new Access();

    $SingIn = new SignIn();

    $Menu = new Menu();

    $User = new User();
    $userData = $User->getById($_COOKIE['user_id']);

    $signType = $SingIn->getTodayRecord($_COOKIE['user_id']) ;

    $menu = $Menu->getMenu($_COOKIE['user_type']);

    if (!$pageName) {
        $pageName = $Menu->getPageName($menuItemId);
    }

    ?>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="/style/bootstrap.css" type="text/css">
        <link rel="stylesheet" href="/style/bootstrap-multiselect.css" type="text/css">
        <link rel="stylesheet" type="text/css" href="/style/style.css">
<!--        <link rel="stylesheet" type="text/css" href="/style/DateTimePicker.css">-->
        <link rel="stylesheet" type="text/css" href="/style/jquery.signaturepad.css">
        <link rel="stylesheet" href="/style/bootstrap-datepicker3.css" type="text/css">
        <link rel="stylesheet" href="/style/jquery.timepicker.css" type="text/css">
        <link rel="icon" type="image/ico" href="/images/favicon.ico" />
        <script type="text/javascript" src="/js/moment.js"></script>
        <script src="/js/jquery-2.1.4.min.js"></script>
<!--        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">-->
        <script type="text/javascript" src="/js/bootstrap.js"></script>
        <script type="text/javascript" src="/js/sisyphus.js"></script>
        <script type="text/javascript" src="/js/tableToExcel.js"></script>
        <script type="text/javascript" src="/js/jquery.timepicker.js"></script>
        <script type="text/javascript" src="/js/json2.min.js"></script>
        <script type="text/javascript" src="/js/jquery.signaturepad.min.js"></script>
        <script src="/js/bootstrap-multiselect.js"></script>
        <script src="/js/inputmask.js"></script>
        <script src="/js/jquery.inputmask.js"></script>
        <script src="/js/jquery.inputmask.js"></script>
        <link rel="stylesheet" href="/style/jquery-ui.css">
<!--        <link rel="stylesheet" href="/js/jquery-ui.structure.css">-->
<!--        <link rel="stylesheet" href="/js/jquery-ui.theme.css">-->
        <script src="/js/jquery-ui.js"></script>
<!--        <script src="/js/DateTimePicker.js"></script>-->
<!--        <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
        <script src="/js/script.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.20"></script>
        <script src="/js/bootstrap-datepicker.js"></script>
        <title><?=$pageName;?></title>
    </head>
    <body>

        <div class="header">
            <div class="container">
                <div class="logo"> <a href="/"><img src="/images/logo.png"></a> </div>
                <div class="welcome"> Welcome to MTBBSP <?=$userData['name'];?>&nbsp;<?=$userData['surname'];?></div>
                <div class="exit">
                    <div class="logout"><a href="/login.php?logout=1"><img src="/images/logout_arrow.png" height="16px">  <span class="patroller-link">Log out</span></a><br>
                        <a class="patroller-link" href="/reporting/patroller.php?id=<?=$_COOKIE['user_id']?>">
                            Own Report
                        </a><br>
                        <a class="patroller-link" href="/settings/edit_user.php?id=<?=$_COOKIE['user_id']?>">
                            Edit Profile
                        </a>
                    </div>
                    <?
                    if ($Access->canAccess($_COOKIE['user_type'], $settingsResourceId)) {
                        ?>
                        <div class="settings"> <a href="/settings/"><img src="/images/setting.png"></a></div>
                    <?
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="menu">
            <div class="container">
                <ul>
                    <?
                    if ($signType != 1) {
                        ?>
                        <li
                            <?if($menuItemId == 1){?> class="selected" <?}?>
                            >
                            <a href="/sign_in.php">
                                Sign In
                            </a>
                        </li>
                    <?
                    }
                    foreach($menu as $menuItem) {
                        ?><li
                        <?if($menuItemId == $menuItem['item_id']){?> class="selected" <?}?>
                        >
                        <a href="<?=$menuItem['link'];?>">
                            <?=$menuItem['item_name'];?>
                        </a>
                        </li><?
                    }
                    if ($signType == 1) {
                        ?>
                        <li
                            <?if($menuItemId == 10){?> class="selected" <?}?>
                            >
                            <a href="/sign_out.php">
                                Sign Out
                            </a>
                        </li>
                    <?
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="main">
            <div class="container">
    <?
    if ($pageName) {
        ?>
        <h4><?=$pageName;?></h4>
    <?
    }
    //here starts content
}

function page_footer(){
    //end of content
    ?>

    </div>
    </div>

    </body>
<?
}

function getUrl() {
//    echo "<pre>";
//    print_r($_SERVER);
//    echo "</pre>";
    $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
    $url .= ( $_SERVER["SERVER_PORT"] != 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
//    $url .= $_SERVER["REQUEST_URI"];
    return $url;
}

?>