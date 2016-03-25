<?php
include_once '../configuration.php';
include_once 'adminconfig.php';

echo '<nav class="navbar">';
echo '<div class="container-fluid">';
echo '<ul class="nav navbar-nav">';

function level1Menu(){
echo '<li><a href="requests.php">All Requests</a></li>';
echo '<li><a href="user.php">Request Users</a></li>';
echo '<li><a href="password.php">Change Password</a></li>';
}

function level2Menu(){
echo '<li><a href="requests.php">All Requests</a></li>';
echo '<li><a href="user.php">Request Users</a></li>';
echo '<li><a href="admin.php">Events</a></li>';
echo '<li><a href="customtext.php">Customise Text</a></li>';
echo '<li><a href="password.php">Change Password</a></li>';
}

function level3Menu(){
echo '<li><a href="settings.php">Settings</a></li>';
echo '<li><a href="requests.php">All Requests</a></li>';
echo '<li><a href="user.php">Request Users</a></li>';
echo '<li><a href="useradmin.php">System Users</a></li>';
echo '<li><a href="admin.php">Events</a></li>';
echo '<li><a href="customtext.php">Customise Text</a></li>';
echo '<li><a  href="password.php">Change Password</a></li>';
}

$level = makeSafe($_COOKIE['adminlevel']);

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

echo '</ul>';
echo '<ul>';
echo'<li><a href="logout.php" class="btn btn-danger btn pull-right" role="button">Log Out <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>';
echo '<ul>';
echo '</div>';
echo '</nav>';

?>
