<?php
include_once '../configuration.php';
include_once 'adminconfig.php';
include_once '../functions/functions.php';
start_session();

if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] == "") {
     header('Location: index.php');
}
else {
     $id = $_SESSION['login_user'];
}

if (isset($_COOKIE['adminuser'])) {
    $user = makeSafe($_COOKIE['adminuser']);
    $userlevel = makeSafe($_COOKIE['adminlevel']);
}

?>
<?php include 'adminheadertop.php'; ?>
<div class="row">
    <div class="col-md-12">
        <h1 class="h1"><a href="">Change Your Password</a></h1
    </div>
</div>
<div class="row">
   <div id="adminpassword">
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
		var gridder = $('#adminpassword');
		var UrlToPass = 'action=load';
		gridder.html('loading..');
		$.ajax({
			url : 'passwordajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function(responseText) {
				gridder.html(responseText);
			}
		});
	}


	LoadGrid(); // Load the grid on page loads

	// Same as the above blur() when user hits the 'Enter' key
	$('body').delegate('.gridder_input', 'keypress', function(e){
		if(e.keyCode == '13') {
			var ThisElement = $(this);
			ThisElement.hide();
			ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
			var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
			$.ajax({
				url : 'passwordajax.php',
				type : 'POST',
				data : UrlToPass
			});
		}
	});

	// Pass the values to ajax page to add the values
	$('body').delegate('#changepassword', 'click', function(){
		// Do insert validation here
		// Pass the form data to the ajax page
		var data = $('#changepassform').serialize();
		$.ajax({
			url : 'passwordajax.php',
			type : 'POST',
			data : data,
			success: function(data) {
                            if(data.status.indexOf("sqlerror") >=0) {
                                alert ("SQL error.");
                                return false;
                            }
				LoadGrid();
			}
		});
		return false;
	});
</script>

</body>
</html>
