<?php
//include_once (dirname(__DIR__).'/config.php');
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
    global $connection;

$init = parse_ini_file(dirname(__DIR__).'/config.php');
    if(!isset($connection)) {
        $connection = mysqli_connect($init['host'],$init['username'],$init['password'],$init['db']);
    }

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
    // Close the database connection
//    mysqli_close($connection);
    return $result;

}

function db_select($query) {
    $rows = array();
    // Query the database
    $sel_result = db_query($query);

    if($sel_result === false) {
        return false;
    }

    while ($row = mysqli_fetch_assoc($sel_result)) {
        $rows[] = $row;
    }
    return $rows;

}

function start_session() {
  if(version_compare(phpversion(), "5.4.0") != -1){
      if (session_status() == PHP_SESSION_NONE) {
          session_start();
      }
  } else {
            if(session_id() == '') {
                session_start();
            }
    }
}

?>
