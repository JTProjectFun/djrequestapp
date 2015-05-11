<?php
$init = parse_ini_file('config.php');
$host = $init['host'];
$username = $init['username'];
$password = $init['password'];
$db = $init['db'];

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
$baseURL = $record['baseURL'];
$session_timeout = $record['session_timeout'];
$flood_period = $record['flood_period'];
$over_hours = $record['over_hours'];
$willexpire = $record['willexpire'];
$show_requests = $record['show_requests'];
$maxRequestDefault = $record['maxRequestDefault'];
$maxUserRequestDefault = $record['maxUserRequestDefault'];
?>
