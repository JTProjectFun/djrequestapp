<?php
include_once '../configuration.php';

?>
<html>
<head>
<title>Install DJ Request System</title>
</head>
<body>

<?php
if(isset($_POST['install']))
{

    $mysqlhostname = mysql_escape($_POST['mysqlhostname']);
    $mysqlport = mysql_escape($_POST['mysqlport']);
    $mysqlhostnameport = $mysqlhostname . ":" . $mysqlport;
    $mysqlroot = mysql_escape($_POST['mysqlroot']);
    $mysqlpassword = mysql_escape($_POST['mysqlpassword']);
    $mysqldbname = mysql_escape($_POST['mysqldbname']);
    $mysqldjuser = mysql_escape($_POST['mysqldjuser']);
    $mysqldjpass = mysql_escape($_POST['mysqldjpass']);

    $conn = mysql_connect($mysqlhostnameport, $mysqlroot, $mysqlpassword);

    if(! $conn )
    {
         die('Could not connect as administrative MySQL user: ' . mysql_error());
    }

    mysql_query("CREATE USER '{$mysqldjuser}'@'{$mysqlhostname}' IDENTIFIED BY '{$mysqldjpass}'");
echo mysqli_info($con);

    if ( mysqli_info($con)) {
        die('Could not create MySQL user ' . $mysqldjuser);
    }

    mysql_query("GRANT ALL ON '$mysqldbname'.* TO '$mysqldjuser'@'$mysqlhostname'");

    if (! mysql_info()) {
        die('Could not grant privileges on ' . $mysqldbname . ' to MySQL user ' . $mysqldjuser);
    }

    mysql_select_db($mysqldbname);

    if (! mysql_info()) {
        die('Could not select MySQL database ' . $mysqldbname);
    }

    mysql_query("CREATE DATABASE IF NOT EXISTS '$mysqldbname'");

    if (! mysql_info()) {
        die('Could not create MySQL user ' . $mysqldjuser);
    }

    mysql_query("CREATE TABLE IF NOT EXISTS admin(username varchar(32),password varchar(16))");

    if (! mysql_info()) {
        die('Could not create admin table');
    }

    mysql_query("CREATE TABLE IF NOT EXISTS settings(session_timeout int default 120, flood_period int default 30,over_hours int default 2,willexpire int default 1,show_requests int default 1)");

    if (! mysql_info()) {
        die('Could not create settings table');
    }

    mysql_query("CREATE TABLE IF NOT EXISTS requestkeys (id int NOT NULL AUTO_INCREMENT, timedate varchar(32),thekey varchar(8) NOT NULL, date varchar(32), showrequests int(1), willexpire int(1), PRIMARY KEY(id))");

    if (! mysql_info()) {
        die('Could not create requestkeys table');
    }

    mysql_query("CREATE TABLE IF NOT EXISTS requests(id int NOT NULL AUTO_INCREMENT,timedate varchar(32),thekey NOT NULL varchar(8),name varchar(64),artist varchar(64),title varchar(64),message varchar(140),willplay int,played int,ipaddr varchar(20),uniqueid varchar(64),PRIMARY KEY(id))");

    if (! mysql_info()) {
        die('Could not create requests table');
    }

    echo "Database created successfully\n";
    mysql_close($conn);

}


?>

<form method="post" action="<?php $_PHP_SELF ?>">
<table width="800" border="0" cellspacing="1" cellpadding="2">
<tr>
<td width="100">MySQL Host</td>
<td width="200"><input name="mysqlhost" type="text" id="mysqlhost" value="localhost"></td>
<td width="400">Hostname of the MySQL server to use with the DJ Request System. This can usually be left as 'localhost'</td>
</tr>
<tr>
<td width="200">MySQL Port</td>
<td><input name="mysqlport" type="text" id="mysqlport" value="3306"></td>
<td width="400">Port the MySQL server is bound to. This can usually be left as '3306'</td>
</tr>
<tr>
<td width="100">MySQL Root User</td>
<td><input name="mysqlroot" type="text" id="mysqlroot" value="root"></td>
<td width="400">MySQL user with privileges to create users, change privileges & create databases. This can usually be left as 'root'</td>
</tr>
<tr>
<td width="100">MySQL Root Password</td>
<td><input name="mysqlpassword" type="text" id="mysqlpassword"></td>
<td width="400">MySQL root user password</td>
</tr>
<tr>
<td width="100">MySQL Database Name</td>
<td><input name="mysqldbname" type="text" id="mysqldbname" value="djrequests"></td>
<td width="400">The name of the DJ Request System MySQL database</td>
</tr>
<tr>
<td width="100">MySQL DJ Database User</td>
<td><input name="mysqldjuser" type="text" id="mysqldjuser" value="djrequestuser"></td>
<td width="400">The MySQL username of the DJ Request System database user</td>
</tr>
<tr>
<td width="100">MySQL DJ Database Password</td>
<td><input name="mysqldjpass" type="text" id="mysqldjpass" value="r3qu35t5"></td>
<td width="400">The MySQL password of the DJ Request System database user</td>
</tr>
<td width="100"> </td>
<td> </td>
</tr>
<tr>
<td width="100"> </td>
<td>
<input name="install" type="submit" id="install" value="Initialise DJ Request Database">
</td>
</tr>
</table>
</form>

