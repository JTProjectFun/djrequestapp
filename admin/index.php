<?php
include_once 'login.php'; // Includes Login Script
include_once '../functions/functions.php';
start_session();

if(isset($_SESSION['login_user'])){
header("location: admin.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Mr Deejay Request System Login</title>
        <link href="loginstyle.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <div id="main">
        <div id="logo">&nbsp;</div>
        <h1>Mr Deejay Request System Login</h1>
        <div id="login">
            <h2>Enter your username & password</h2>
            <form action="" method="post">
                <table class="login">
                    <tr class="login">
                        <td><label>UserName :</label></td>
                        <td class="logininput"><input name="username" type="text" class="inputbox" style="background-color:#9eff9e"/></td>
                    </tr>
                    <tr class="login">
                        <td><label>Password :</label></td>
                        <td><input id="password" name="password" type="password" class="inputbox" /></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <center>
                                <input name="submit" type="submit" value=" Login ">
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <center>
                                <a href="forgotpass.php">Forgot Your Password?</a>
                            </center>
                        </td>
                    </tr>
                </table>
                <span><?php echo $error; ?></span>
            </form>
        </div>
    </div>
</body>
</html>
