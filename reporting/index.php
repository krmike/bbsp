<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include("../config.php");

$resourceId = 6; // Reporting

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);
page_header(5);

if ($Access->canAccess($_COOKIE['user_type'], 9)) {
    ?>
    <a href="sign_in.php">Sign In / Sign Out</a><br>
<?
}
if ($Access->canAccess($_COOKIE['user_type'], 10)) {
    ?>
    <a href="daily_run.php">Run Conditions</a><br>
<?
}
if ($Access->canAccess($_COOKIE['user_type'], 11)) {
    ?>
    <a href="daily_log.php">Communication Log</a><br>
<?
}
if ($Access->canAccess($_COOKIE['user_type'], 12)) {
    ?>
    <a href="training_log.php">Training Log</a><br>
<?
}
if ($Access->canAccess($_COOKIE['user_type'], 16)) {
    ?>
    <a href="incidents.php">Incidents</a><br>
    <a href="incidents_map.php">Incidents Heat Map</a><br>
<?
}
if ($Access->canAccess($_COOKIE['user_type'], 19)) {
    ?>
    <a href="incident_summary.php">Incident Summary</a><br>
<?
}
if ($Access->canAccess($_COOKIE['user_type'], 14)) {
    ?>
    <a href="patrollers.php">Patrollers Profile</a><br>
<?
}
if ($Access->canAccess($_COOKIE['user_type'], 15)) {
    ?>
    <a href="equipment.php">Equipment Management</a><br>
<?
}
if ($Access->canAccess($_COOKIE['user_type'], 20)) {
    ?>
    <a href="consumable.php">Consumable Report</a><br>
<?
}
if ($Access->canAccess($_COOKIE['user_type'], 17)) {
    ?>
    <a href="entonox.php">Entonox</a><br>
<?
}
if ($Access->canAccess($_COOKIE['user_type'], 18)) {
    ?>
    <a href="penthrane.php">Penthrane</a><br>
<?
}
?>


<?

page_footer();

?>