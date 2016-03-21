<?php 
include_once 'functions/functions.php';
include_once 'login.php'; // Includes Login Script
start_session(); 

if((isset($_SESSION['eventkey']) && $_SESSION['eventkey'] ='')){
header("location: requests.php");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $company_name; ?> Request System Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="loginstyle.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <div id="main">
        <div id="logo">&nbsp;</div>
        <h1>Your session has timed out due to inactivity</h1>
        <div id="login">
            <h2>Log in again</h2>
            <form action="" method="post">
            <div id="inputting"><label>Key Code</label>
                <input name="eventkey" type="text" class="inputbox" style="background-color:#9eff9e;"/>
            </div>
                            <center>
                                <input name="submit" type="submit" value=" Login ">
                            </center>
                <span><?php echo $error; ?></span>
            </form>
        </div>
    </div>
</body>
</html>
