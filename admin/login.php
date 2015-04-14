<?php
include '../configuration.php';
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}
else
{

    // Define $username and $password
    $user=$_POST['username'];
    $pass=$_POST['password'];

    // Establishing Connection with Server by passing server_name, user_id and password as a parameter
    $conn = mysqli_connect($host, $username, $password, $db);

    // To protect MySQL injection for Security purpose
    $user = stripslashes($user);
    $pass = stripslashes($pass);
    $user = mysqli_real_escape_string($conn, $user);
    $pass = mysqli_real_escape_string($conn, $pass);

    // SQL query to fetch information of registered users and finds user match.
    $query = mysqli_query($conn, "select * from systemUser where password='$pass' AND username='$user'");
    $rows = mysqli_num_rows($query);

    if ($rows == 1) {
        $_SESSION['login_user']=$user; // Initializing Session

            $query = mysqli_query($conn, "SELECT userlevel, name, id FROM systemUser WHERE username='$user'");
            $result = mysqli_fetch_row($query);
            $userlevel = $result[0];
            $realname = $result[1];
            $userid = $result[2];
            setcookie("adminuser", $user);
            setcookie("adminuserid", $userid);
            setcookie("adminlevel", $userlevel);
            setcookie("adminrealname", $realname);

        header("location: admin.php"); // Redirecting To Other Page
        } else {
                $error = $rows . " : " . $user . " : " . $pass . " : Whoops. Username or Password is invalid";
               }
        mysqli_close($connection); // Closing Connection
    }
}
?>
