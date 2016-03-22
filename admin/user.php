<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
start_session();
$user = 0;
if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] == "") {
     header('Location: index.php');
}
else {
     $id = $_SESSION['login_user'];
}

if ($userlevel == "1") {
    header('Location: admin.php');
}

if(isset($_GET['user'])) {
    $user = makeSafe($_GET['user']);
}
    $_SESSION['listuser'] = $user;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $company_name; ?> Song Requests</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript">
$(function(){

	// Function for loading the grid
	function LoadGrid() {
		var gridder = $('#as_gridder');
		var UrlToPass = 'action=load';
		gridder.html('loading..');
		$.ajax({
			url : 'userajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function(responseText) {
				gridder.html(responseText);
			}
		});
	}

	LoadGrid(); // Load the grid on page loads

	// Function for deleting all the user's requests
	$('body').delegate('.gridder_delete', 'click', function(){
		var conf = confirm("Are you sure want to delete this user's requests?");
		if(!conf) {
			return false;
		}
		var ThisElement = $(this);
		var UrlToPass = 'action=delete&value='+ThisElement.attr('href');
		$.ajax({
			url : 'userajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function() {
				LoadGrid();
			}
		});
		return false;
	});
	// Function for banning the user
	$('body').delegate('.gridder_ban', 'click', function(){
		var conf = confirm('Are you sure want to ban this user?');
		if(!conf) {
			return false;
		}
		var ThisElement = $(this);
		var UrlToPass = 'action=ban&value='+ThisElement.attr('href');
		$.ajax({
			url : 'userajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function() {
				LoadGrid();
			}
		});
		return false;
	});
});
</script>
</head>

<body>
<div class="as_wrapper">
<div id="logo">&nbsp;</div>
	<h1 class="h1"><a href="">User Management</a></h1>
<?php include 'menu.php'; ?>
<div class="as_wrapper">
	<div class="as_grid_container">

        <div class="as_gridder" id="as_gridder"></div> <!-- GRID LOADER -->
    </div>
</div>
</div>
</body>
</html>
