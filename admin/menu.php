<?php
include_once '../configuration.php';
include_once 'adminconfig.php';

function level1Menu(){
echo '<a class="menu" href="requests.php">Admin All Requests</a>';
echo '<a class="menu" href="user.php">Admin Request Users</a>';
echo '<a class="menu" href="password.php">Change Password</a>';
echo '<a class="menu" href="logout.php">Log out</a>';
}

function level2Menu(){
echo '<a class="menu" href="requests.php">Admin All Requests</a>';
echo '<a class="menu" href="user.php">Admin Request Users</a>';
echo '<a class="menu" href="admin.php">Admin Events</a>';
echo '<a class="menu" href="customtext.php">Customise Text</a>';
echo '<a class="menu" href="password.php">Change Password</a>';
echo '<a class="menu" href="logout.php">Log out</a>';
}

function level3Menu(){
echo '<a class="menu" href="settings.php">Settings</a>';
echo '<a class="menu" href="requests.php">Admin All Requests</a>';
echo '<a class="menu" href="user.php">Admin Request Users</a>';
echo '<a class="menu" href="useradmin.php">Admin System Users</a>';
echo '<a class="menu" href="admin.php">Admin Events</a>';
echo '<a class="menu" href="customtext.php">Customise Text</a>';
echo '<a class="menu" href="password.php">Change Password</a>';
echo '<a class="menu" href="logout.php">Log out</a>';
}

$level = preg_replace('/[^\w]/', '',  $_COOKIE['adminlevel']);

switch ($level) {
    case 1:
        level1Menu();
        break;
    case 2:
        level2Menu();
        break;
    case 3:
        level3Menu();
        break;
}

?>

