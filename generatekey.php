<?php
include 'configuration.php';
function random_string()
{
    $character_set_array = array();
    $character_set_array[] = array('count' => 4, 'characters' => 'abcdefghijklmnopqrstuvwxyz');
    $character_set_array[] = array('count' => 2, 'characters' => '0123456789');
    $temp_array = array();
    foreach ($character_set_array as $character_set) {
        for ($i = 0; $i < $character_set['count']; $i++) {
            $temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
        }
    }
    shuffle($temp_array);
    $key = implode('', $temp_array);
    return $key;
}

function checkkey($key)
{
    $conn = mysqli_connect($host, $username, $password, $db);
    $query = mysqli_query($conn,"select * from requestkeys where thekey='$key'");
    $rows = mysqli_num_rows($query);
    mysqli_close($conn); // Closing Connection
    if ($rows == 1 ) {
   return 1;
                     } else {
   return 0;
    }
}


?>
