<?php
include_once '../configuration.php';
include_once 'adminconfig.php';
include_once '../functions/functions.php';
$email=$_POST['email'];

$conn = mysqli_connect($host, $username, $password, $db);

// Cleanup & escape email address POSTed
$email = stripslashes($email);
$email = mysqli_real_escape_string($conn, $email);

// Find admin user from email address in systemUser table
$query = mysqli_query($conn, "SELECT email, id from systemUser WHERE email='$email'");
$result = mysqli_fetch_row($query);
$rows = mysqli_num_rows($query);

if ($rows == 1) {
$id = $result[1];  // fetch userid
$tm=time() - 86400; // Time in last 24 hours
// Check if reset is still pending on user's account
$query = mysqli_query($conn, "SELECT id FROM systemUserKey WHERE id='$id' AND time > $tm and status='pending'");
$rows = mysqli_num_rows($query);
if ($rows == 1){
                 echo "Your password reset link is already posted to your email address (".$email."), please check your email inbox & Junk mail folder. ";
                 exit;
               }


function random_generator($digits){
    srand ((double) microtime() * 10000000);
    $input = array ("A", "B", "C", "D", "E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    $random_generator=""; // Initialize the string to store random numbers
    for ($i=1;$i<$digits+1;$i++) { // Loop the number of times of required digits
        if (rand(1,2) == 1) {// to decide the digit should be numeric or alphabet
            $rand_index = array_rand($input);
            $random_generator .= $input[$rand_index]; // One char is added
        } else {
              // Add one numeric digit between 1 and 10
                 $random_generator .=rand(1,10); // one number is added
             } 

    }

return $random_generator;
}


$key=random_generator(10);
$key=md5($key);
$tm=time();

$query = mysqli_query($conn, "INSERT INTO systemUserKey(id,pkey,time,status) VALUES ('$id', '$key', '$tm', 'pending')");
 if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    mysqli_close($conn);
                    break;
                }


// Create email to send user a link
$headersfrom="admin@sitename.com"; // Make this configurable later
$headers.= "From: ".$headersfrom."\n"; 
//$headers = "Content-Type: text/html; charset=iso-8859-1\n".$headers;// for html mail
$site_url = $requestAppURL."resetpassword.php?ak=$key&userid=".$id;
$body="This is in response to your request for login detailst at site_name \n \nLogin ID:". $id ."\n To reset your password, please visit this link( or copy and paste this link in your browser window )\n\n
\n\n $site_url \n\n <a href='".$site_url."'>".$site_url."</a>  \n\n Thank You \n \n siteadmin";

$success = mail($email,"Your Request for login details",$body);

if ($success) {
                   echo "THANK YOU. <br>A link to reset your password has been posted to your email address . Please check your mail shortly.";
              }
else {
          error_log("Bums. Emailing failed for some reason");
          echo "<center><font face='Verdana' size='2' color=red >$msg <br><br><input type='button' value='Retry' onClick='history.go(-1)'></center></font>";
     }


}
?>
