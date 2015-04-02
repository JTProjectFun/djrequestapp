<?php
include '../configuration.php';
$user = 0;
session_start();
if(!isset($_SESSION['login_user']) || $_SESSION['login_user'] == "") {
     header('Location: index.php');
}
else {
     $id = $_SESSION['login_user'];
}

if ($_GET["user"]) {
  $user = $_GET["user"];
}
else header('Location: admin.php');

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
	
	// Function for load the grid
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
	
	// Seperate Function for datepiker() to save the value
	function ForDatePiker(ThisElement) {
		ThisElement.prev('span').html(ThisElement.val()).prop('title', ThisElement.val());
		var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
		$.ajax({
			url : 'userajax.php',
			type : 'POST',
			data : UrlToPass
		});
	}
	
	LoadGrid(); // Load the grid on page loads
	
	// Execute datepiker() for date fields
	$("body").delegate("input[type=text].datepicker", "focusin", function(){
		var ThisElement = $(this);
		$(this).datetimepicker({
	   		dateFormat: 'yy/mm/dd',
			onSelect: function() {
				setTimeout(ForDatePiker(ThisElement), 500);
			}
	   });
	});
	
        // Show the tickable thing on click
        $('body').delegate('.tickable', 'click', function(){
            var ThisElement = $(this);
            ThisElement.find('span').hide();
            ThisElement.find('.gridder_input').show().focus();
        });
            
	// Show the text box on click
	$('body').delegate('.editable', 'click', function(){
		var ThisElement = $(this);
		ThisElement.find('span').hide();
		ThisElement.find('.gridder_input').show().focus();
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
			url : 'userajax.php',
			type : 'POST',
			data : UrlToPass
		});
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
			url : 'userajax.php',
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
				url : 'userajax.php',
				type : 'POST',
				data : UrlToPass
			});
		}
	});
	
	// Function for delete the record
	$('body').delegate('.gridder_delete', 'click', function(){
		var conf = confirm('Are you sure want to delete this record?');
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
	   		dateFormat: '@'
	   });
	});
	
	// Pass the values to ajax page to add the values
	$('body').delegate('#gridder_addrecord', 'click', function(){
		// Do insert validation here
		if($('#name').val() == '') {
			$('#name').focus();
                             alert('The "Name" field cannot be left blank. Please enter a name.');
		return false;
		}
		if($('#artist').val() == '') {
			$('#artist').focus();
                        alert('The "Artist" field cannot be left blank. Please enter an artist.');
			return false;
		}
		if($('#title').val() == '') {
			$('#title').focus();
                        alert('The "Title" field cannot be left blank. Please enter a title.');

			return false;
		}
		if($('#message').val().length > 140)	 {
                        $('#message').focus();
                             alert('The "Message" field cannot contain more than 140 characters. Consider using a shorter message.');
                        return false;
                }
		// Pass the form data to the ajax page
		var data = $('#gridder_addform').serialize();
		$.ajax({
			url : 'userajax.php',
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
	<h1 class="h1"><a href="">Song Requests</a></h1>
<a class="logout" href="admin.php">Keys Admin</a>
<a class="logout" href="logout.php">Log out</a>

<div class="as_wrapper">
	<div class="as_grid_container">

        <div class="as_gridder" id="as_gridder"></div> <!-- GRID LOADER -->
    </div>
</div>
</div>
</body>
</html>
