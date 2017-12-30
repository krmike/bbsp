<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], 1);
page_header(null, "Settings");

?>
    <a href="activity.php">Activity</a><br>
    <a href="ambulance.php">Ambulance destination</a><br>
    <a href="dr.php">MtBB Dr</a><br>
    <a href="equipment.php">Equipment</a><br>
    <a href="injury_location.php">Injury locations</a><br>
    <a href="injury_categories.php">Injury categories</a><br>
    <a href="injury_types.php">Injury types</a><br>
    <a href="lifts.php">Lifts</a><br>
    <a href="patroller_equipment.php">Patroller Equipment</a><br>
    <a href="access.php">Permissions</a><br>
    <a href="radio.php">Radio</a><br>
    <a href="referral.php">Referral outcome</a><br>
    <a href="roles.php">Roles</a><br>
    <a href="runs.php">Runs</a><br>
    <a href="snow.php">Snow</a><br>
    <a href="statuses.php">Statuses</a><br>
    <a href="training_categories.php">Training Categories</a><br>
    <a href="training_types.php">Training Types</a><br>
    <a href="transport.php">Transport</a><br>
    <a href="users.php">Users</a><br>
    <a href="visibility.php">Visibility</a><br>
    <a href="weather.php">Weather</a><br>

<?

page_footer();

?>