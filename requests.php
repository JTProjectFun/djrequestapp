<?php
session_start();
include_once 'configuration.php';
$bypass = 0;

// check user is logged in
if (!(isset($_COOKIE['eventkey']) && $_COOKIE['eventkey'] != ''))
{
  header("Location: index.php");
}

if (isset($_SESSION['timeout'])){
    if ($_SESSION['timeout'] + $session_timeout * 60 < time()) {
        if(session_destroy()) // Destroying All Sessions
        {
            header("Location: timedout.php"); // Redirecting To Home Page
        }
    }
}

$key = $_COOKIE['eventkey'];
$uniqueid = uniqid();
// If cookie hasn't been set, set it and put this user in the requestuser table
if (!isset($_COOKIE['requestuser'])) {
    setcookie("requestuser", $uniqueid, time() + (60 * 60 * 8), "/"); // 60 * 60 * 8 seconds = 8 hours
    setcookie("requestkey", $key, time() + (60 * 60 * 8), "/"); // 60 * 60 * 8 seconds = 8 hours
    $ip_addr = $_SERVER['REMOTE_ADDR'];
    $conn = mysqli_connect($host, $username, $password, $db);
    $query = mysqli_query($conn, "INSERT INTO requestusers (uniqueid, ipaddr, thekey, createdTime) VALUES ('$uniqueid', '$ip_addr', '$key', NOW())");
    // Delete users older than $maxUserAge days old
    $query = mysql_query($conn, "DELETE FROM requestusers WHERE createdTime < NOW - INTERVAL '$maxUserAge' DAY");
    // Delete requests older than $maxUserAge days old
    $query = mysql_query($conn, "DELETE FROM requests WHERE timedate < NOW - INTERVAL '$maxUserAge' DAY");
    mysqli_close($conn);
}
else {
    $uniqueid=$_COOKIE['requestuser'];
    $conn = mysqli_connect($host, $username,$password,$db);
    $query = mysqli_query($conn, "SELECT logintimes,thekey FROM requestusers WHERE uniqueid='".$uniqueid."'");
    $result = mysqli_fetch_row($query);
    $times = $result[0];
    $lastkey = $result[1];
    if ($lastkey != $key) { // If key is different to the one the user originally logged in with, increment their logintimes counter
        $query = mysqli_query($conn, "UPDATE requestusers SET logintimes=logintimes+1 WHERE uniqueid='".$uniqueid."'");
    }

    mysqli_close($conn);

    if ($times > 3) { // If user has logged into 2 events or more, kick em out!
        header('Location: logout.php');
    }
}
// Better check key exists. If not, kick back to login page
$result=""; 
$record="";
$conn = mysqli_connect($host, $username, $password, $db);
$result = mysqli_query($conn, "select thekey from requestkeys where thekey='$key'");
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $row = mysqli_fetch_row($result);
            $result = $row[0];
        }
        mysqli_close($conn);

if (empty($result)) {
    header("Location: logout.php");
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mr Deejay Song Requests</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript">
$(function(){
	function HideErrors() {
            $('#nameerror').hide();
            $('#nameerror_tl').hide();
            $('#artisterror').hide();
            $('#artisterror_tl').hide();
            $('#titleerror').hide();
            $('#titleerror_tl').hide();
            $('#messageerror_tl').hide();
        }

	$('body').delegate('.gridder_add', 'click', function(){
            HideErrors();
	});

	// Function for load the grid
	function LoadGrid() {
		var gridder = $('#as_gridder');
		var UrlToPass = 'action=load';
		gridder.html('loading..');
		$.ajax({
			url : 'requestajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function(responseText) {
				gridder.html(responseText);
			}
		});
	}

	// Seperate Function for datepiker() to save the value
	function ForDatePiker(ThisElement) {
		ThisElement.prev('span').html(ThisElement.val()).prop('title', ThisElement.val());
		var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
		$.ajax({
			url : 'requestajax.php',
			type : 'POST',
			data : UrlToPass
		});
	}
	
	LoadGrid(); // Load the grid on page loads
	
	// Execute datepiker() for date fields
	$("body").delegate("input[type=text].datepicker", "focusin", function(){
		var ThisElement = $(this);
		$(this).datepicker({
	   		dateFormat: 'yy/mm/dd',
			onSelect: function() {
				setTimeout(ForDatePiker(ThisElement), 500);
			}
	   });
	});
	
	// Show the text box on click
	$('body').delegate('.editable', 'click', function(){
		var ThisElement = $(this);
		ThisElement.find('span').hide();
		ThisElement.find('.gridder_input').show().focus();
	});
	
	// Pass and save the textbox values on blur function
	$('body').delegate('.gridder_input', 'blur', function(){
		var ThisElement = $(this);
		ThisElement.hide();
		ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
		var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
		if(ThisElement.hasClass('datepicker')) {
			return false;
		}
		$.ajax({
			url : 'requestajax.php',
			type : 'POST',
			data : UrlToPass
		});
	});

	// Same as the above blur() when user hits the 'Enter' key
	$('body').delegate('.gridder_input', 'keypress', function(e){
		if(e.keyCode == '13') {
			var ThisElement = $(this);
			ThisElement.hide();
			ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
			var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
			if(ThisElement.hasClass('datepicker')) {
				return false;
			}
			$.ajax({
				url : 'requestajax.php',
				type : 'POST',
				data : UrlToPass
			});
		}
	});
	
	// Add new record
	
	// Add new record when the table is empty
	$('body').delegate('.gridder_insert', 'click', function(){
		$('#norecords').hide();
		$('#addnew').slideDown();
		return false;
	});
	
	// Add new record when the table in non-empty
	$('body').delegate('.gridder_addnew', 'click', function(){
		$('html, body').animate({ scrollTop: $('.as_gridder').offset().top}, 250); // Scroll to top gridder table
		$('#addnew').slideDown();
		return false;
	});
	
	// Cancel the insertion
	$('body').delegate('.gridder_cancel', 'click', function(){
		LoadGrid()
		return false;
	});
	
	// For datepiker
	$("body").delegate(".gridder_add.datepiker", "focusin", function(){
		var ThisElement = $(this);
		$(this).datepicker({
	   		dateFormat: 'yy/mm/dd'
	   });
	});
	
	// Pass the values to ajax page to add the values
	$('body').delegate('#gridder_addrecord', 'click', function(){
		// Do insert validation here
		if($('#name').val() == '') {
		    $('#name').focus();
                    $('#nameerror').show(); 
		return false;
		}

		if($('#name').val().length > 64) {
			$('#name').focus();
                        $('#nameerror_tl').show(); 
                        return false;
                }

		if($('#artist').val() == '') {
			$('#artist').focus();
                        $('#artisterror').show(); 
			return false;
		}

		if($('#artist').val().length > 64) {
			$('#artist').focus();
                        $('#artisterror_tl').show(); 
                        return false;
                }

		if($('#title').val() == '') {
			$('#title').focus();
                        $('#titleerror').show(); 
			return false;
		}

		if($('#title').val().length > 64) {
			$('#title').focus();
                        $('#titleerror_tl').show(); 
                        return false;
                }

		if($('#message').val().length > 140) {
			$('#message').focus();
                        $('#messageerror_tl').show(); 
                        return false;
                }
		// Pass the form data to the ajax page
		var data = $('#gridder_addform').serialize();
		$.ajax({
			url : 'requestajax.php',
			type : 'POST',
			data : data,
			success: function(data) {
                            if (data.status == "nametooshort") {
                                 setTimeout(function(){ $('#nametooshort').show(); }, 100);
                                 setTimeout(function(){ $('#nametooshort').fadeOut('fast'); }, 8000);
                                $('#name').focus();
                            }
                            if (data.status == "artisttooshort") {
                                 setTimeout(function(){ $('#artisttooshort').show(); }, 100);
                                 setTimeout(function(){ $('#artisttooshort').fadeOut('fast'); }, 8000);
                                $('#name').focus();
                            }
                            if (data.status == "titletooshort") {
                                 setTimeout(function(){ $('#titletooshort').show(); }, 100);
                                 setTimeout(function(){ $('#titletooshort').fadeOut('fast'); }, 8000);
                                $('#name').focus();
                            }
                            if (data.status == "toomany") {
                                 setTimeout(function(){ $('#toomany').show(); }, 100);
                                 setTimeout(function(){ $('#toomany').fadeOut('fast'); }, 8000);
                            }
                            if (data.status == "toomanyuser") {
                                 setTimeout(function(){ $('#toomanyuser').show(); }, 100);
                                 setTimeout(function(){ $('#toomanyuser').fadeOut('fast'); }, 8000);
                            }
                            if (data.status == "banned") {
                                 setTimeout(function(){ $('#banned').show(); }, 100);
                                 setTimeout(function(){ $('#banned').fadeOut('fast'); }, 8000);
                            }
                            if (data.status == "flood") {
                                 setTimeout(function(){ $('#floodalert').show(); }, 100);
                                 setTimeout(function(){ $('#floodalert').fadeOut('fast'); }, 8000);
                            }
                            if (data.status == "success") {
                                 setTimeout(function(){ $('#goodpopup').show(); }, 100);
                                 setTimeout(function(){ $('#goodpopup').fadeOut('fast'); }, 5000);
                            }
			    LoadGrid();
                        }
		});
		return false;
	});
});
</script>
</head>

<body>
<div id="main">
 <div id="logo">&nbsp;</div>
	<h1 class="h1"><a href="">Song Requests</a></h1>
        <h1 class="gridder_addnew"><a href="gridder_addnew" id="gridder_addnew" class="gridder_addnew">Click HERE to add a new request</a></h1>

        <div class="as_wrapper">
	    <div class="as_grid_container">
                <div class="as_gridder" id="as_gridder"></div> <!-- GRID LOADER -->
            </div>
 <a class="logout" href="logout.php">Log Out</a>
        </div>
</div>
</body>
</html>
