<?php
include_once '../configuration.php';
// Function for encryption
function encrypt($data) {
	return base64_encode(base64_encode(base64_encode(strrev($data))));
}

// Function for decryption
function decrypt($data) {
	return strrev(base64_decode(base64_decode(base64_decode($data))));
}

function db_connect() {

  // Define connection as a static variable, to avoid connecting more than once 
    static $connection;

    // Try and connect to the database, if a connection has not been established yet
//    if(!isset($connection)) {
         // Load configuration as an array. Use the actual location of your configuration file
//        $config = parse_ini_file('../config.ini'); 
//        $connection = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']);
$connection = mysqli_connect($host,$username,$password,$db);
//    }

    // If connection was not successful, handle the error
    if($connection === false) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error(); 
    }
    return $connection;
}


function db_query($query) {

  // Connect to the database
    $connection = db_connect();

    // Query the database
    $result = mysqli_query($connection,$query);

    // Close the database conenction
    mysqli_close($connection);
    return $result;

}

function start_session() {
//  if(version_compare(phpversion(), "5.4.0") != -1){
//    if (session_status() == PHP_SESSION_NONE) {
      session_start();
//    }
//  } else {
//    if(session_id() == '') {
//      session_start();
//    }
//  }
}

?>
