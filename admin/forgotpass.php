<?php
include_once 'forgotpassck.php';
include_once '../functions/functions.php';
start_session();

//if(isset($_SESSION['login_user'])){
//header("location: admin.php");
//}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Request System Forgotten Password</title>
        <link href="loginstyle.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <div id="main">
        <div id="logo">&nbsp;</div>
        <h1>Request System Forgotten Password</h1>
        <div id="login">
            <h2>Enter your email address</h2>
            <form action="" method="post">
                <table class="login">
                    <tr class="login">
                        <td><label>Email Address :</label></td>
                        <td><input id="email" name="email" type="text" class="inputbox" style="background-color:#9eff9e"/></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <center>
                                <input name="submit" type="submit" value=" Submit ">
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                    </tr>
                </table>
                <span><?php echo $error; ?></span>
            </form>
        </div>
    </div>
</body>
</html>
