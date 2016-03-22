<?php

// Reminder: DON'T TRUST COOKIES!
$user = preg_replace('/[^\w]/', '', $_COOKIE['adminuser']);
$userid = preg_replace('/[^\w]/', '', $_COOKIE['adminuserid']);
$userlevel = preg_replace('/[^\w]/', '',  $_COOKIE['adminlevel']);
$adminrealname = preg_replace('/[^\w]/', '', $_COOKIE['adminrealname']);
?>
