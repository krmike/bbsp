<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 13.05.2015
 * Time: 22:31
 */

include('config.php');

$resourceId = 21; // Penthrane

$Access = new Access();
$DB = new Db();
$User = new User();
$Penthrane = new Penthrane();

chk_auth();

$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);

page_header(8, "Penthrane");

?>
<div>
	<a href="/penthrane_add.php">Add / Remove</a>
</div>
<?

page_footer();

?>