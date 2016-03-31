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
    // If I told you once, I told you a million times. DON'T TRUST COOKIES!
    $user = makeSafe($_COOKIE['adminuser']);
    $userlevel = makeSafe($_COOKIE['adminlevel']);
}

?>
<?php include 'adminheadertop.php'; ?>
<div class="row">
    <div class="container-fluid">
        <h1 class="h1"><a href="">Administer Events</a></h1>
        <a href="gridder_addnew" class="gridder_addnew">Add New Event</a>
    </div>
</div>
<div class="row">
    <div class="container-fluid">
        <div id="adminlist">
            <!-- ajax content -->
        </div>
    </div>
</div>

<?php include 'javaincludes.php'; ?>
<script type="text/javascript"> 
        // Function to hide all errors
        function HideErrors() {
            $('.error').hide();
        }
	// Function for loading the grid
	function LoadGrid() {
		var gridder = $('#adminlist');
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

	LoadGrid(); // Load the grid on page loads

	// Seperate Function for datepicker() to save the value
	function ForDatePiker(ThisElement) {
		ThisElement.prev('span').html(ThisElement.val()).prop('title', ThisElement.val());
		var UrlToPass = 'action=update&value='+ThisElement.val()+'&crypto='+ThisElement.prop('name');
		$.ajax({
			url : 'adminajax.php',
			type : 'POST',
			data : UrlToPass
		});
	}

	// Execute datepicker() for date fields
	$("body").delegate("input[type=text].datepiker", "focusin", function(){
		var ThisElement = $(this);
		$(this).datepicker({
                                    format:"dd-mm-yyyy"
                                   })
                                    .on('changeDate', function() {
                                                                  $(this).datepicker('hide');
                                                                  //setTimeout(ForDatePiker(ThisElement), 500);
                                                                  ForDatePiker(ThisElement);
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
			data : UrlToPass,
                        success : function(data) {
                            if(data.status.indexOf("sqlerror") >=0) {
                                if (data.status.indexOf("Duplicate entry") >=0) {
                                    alert ("Whoops. That event key was not unique. Try again with a different key.");
                                    LoadGrid();
                                }
                           }
LoadGrid();
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
			if(ThisElement.hasClass('datepiker')) {
				return false;
			}
			$.ajax({
				url : 'adminajax.php',
				type : 'POST',
				data : UrlToPass,
success : function(data) {
                            if(data.status.indexOf("sqlerror") >=0) {
                                if (data.status.indexOf("Duplicate entry") >=0) {
                                    alert ("Whoops. That event key was not unique. Try again with a different key.");
                                    LoadGrid();
                                }
                           }
LoadGrid();
}
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

	// For datepicker
	$("body").delegate(".datepiker", "focusin", function(){
		var ThisElement = $(this);
		$(this).datepicker({
	   		format: 'dd-mm-yyyy',
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
			success: function(data) {
                                                     if(data.status.indexOf("sqlerror") >=0) {
                                                                                                if (data.status.indexOf("Duplicate entry") >=0) {
                                                                                                alert ("Whoops. That event key was not unique. Try again with a different key.");
                                                                                                return false;
                                                                                              }
                                                }
			LoadGrid();
			}
		});
		return false;
	});
</script>
</body>
</html>
