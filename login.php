<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
include_once 'configuration.php';

// If user comes in via a URL obtained from somewhere, e.g. the (not yet implemented QR code generator), set their session as 'logged in'

if (isset($_GET["eventkey"])) {
    $key = $_GET["eventkey"];
    $_POST['submit'] = true;
    $_POST['eventkey'] = $key;
}

if (isset($_POST['submit'])) {
    if (empty($_POST['eventkey'])) {
        $error = "Key Code is not valid";
        }
    else
    {
        $conn = mysqli_connect($host, $username, $password, $db);
        $key=$_POST['eventkey'];
        $date = date("d-m-Y");
        // To protect MySQL injection for Security purpose
        $key = stripslashes($key);
        $date = stripslashes($date);
        $key = mysqli_real_escape_string($conn, $key);
        $date = mysqli_real_escape_string($conn, $date);

        $result = mysqli_query($conn, "SELECT date, willexpire FROM requestkeys WHERE thekey='$key'");
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $row = mysqli_fetch_row($result);
            $data = $row[0];
            $date = strtotime($data);
            $willexpire = $row[1];
            $cur_date = date("U");
            if (($cur_date - (3600 * (24 + $over_hours)) < $date) || ($willexpire == "0")) {
                $_SESSION['eventkey'] = $key; 
                $_SESSION['timeout'] = time();
                mysqli_close($conn); // Closing Connection
                header("location: requests.php"); 
                } else {
                       $error = "Willexpire: ". $willexpire. " " . $data . " Key Code '" . $key . "' is not valid " .$date . ", sorry. Try again with a valid code.";
                       mysqli_close($conn); // Closing Connection
                       header("location: index.php");
                   }
        }
        else {
            echo $key . " is not valid. Ooops";
        }
//        mysqli_close($conn); // Closing Connection
    }
}
?>
