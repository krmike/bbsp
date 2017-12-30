<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 23:12
 */

include("../config.php");

chk_auth();

$Access = new Access();

$Access->checkPageAccess($_COOKIE['user_type'], 1);

$Weather = new Weather();
$weatherList = $Weather->getlist();

if ($_GET['action'] == "delete") {
    $weatherId = $_GET['id'];
    $Weather->deleteById((integer)$weatherId);
    header("location:weather.php");
}

page_header(null, "Weather Settings");

?>
    <a href="edit_weather.php?action=add">Add weather</a>
<table class="table">
    <?
    foreach ($weatherList as $item) {
        ?>
        <tr>
            <td><?=$item['name']?></td>
            <td><a href="edit_weather.php?id=<?=$item['id'];?>">Edit</a></td>
            <td><a class="delete_weather" href="weather.php?action=delete&id=<?=$item['id'];?>">Delete</a></td>
        </tr>
    <?
    }
    ?>
</table>
<?

page_footer();

?>