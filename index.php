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

$User = new User();
$userData = $User->getById($_COOKIE['user_id']);

page_header(Null, "Welcome");

?>
Welcome <?=$userData['name'];?>&nbsp;<?=$userData['surname'];?>!<br> <?=date("d-m-Y H:i");?>
<?

page_footer();

?>