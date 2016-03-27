
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

if ($userlevel < 3) {
    header('Location: admin.php');
}

$key = 0;

if (isset($_GET['key'])) {
  $key = makeSafe($_GET['key']);
}

$_SESSION['key'] = $key;

?>
<?php include('adminheadertop.php'); ?>
<div class="row">
    <div class="col-md-12">
	<h1 class="h1"><a href="">System User Admin</a></h1>
        <a href="gridder_addnew" class="gridder_addnew">Add New User</a>
    </div>
</div>
<div class="row">
    <div id="adminsysusers">
        <!-- ajax content -->
    </div>
</div>
<?php include 'javaincludes.php'; ?>
<script type="text/javascript">
	// Function for loading the grid
	function LoadGrid() {
		var gridder = $('#adminsysusers');
		var UrlToPass = 'action=load';
		gridder.html('loading..');
		$.ajax({
			url : 'useradminajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function(responseText) {
				gridder.html(responseText);
			}
		});
	}
	
	LoadGrid(); // Load the grid on page loads
	
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
		var UrlToPass = 'action=toggle&value='+value+'&crypto='+ThisElement.prop('name');
		$.ajax({
			url : 'useradminajax.php',
			type : 'POST',
			data : UrlToPass
		});
	});

	// Pass and save the textbox values on blur function
	$('body').delegate('.gridder_input', 'blur', function(){
		var ThisElement = $(this);
		ThisElement.hide();
                if (ThisElement.prop('type') == "password") {
                    ThisElement.prev('span').show().html("************").prop('title', $(this).val());
                }
                else {
		    ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
                }
		var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
		$.ajax({
			url : 'useradminajax.php',
			type : 'POST',
			data : UrlToPass,
                        success : function(data) {
                                    if(data.status.indexOf("sqlerror") >=0) {
                                        if (data.status.indexOf("Duplicate entry") >=0) {
                                            alert ("Whoops. You can't use a username more than once. Try again.");
                                            LoadGrid();
                                        }
                                    }
                               }

		});
	});

	// Same as the above blur() when user hits the 'Enter' key
	$('body').delegate('.gridder_input', 'keypress', function(e){
		if(e.keyCode == '13') {
			var ThisElement = $(this);
			ThisElement.hide();
			ThisElement.prev('span').show().html($(this).val()).prop('title', $(this).val());
			var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
			$.ajax({
				url : 'useradminajax.php',
				type : 'POST',
				data : UrlToPass,
                                success : function(data) {
                                    if(data.status.indexOf("sqlerror") >=0) {
                                        if (data.status.indexOf("Duplicate entry") >=0) {
                                            alert ("Whoops. You can't use a username more than once. Try again.");
                                            LoadGrid();
                                        }
                                    }
                               }
			});
		}
	});
	
	// Function for deleting all of one user's requests
	$('body').delegate('.gridder_deleteuserreq', 'click', function(){
		var conf = confirm("Are you sure want to delete all of this user's requests?");
		if(!conf) {
			return false;
		}
		var ThisElement = $(this);
		var UrlToPass = 'action=deleteuserreq&value='+ThisElement.attr('href');
		$.ajax({
			url : 'useradminajax.php',
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
			url : 'useradminajax.php',
			type : 'POST',
			data : UrlToPass,
			success: function() {
				LoadGrid();
			}
		});
		return false;
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
			url : 'useradminajax.php',
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
$('.gridder_addnew').hide();
		$('#addnew').slideDown();
		return false;
	});
	
	// Cancel the insertion
	$('body').delegate('.gridder_cancel', 'click', function(){
$('.gridder_addnew').show();
		LoadGrid()
		return false;
	});
	
	// Pass the values to ajax page to add the values
	$('body').delegate('#gridder_addrecord', 'click', function(){
		// Do insert validation here
		if($('#name').val() == '') {
			$('#name').focus();
                             alert('The "Name" field cannot be left blank. Please enter a name.');
		return false;
		}

		// Pass the form data to the ajax page
		var data = $('#gridder_addform').serialize();

		$.ajax({
			url : 'useradminajax.php',
			type : 'POST',
			data : data,
			success: function() {
                         $('.gridder_addnew').show();
				LoadGrid();
			}
		});
		return false;
	});
</script>
</body>
</html>
