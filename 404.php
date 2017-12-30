<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 11:36
 */

include('../config.php');

chk_auth();

page_header(null, "Page Not Found");

echo "PAGE NOT FOUND";

page_footer();

?>