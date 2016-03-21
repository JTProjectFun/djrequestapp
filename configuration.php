<?php
// Your name or company name
$company_name = "Your Mobile DJ";

// where this is installed, with trailing slash e.g. http://www.mydomain.com/requests/
// only used in the generation of QR codes for now
$baseURL = "http://mydomain.ccm/requests/";

// quick and simple effort to stop abuse of QR Code Generator
// append some random alphanumerics to /phpqrcode...
$phpqrcodeFolder = "phpqrcode";

// full URL to your logo - recommended 840px wide
$logoURL = "http://mydomain.ccm/requests/images/logo.png";

// database details next
$host 		= "localhost"; // Your hostname
$username	= "requests"; // Your host username
$password	= "r3qu35t"; // Your host password
$db		= "djrequests"; // Your database name

// don't edit anything below here
$record = "";
$settingdb = mysqli_connect($host, $username, $password, $db) or die("Some error occurred during connection " . mysqli_error($settingdb));

//$query = mysqli_query($settingdb, "SELECT * FROM settings;");
// while($fetch = mysqli_fetch_array($query)) {
//                                $record[] = $fetch;
//}
//$record = mysqli_fetch_assoc($query);
//mysqli_close($query);

$conn = mysqli_connect($host, $username, $password, $db);
                $query  = mysqli_query($conn, "SELECT * FROM `settings`");
                $count  = mysqli_num_rows($query);
                if($count > 0) {
                        $record = mysqli_fetch_array($query);
                }

mysqli_close($conn);
$session_timeout = $record['session_timeout'];
$flood_period = $record['flood_period'];
$over_hours = $record['over_hours'];
$willexpire = $record['willexpire'];
$show_requests = $record['show_requests'];
$maxRequestDefault = $record['maxRequestDefault'];
$maxUserRequestDefault = $record['maxUserRequestDefault'];
?>
