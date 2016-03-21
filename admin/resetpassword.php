<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
$error=''; // Variable To Store Error Message
$ak=$_GET['ak'];
$id=$_GET['userid'];


$tm = time()-86400;
$conn = mysqli_connect($host, $username, $password, $db);
$id=mysqli_real_escape_string($conn, $id);
$ak=mysqli_real_escape_string($conn, $ak);

    $query = mysqli_query($conn, "SELECT id FROM systemUserKey WHERE pkey='$ak' AND id='$id'  AND time > '$tm' AND status='pending'");
    $rows = mysqli_num_rows($query);
    if ($rows <> 1) {
                         echo "Wrong activation";
                         exit;
                    }
//password resetting form will go here

?>
