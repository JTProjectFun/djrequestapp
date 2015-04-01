<?php
include "../configuration.php";
include "phpqrcode/qrlib.php";
$row = "";
$key = $_GET['key'];
$codeContents="http://" . $baseURL . "login.php?key=" . $key;

$tempfile = "images/qrcode.png";

$conn = mysqli_connect($host,$username,$password,$db);
$query = mysqli_query($conn, "SELECT thekey FROM requestkeys WHERE thekey='$key'");
$rows = mysqli_num_rows($query);
        if ($rows == 1) {
                             QRcode::png($codeContents, $tempfile, QR_ECLEVEL_L, 8); 
                             // display QR code
                             echo '<img src="'.$tempfile.'" />'; 

                         }
        else echo "<span>INVALID KEY SUPPLIED</span>";
mysqli_close($conn);
?>
