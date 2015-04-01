<?php
include "../configuration.php";
include "phpqrcode/qrlib.php";

$key = $_GET['key'];
$codeContents="http://" . $baseURL . "login.php?key=" . $key;

$tempfile = "images/qrcode.png";

    QRcode::png($codeContents, $tempfile, QR_ECLEVEL_L, 8); 
         
    // displaying 
    echo '<img src="'.$tempfile.'" />'; 

?>
