<?php
/**
 * Created by PhpStorm.
 * User: krmike
 * Date: 12.05.2015
 * Time: 11:36
 */

include('config.php');

chk_auth();




try
{
    // create an API client instance
    $client = new Pdfcrowd("krmike", "eae0ed92d645ba2c5bcc20ef307cb788");

    // convert a web page and store the generated PDF into a $pdf variable
    $pdf = $client->convertURI('http://bbsp/reporting/incident.php?id=184');

    // set HTTP response headers
    header("Content-Type: application/pdf");
    header("Cache-Control: max-age=0");
    header("Accept-Ranges: none");
    header("Content-Disposition: attachment; filename=\"summary.pdf\"");

    // send the generated PDF
    echo $pdf;
}
catch(PdfcrowdException $why)
{
    echo "Pdfcrowd Error: " . $why;
}
?>


?>