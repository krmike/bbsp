<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 10:21
 */

include('config.php');

$resourceId = 4; // incident Form

chk_auth();

$Access = new Access();
$Access->checkPageAccess($_COOKIE['user_type'], $resourceId);


$User = new User();
$Incident = new Incident();
$Injury = new Injury();
$Activity = new Activity();
$Referral = new Referral();

$usersList = $User->getList();
$incidentslist = $Incident->getList();
$injuryTypesList = $Injury->getTypesList();
$injuryLocationList = $Injury->getLocationList();
$activityList = $Activity->getList();
$outcomeList = $Referral->getList();
$week = array(
    "1" => "Monday",
    "2" => "Tuesday",
    "3" => "Wednesday",
    "4" => "Thursday",
    "5" => "Friday",
    "6" => "Saturday",
    "7" => "Sunday"
);
$report = $Incident->getEditable();

page_header(3);
?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Name</th>
                <th>Activity</th>
                <th>Injury Type</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <?
            foreach ($report as $incident) {
                ?>
                <tr
                    <?
                    if ($incident['submit'] == "0") {
                        ?>
                        class="red"
                    <?
                    }
                    ?>
                    >
                    <td>
                        <?
                        $datet = explode("-", $incident['incident_date']);
                        $thisdate = $datet['2']."-".$datet['1']."-".$datet['0'];
                        echo $thisdate;
                        ?>
                    </td>
                    <td>
                        <?=$incident['incident_time'];?>
                    </td>
                    <td>
                        <?=$incident['first_name'];?> <?=$incident['last_name'];?>
                    </td>
                    <td>
                        <?=$activityList[$incident['activity_id']]['name'];?>
                    </td>
                    <?
                    $injures = $Incident->getIncidentInjures($incident['id']);
                    ?>
                    <td>
                        <?
                        foreach ($injures as $iType) {
                            ?>
                            <?=$injuryTypesList[$iType['type_id']]['name'];?><br>
                        <?
                        }
                        ?>
                    </td>
                    <td>
                        <a href="incident.php?id=<?=$incident['id'];?>">
                            Edit
                        </a>
                    </td>
                    <td>
                        <a href="reporting/incident.php?id=<?=$incident['id'];?>">
                            View
                        </a>
                    </td>
                </tr>
            <?
            }
            ?>
        </table>
    </div>
<div>
    <a href="incident.php">
        Add incident
    </a>
</div>
<?

page_footer();

?>