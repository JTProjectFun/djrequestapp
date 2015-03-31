<?php
session_start(); // Starting Session
include_once 'configuration.php';
include_once 'functions/functions.php';

$result=""; // Initialise variables which may have been previously used & would contain data already
$fetch=""; // Initialise variables which may have been previously used & would contain data already
$record=""; // Initialise variables which may have been previously used & would contain data already

$action = $_REQUEST['action'];
$key = $_SESSION['key'];
$uniqueid = $_SESSION['uniqueid'];
$error = '';
switch($action) {
	case "load":
                $conn = mysqli_connect($host, $username, $password, $db);
                $result = mysqli_query($conn, "SELECT * FROM requests WHERE thekey='".$key."' ORDER BY timedate DESC");
                $count = mysqli_num_rows($result);

                if($count > 0) {
                        while($fetch = mysqli_fetch_assoc($result)) {
                                $record[] = $fetch;
                        }
                }

                mysqli_close($conn);

                $conn = mysqli_connect($host, $username, $password, $db);
                $query = mysqli_query($conn, "SELECT showrequests FROM requestkeys WHERE thekey='".$key."' LIMIT 1");
                $row = mysqli_fetch_row($query);
                $showrequests = $row[0];

                mysqli_close($conn);

		?>

                <div id="goodpopup">
                    <div class="content">
                        THANKYOU
                        <span class="error">Your request submission was successful</span>
                    </div>
                </div>
                <div id="floodalert">
                    <div class="content">
                        WHOOPS
                        <span class="error">You may only make one request every <?php if($flood_period > 60) { echo (intval($flood_period / 60) . " minutes.  "); } else { echo $flood_period . " seconds. ";} ?> Please wait and re-submit a new request</span>
                    </div>
                </div>
                <form id="gridder_addform" method="post">
                    <input type="hidden" name="action" value="addnew" />

                    <div class="addnewrequest" id="addnew">
                        <div class="newadd" id="inputlabel">Your name</div>
                        <div class="newadd" id="inputbox"><input type="text" name="name" id="name" class="gridder_add" /> </div>
                        <div class="newadd" id="nameerror">ERROR<span class="error">The name field cannot be left blank</span></div>
                        <div class="newadd" id="nameerror_tl">ERROR<span class="error">The name you entered is too long. Please use less than 64 characters</span></div>
                        <div class="newadd" id="inputlabel">Song Artist</div>
                        <div class="newadd" id="inputbox"><input type="text" name="artist" id="artist" class="gridder_add" /> </div>
                        <div class="newadd" id="artisterror">ERROR<span class="error">The artist field cannot be left blank</span></div>
                        <div class="newadd" id="artisterror_tl">ERROR<span class="error">The artist you entered is too long. Please use less than 64 characters</span></div>
                        <div class="newadd" id="inputlabel">Song Title</div>
                        <div class="newadd" id="inputbox"><input type="text" name="title" id="title" class="gridder_add" /> </div>
                        <div class="newadd" id="titleerror">ERROR<span class="error">The title field cannot be left blank</span></div>
                        <div class="newadd" id="titleerror_tl">ERROR<span class="error"> The title you entered is too long. Please use less than 64 characters</span></div>
                        <div class="newadd" id="inputlabel">Your Message (up to 140 characters)</div>
                        <div class="newadd" id="inputbox"><textarea name="message" id="message" class="gridder_add"></textarea></div>
                        <div class="newadd" id="messageerror_tl">ERROR<span class="error">The message you entered is too long. Please use 140 characters or less</span></div>
                        <div class="newadd" id="submitbutton"><input type="submit" id="gridder_addrecord" value="submit" class="gridder_addrecord_button" title="Add" /></div>
                        <div class="newadds" id="cancelbutton">
                            <a href="cancel" id="gridder_cancel" class="gridder_cancel">Cancel</a>
                        </div>
                 </div>
                 </form>

        <div class="request_table">
            <?php
            if ($showrequests == 0) {
                if ($count == 0) {
                    echo '<h1>There have been no requests made yet';
                }
                if ($count == 1) {
                    echo '<h1>There has been one request made so far</h1>';
                }
                if ($count > 1) {echo '<h1>There have been ' .$count. " requests so far</h1>";}
                }
            else {
                      if($count <= 0) { echo '<h1>There are no requests yet</h1>';}
                      else {
                echo "<h1>Current Requests</h1>";
                $i = 0;
                foreach($record as $records) {
                $i = $i + 1;
                ?>
                <div class="<?php if($i%2 == 0) { echo 'even'; } else { echo 'odd'; } ?>">
                    <span class="list"><?php echo $records['name']; ?> : &lsquo;<?php echo $records['title']; ?>&rsquo; by <?php echo $records['artist']; ?></span>
                    <div class="message">Message: <?php echo $records['message']; ?></div>
                    <?php if ($records['played'] == 1) {echo '<span class="played">Played</span>'; } ?>
                </div>
                <?php
                    }
                }
             }
            ?>
        <?php
	break;

	case "addnew":
                $result="";
                $timedate = date("Y-m-d.H:i:s");
                $ip_addr = $_SERVER['REMOTE_ADDR'];
                $uniqueid = $_SESSION['uniqueid'];
                $conn = mysqli_connect($host, $username, $password, $db);
                $result = mysqli_query($conn, "SELECT timedate FROM requests WHERE uniqueid='".$uniqueid."' ORDER BY timedate DESC LIMIT 1");

                $rows = mysqli_num_rows($result);
                if ($rows == 1) {
                    $row = mysqli_fetch_row($result);
                    $lasttime = date($row[0]);

                    if (strtotime($lasttime) > (strtotime($timedate) - $flood_period)) {
                        $response['status'] = 'flood';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                    }
        }
                mysqli_close($conn);
                $result="";
                $conn = mysqli_connect($host, $username, $password, $db);
                $key = $_SESSION['key'];
		$name 		= isset($_POST['name']) ? mysqli_real_escape_string($conn, strip_tags($_POST['name'])) : '';
		$artist         = isset($_POST['artist']) ? mysqli_real_escape_string($conn, strip_tags($_POST['artist'])) : '';
		$title 		= isset($_POST['title']) ? mysqli_real_escape_string($conn, strip_tags($_POST['title'])) : '';
		$message 	= isset($_POST['message']) ? mysqli_real_escape_string($conn, strip_tags($_POST['message'])) : '';
                $name = strip_tags($_POST['name']);
                $artist = strip_tags($_POST['artist']);
                $title = strip_tags($_POST['title']);
                $message = strip_tags($_POST['message']);

                $result = mysqli_query($conn, "INSERT INTO `requests` (timedate, thekey, name, artist, title, message, ipaddr, uniqueid) VALUES ('".$timedate."', '".$key."', '".$name."', '".$artist."', '".$title."', '".$message."', '".$ip_addr."', '".$uniqueid."')");
                mysqli_close($conn);

                $response['status'] = 'success';
                header('Content-type: application/json');
                echo json_encode($response);
	break;

	case "update":
	break;

	case "delete":
	break;
}
?>
