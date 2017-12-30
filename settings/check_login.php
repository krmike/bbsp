<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 23:22
 */

include("../config.php");

chk_auth();

$Db = new Db();

$login = $_POST['login'];

$SQL = "SELECT * FROM users WHERE login = '".$login."'";
$result = $Db->sql_query($SQL);
for ($users = array(); $row = mysqli_fetch_assoc($result); $users[] = $row);
if (count($users) > 0) {
    echo "count";
} else
    echo "no";
?>