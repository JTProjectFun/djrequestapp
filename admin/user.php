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

<?php include 'adminheadertop.php'; ?>
<div class="row">
    <div class="col-md-12">
        <h1 class="h1"><a href="">Administer Users</a></h1>
    </div>
</div>
<div class="row">
    <div id="adminuser">
        <!-- ajax content -->
    </div>
</div>

<!-- put all the java stuff here -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../bootstrap/js/jquery.1.11.3.min.js"><\/script>')</script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../bootstrap/js/ie10-viewport-bug-workaround.js"></script>
<script src="../datepicker/js/bootstrap-datepicker.js"></script>

<script type="text/javascript">

	// Function for loading the grid
	function LoadGrid() {
		var gridder = $('#adminuser');
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
</script>
</body>
</html>
