<?php
session_start();
include('login.php'); // Includes Login Script

if((isset($_SESSION['eventkey']) && $_SESSION['eventkey'] ='')){
header("location: requests.php");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Mr Deejay Request System Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="loginstyle.css" rel="stylesheet" type="text/css">
<script type="text/javascript"> 
    $(document).bind("mobileinit", function () {
        // jQuery Mobile's Ajax navigation does not work in all cases (e.g.,
        // when navigating from a mobile to a non-mobile page), especially when going back, hence disabling it.
        $.extend($.mobile, {
            ajaxEnabled: false
        });
    }); 
</script>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script type="text/javascript">

            $(document).ready(function(){
                $("#helpbutton").click(function(){
                    $("#helppopup").show();
                });

            $("#helpclose").click(function(){
                    $("#helppopup").hide();
                });
        });
        </script>
    </head>
    <body>
    <div id="main">
        <div id="logo">&nbsp;</div>
        <h1>Log in to the request system</h1>
        <div id="login">
            <h2>Enter the event key code</h2>
            <form action="" method="post">
                <div id="inputting">
                    <input name="eventkey" type="text" class="inputbox" style="background-color:#9eff9e;"/>
                 </div>
                <center>
                    <input name="submit" type="submit" value=" Login ">
                </center>
                <span><?php echo $error; ?></span>
            </form>
        </div>
        <h1 id="helpbutton">How to use this service</h1>
        <div id="helppopup">
            <div id="helpcontents">
                <div id="logo">&nbsp;</div>
                    <h1>Mr Deejay Request System Help</h1>
                    <div id="helpmain">
                        <h1 class="title">Logging in</h1>
                        <div>To log in, enter the key code provided at the venue. Requests cannot be made without logging in.</div>
                        <div>If the key code entered incorrectly it will not be possible to log in.</div>
                        <h1 class="title">Using the system</h1>
                        <div>Once logged in, click the 'Add New Request' button.</div>
                        <div>A form will be shown where your name, the song artist, title and an optional message can be added.</div>
                        <div>If there are problems with the information used on the form, error popups will be shown</div>
                        <div>Upon a request being submitted successfully, a popup will be displayed to say so.</div>
                        <div>In some circumstances a list of all requests for this event will be displayed.</div>
                        <h1 class="title">Logging out</h1>
                        <div>To log out, click the 'log out' button at the bottom of the requests page. Requests cannot be made once logged out. To make further requests, log in with the correct key code again.</div>
                        <h1 class="title">Time out</h1>
                        <div>All logged in users are automatically logged out after 2 hours. To make requests again, simply log in again with the correct key code<p></p></div>
                    </div>
                    <div id="helpclose">
                        <h1>CLOSE & RETURN TO THE LOGIN PAGE</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
