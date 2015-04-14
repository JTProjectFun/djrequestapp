<?php
include_once '../configuration.php';
include_once 'adminconfig.php';

session_start();
if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] == "") {
     header('Location: index.php');
}
else {
     $id = $_SESSION['login_user'];
}

if (isset($_COOKIE['adminuser'])) {
    $user = $_COOKIE['adminuser'];
    $userlevel = $_COOKIE['adminlevel'];
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DJ Request System Administration</title>
<meta name="keywords" content=""/>
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
			url : 'adminajax.php',
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
			url : 'adminajax.php',
			type : 'POST',
			data : UrlToPass
		});
	}

	LoadGrid(); // Load the grid on page loads

	// Execute datepiker() for date fields
	$("body").delegate("input[type=text].datepiker", "focusin", function(){
		var ThisElement = $(this);
		$(this).datepicker({
	   		dateFormat: 'dd-mm-yy',
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
		if(ThisElement.hasClass('datepiker')) {
			return false;
		}
		$.ajax({
			url : 'adminajax.php',
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
			if(ThisElement.hasClass('datepiker')) {
				return false;
			}
			$.ajax({
				url : 'adminajax.php',
				type : 'POST',
				data : UrlToPass
			});
		}
	});

        // On click, do the toggle thing
        $('body').delegate('.toggle', 'click', function(){
                var ThisElement = $(this);
                var value = 0;
                if ($(ThisElement).prop("checked")){
                    value = 1;
                }
                else {
                    value = 0;
                }
                var UrlToPass = 'action=update&value='+value+'&crypto='+ThisElement.prop('name');
                $.ajax({
                        url : 'adminajax.php',
                        type : 'POST',
                        data : UrlToPass
                });
        });

	// Function to delete the record
	$('body').delegate('.gridder_delete', 'click', function(){
		var conf = confirm('Are you sure want to delete this key and all requests associated with it?');
		if(!conf) {
			return false;
		}
		var ThisElement = $(this);
		var UrlToPass = 'action=delete&value='+ThisElement.attr('href');
		$.ajax({
			url : 'adminajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function() {
				LoadGrid();
			}
		});
		return false;
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
	   		dateFormat: 'dd-mm-yy',
	   });
	});

	// Pass the values to ajax page to add the values
	$('body').delegate('#gridder_addrecord', 'click', function(){
		// Do insert validation here
		if($('#date').val() == '') {
			$('#date').focus();
			alert('Enter the Date');
			return false;
		}
		if($('#thekey').val() == '') {
			$('#thekey').focus();
			alert('Enter the Key');
			return false;
		}

		// Pass the form data to the ajax page
		var data = $('#gridder_addform').serialize();
		$.ajax({
			url : 'adminajax.php',
			type : 'POST',
			data : data,
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
<?php echo "Welcome, " . $username; ?>
	<h1 class="h1"><a href="">Administer Event Keys</a></h1>
<a class="logout" href="settings.php">Settings</a>
<a class="logout" href="requests.php">Admin All Requests</a>
<a class="logout" href="user.php">Admin Request Users</a>
<a class="logout" href="useradmin.php">Admin System Users</a>
<a href="gridder_addnew" id="meh" class="gridder_addnew">Add New Event Key</a>
<a class="logout" href="logout.php">Log out</a>
	<div class="as_grid_container">
            <div class="as_gridder" id="as_gridder"></div> <!-- GRID LOADER -->
        </div>
</div>
</body>
</html>
