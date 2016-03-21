<?php
// this can probably be removed when finished
$error='';

$introduceDJ = '';
include_once 'configuration.php';
include_once 'functions/functions.php';
include_once $phpqrcodeFolder . "/qrlib.php";

start_session();
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
            header("Location: timedout.php"); // Redirecting To Timed-Out Page
        }
    }
}

// dont' trust cookies
// $key = $_COOKIE['eventkey'];
$key = preg_replace('/[^\w]/', '', $_COOKIE['eventkey']);
$uniqueid = uniqid();
// If cookie hasn't been set, set it and put this user in the requestuser table
if (!isset($_COOKIE['requestuser'])) {
    setcookie("requestuser", $uniqueid, time() + (60 * 60 * 8), "/"); // 60 * 60 * 8 seconds = 8 hours
    setcookie("requestkey", $key, time() + (60 * 60 * 8), "/"); // 60 * 60 * 8 seconds = 8 hours
    $ip_addr = $_SERVER['REMOTE_ADDR'];
    $conn = mysqli_connect($host, $username, $password, $db);
    $query = mysqli_query($conn, "INSERT INTO requestusers (uniqueid, ipaddr, thekey, createdTime) VALUES ('$uniqueid', '$ip_addr', '$key', NOW())");
    // Delete users older than $maxUserAge days old
    $query = mysqli_query($conn, "DELETE FROM requestusers WHERE createdTime < NOW - INTERVAL '$maxUserAge' DAY");
    // Delete requests older than $maxUserAge days old
    $query = mysqli_query($conn, "DELETE FROM requests WHERE timedate < NOW - INTERVAL '$maxUserAge' DAY");
    mysqli_close($conn);
}
else {
    // dont' trust cookies
	// $uniqueid=$_COOKIE['requestuser'];
	$uniqueid = preg_replace('/[^\w]/', '', $_COOKIE['requestuser']);
    $conn = mysqli_connect($host, $username,$password,$db);
    $query = mysqli_query($conn, "SELECT logintimes,thekey FROM requestusers WHERE uniqueid='".$uniqueid."'");
    $result = mysqli_fetch_row($query);
    $times = $result[0];
    $lastkey = $result[1];
    if ($lastkey != $key) { // If key is different to the one the user originally logged in with, increment their logintimes counter
        $query = mysqli_query($conn, "UPDATE requestusers SET logintimes=logintimes+1 WHERE uniqueid='".$uniqueid."'");
    }

    mysqli_close($conn);

	/* not sure we need this feature....and it makes testing harder
    if ($times > 3) { // If user has logged into 2 events or more, kick em out!
        header('Location: logout.php');
    }
	*/
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

// make or get the QR Code for sharing
QRcode::png($baseURL . 'login.php?eventkey=' . $key, 'images/' . $key . '.png', QR_ECLEVEL_L, 16, 1); 

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">

		<title><?php echo $company_name; ?>  Song Requests</title>

		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<link href="bootstrap/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="theme.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="bootstrap/js/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="bootstrap/js/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

	</head>

	<body role="document">

		<div class="container theme-showcase" role="main">

			<div class="row">
				<div class="col-md-12">
					<a href="logout.php" class="btn btn-danger btn pull-right" role="button">Log Out <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a>
					<button class="btn btn-primary share-btn" type="button" data-toggle="collapse" data-target="#shareqrcode" aria-expanded="false" aria-controls="shareqrcode">
					<span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span> Share
					</button>
				</div>
			</div>

			<div class="row top-buffer collapse" id="shareqrcode">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12 text-center">
							<p>Scan the QR code with your smartphone or tablet to automatically log in:</p>
							<img class="img-responsive shareqrcode" src="images/<?php echo $key; ?>.png" />
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 text-center">
						For QR Code reader apps, we recommend <a href="http://qrafter.com/get/" target="_blank">Qrafter for iOS</a>, and <a href="https://play.google.com/store/apps/details?id=tw.mobileapp.qrcode.banner target="_blank"">QR Code Reader for Android</a>.
						</div>
						<div class="col-md-3">
						<a href="http://qrafter.com/get/" target="_blank"><img class="img-responsive appstores" src="images/app-store.png" alt="Get Qrafter from the App Store"/></a>
						</div>
						<div class="col-md-3">
						<a href="https://play.google.com/store/apps/details?id=tw.mobileapp.qrcode.banner" target="_blank"><img class="img-responsive appstores" src="images/play-store.png" alt="Get QR Code Reader from Google Play"/></a>
						</div>
					</div>
					<div class="row top-buffer">
						<div class="col-md-12 text-center">
							<p>Or the manual method: -</p>
							<p>Visit <strong><?php echo $baseURL; ?></strong> and log in with event key: <strong><?php echo $key; ?><strong></p>
						</div>
					</div>
				</div>
			</div>


			<div class="row top-buffer">
				<div class="col-md-12">
					<img class="img-responsive" src="<?php echo $logoURL; ?>" alt="<?php echo $company_name; ?>"/>
				</div>
			</div>

			<div class="row top-buffer">
				<div class="col-md-12">
					<h1 class="text-center"><?php echo $company_name; ?> request system</h1>
				</div>
			</div>

			<div id="introduceDJ">
				<!-- ajax content -->
			</div>

			<div class="row">
				<div class="col-md-12">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs request-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#addrequest" aria-controls="addrequest" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> New Request</a></li>
						<li role="presentation"><a href="#currentrequests" aria-controls="currentrequests" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Current Requests <span id="requests-badge" class="badge"></span></a></li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane request-pane fade in active" id="addrequest">
								<div class="as_grid_container">
									<div class="as_gridder" id="as_gridder"></div> <!-- GRID LOADER -->
								</div>
						</div>
						<div role="tabpanel" class="tab-pane request-pane fade" id="currentrequests">
							<div id="requests-placeholder">
							</div>
						</div>
					</div>
				</div>
			</div>

		</div> <!-- /container -->

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="bootstrap/js/jquery.1.11.3.min.js"><\/script>')</script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="bootstrap/js/ie10-viewport-bug-workaround.js"></script>

		<!-- Javascript for entries etc. -->
		<script>

		// Function to hider all errors
		function HideErrors() {
			$('.error').hide();
		}

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


		// Function to populate requests
		function LoadRequests() {
			var therequests = $('#requests-placeholder');
			var UrlToPass = 'action=populateRequests';
			therequests.html('loading..');
			$.ajax({
				url : 'requestajax.php?rnd=',
				type : 'POST',
				data : UrlToPass,
				success: function(responseText) {
					therequests.html(responseText);
				}
			});
		}

		// update current requests on tab click
		$('a[data-toggle="tab"][aria-controls="currentrequests"]').on('shown.bs.tab', function (e) {
			LoadRequests();
		})


		$(function(){

			LoadGrid(); // Load the grid on page loads

			LoadRequests(); // Load the requests

			// disable form default submit
			$("#cpa-form").submit(function(e){
				e.preventDefault();
			});


			// Pass the values to ajax page to add the values
			$('body').delegate('#gridder_addrecord', 'click', function(){
				//clear any existing error messages
				HideErrors();
				var suberrors = 0;
				// Do insert validation here
				if($('#name').val() == "") {
					$('#name').focus();
					$('#nameerror').show(); 
					++suberrors;
				}

				if($('#name').val().length > 64) {
					$('#name').focus();
					$('#nameerror_tl').show(); 
					++suberrors;
				}

				if($('#artist').val() == '') {
					$('#artist').focus();
					$('#artisterror').show(); 
					++suberrors;
				}

				if($('#artist').val().length > 64) {
					$('#artist').focus();
					$('#artisterror_tl').show(); 
					++suberrors;
				}

				if($('#title').val() == '') {
					$('#title').focus();
					$('#titleerror').show(); 
					++suberrors;
				}

				if($('#title').val().length > 64) {
					$('#title').focus();
					$('#titleerror_tl').show(); 
					++suberrors;
				}

				if($('#message').val().length > 140) {
					$('#message').focus();
					$('#messageerror_tl').show(); 
					++suberrors;
				}
				if(suberrors > 0) {
					alert("There was a problem with your request.");
				}
				if(suberrors == 0) {
					// Pass the form data to the ajax page
					var data = $('#gridder_addform').serialize();
					$.ajax({
						url : 'requestajax.php',
						type : 'POST',
						data : data,
						success: function(data) {
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
								 // update the requests tab badge / list
								 LoadRequests();
								 // clear the form
								 $('#gridder_addform').trigger("reset");
							}
							// LoadGrid();
						}
					});
				}
				return false;
			});
		});
		</script>

	</body>
</html>
