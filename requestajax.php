<?php
include_once 'configuration.php';
include_once 'functions/functions.php';
start_session();

$result=""; // Initialise variables which may have been previously used & would contain data already
$fetch=""; // Initialise variables which may have been previously used & would contain data already
$record=""; // Initialise variables which may have been previously used & would contain data already

$action = $_REQUEST['action'];
//$key = $_COOKIE['requestkey'];
$key = $_COOKIE['eventkey'];
$uniqueid = $_COOKIE['requestuser'];
$error = '';
switch($action) {
	case "load":
                $conn = mysqli_connect($host, $username, $password, $db);
                $mess = mysqli_query($conn, "SELECT showMessages FROM requestkeys WHERE thekey='".$key."'");
                $showMes = mysqli_fetch_row($mess);
                $showMessages = $showMes[0];
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

                $conn = mysqli_connect($host, $username, $password, $db);
                $namequery = mysqli_query($conn, "SELECT systemUser.name FROM systemUser LEFT JOIN requestkeys ON systemUser.id=requestkeys.userid WHERE requestkeys.thekey='$key'");
                $row = mysqli_fetch_row($namequery);
                $djName = $row[0];

                $conn = mysqli_connect($host, $username, $password, $db);
                $customquery = mysqli_query($conn, "SELECT customtext.* FROM customtext LEFT JOIN systemUser ON systemUser.id=customtext.userid WHERE systemUser.name='$djName'");
                $customtext = mysqli_fetch_array($customquery, MYSQLI_ASSOC);
                mysqli_close($conn);

		?>
                <?php if (!empty($djName)) { echo "<div><span>Your DJ "; 
                    if (date("H") < 12) { echo "this morning"; }
                    else if (date("H") > 11 && date("H") < 18) { echo "this afternoon"; }
                    else if (date("H") > 17) { echo "tonight"; }
                    echo " is ". $djName . "</span></div>"; } ?>
                <div id="goodpopup">
                    <div class="content">
                        THANKYOU
                        <span class="error"><?php echo $customtext['success']; ?></span>
                    </div>
                </div>
                <div id="databaseerror">
                    <div class="errorcontent">
                        OH DEAR
                        <span><?php echo $customtext['sqlerror']; ?></span>
                    </div>
                </div>
                <div id="nametooshort">
                    <div class="errorcontent">
                        WHOOPS
                        <span><?php echo str_replace("%FIELD%", "name", $customtext['tooshort']); ?></span>
                    </div>
                </div>
                <div id="titletooshort">
                    <div class="errorcontent">
                        WHOOPS
                        <span><?php echo str_replace("%FIELD%", "title", $customtext['tooshort']); ?></span>
                    </div>
                </div>
                <div id="artisttooshort">
                    <div class="errorcontent">
                        WHOOPS
                        <span><?php echo str_replace("%FIELD%", "artist", $customtext['tooshort']); ?></span>
                    </div>
                </div>
                <div id="titletooshort">
                    <div class="errorcontent">
                        WHOOPS
                        <span><?php echo str_replace("%FIELD%", "title", $customtext['tooshort']); ?></span>
                    </div>
                </div>
                <div id="toomanyuser">
                    <div class="errorcontent">
                        WHOOPS
                        <span><?php echo $customtext['toomanyuser']; ?></span>
                    </div>
                </div>
                <div id="toomany">
                    <div class="errorcontent">
                        WHOOPS
                        <span><?php echo $customtext['toomany']; ?></span>
                    </div>
                </div>
                <div id="banned">
                    <div class="errorcontent">
                        WHOOPS
                        <span><?php echo $customtext['banned']; ?></span>
                    </div>
                </div>
                <div id="floodalert">
                    <div class="errorcontent">
                        WHOOPS
                        <span class="error">You may only make one request every <?php if($flood_period > 60) { echo (intval($flood_period / 60) . " minutes.  "); } else { echo $flood_period . " seconds. ";} ?> Please wait and re-submit a new request</span>
                    </div>
                </div>
                <form id="gridder_addform" method="post">
                    <input type="hidden" name="action" value="addnew" />

                    <div class="addnewrequest" id="addnew">
                        <div class="newadd" id="inputlabel">Your name</div>
                        <div class="newadd" id="inputbox"><input type="text" name="name" id="name" class="gridder_add" maxlength="64"/> </div>
                        <div class="newadd" id="nameerror">ERROR<span class="error"><?php echo str_replace("%FIELD%", "name", $customtext['tooshort']); ?></span></div>
                        <div class="newadd" id="nameerror_tl">ERROR<span class="error"><?php echo str_replace("%FIELD%", "name", $customtext['toolong']); ?></span></div>
                        <div class="newadd" id="inputlabel">Song Artist</div>
                        <div class="newadd" id="inputbox"><input type="text" name="artist" id="artist" class="gridder_add" maxlength="64"/> </div>
                        <div class="newadd" id="artisterror">ERROR<span class="error"><?php echo str_replace("%FIELD%", "artist", $customtext['tooshort']); ?></span></div>
                        <div class="newadd" id="artisterror_tl">ERROR<span class="error"><?php echo str_replace("%FIELD%", "artist", $customtext['toolong']); ?></span></div>
                        <div class="newadd" id="inputlabel">Song Title</div>
                        <div class="newadd" id="inputbox"><input type="text" name="title" id="title" class="gridder_add" maxlength="64"/> </div>
                        <div class="newadd" id="titleerror">ERROR<span class="error"><?php echo str_replace("%FIELD%", "title", $customtext['tooshort']); ?></span></div>
                        <div class="newadd" id="titleerror_tl">ERROR<span class="error"><?php echo str_replace("%FIELD%", "title", $customtext['toolong']); ?></span></div>
                        <div class="newadd" id="inputlabel">Your Message (up to 140 characters)</div>
                        <div class="newadd" id="inputbox"><textarea name="message" id="message" class="gridder_add" maxlength="140"></textarea></div>
                        <div class="newadd" id="messageerror_tl">ERROR<span class="error"><?php echo str_replace("%FIELD%", "message", $customtext['toolong']); ?></span></div>
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
                    echo '<h1>There have been no requests made yet</h1>';
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
                    <?php if ((strlen($records['message']) > 1) && ($showMessages == "1")) { echo '<div class="message">Message: ' . $records['message'] . "</div>"; } ?>
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
                $row="";
                $timedate = date("Y-m-d.H:i:s");
                $ip_addr = $_SERVER['REMOTE_ADDR'];
                $uniqueid = $_COOKIE['requestuser'];
                $conn = mysqli_connect($host, $username, $password, $db);
                $bannedquery = mysqli_query($conn, "SELECT banned FROM requestusers WHERE uniqueid='$uniqueid'");
                $banned = mysqli_fetch_row($bannedquery);
                if ( $banned[0] == "1" ) {
                        $response['status'] = 'banned';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                }

                $numquery = mysqli_query($conn, "SELECT numRequests FROM requestusers WHERE uniqueid='$uniqueid'");
                $maxquery = mysqli_query($conn, "SELECT maxUserRequests, maxRequests FROM requestkeys WHERE thekey='$key'");
                $totalquery = mysqli_query($conn, "SELECT COUNT(*) FROM requests WHERE thekey='$key'");
                $maxRequests = mysqli_fetch_row($maxquery);
                $totalRequest = mysqli_fetch_row($totalquery);
                $totalRequests = $totalRequest[0];
                $maxUserRequests = $maxRequests[0];
                $maxRequests = $maxRequests[1];
                $userRequest = mysqli_fetch_row($numquery);
                $userRequests = $userRequest[0];

                if (($userRequests > $maxUserRequests) && ($maxUserRequests != 0)) {
                        $response['status'] = 'toomanyuser';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                }

                if (($totalRequests > $maxRequests) && ($maxRequests != 0)) {
                        $response['status'] = 'toomany';
                        header('Content-type: application/json');
                        echo json_encode($response);
                        mysqli_close($conn);
                        break;
                }


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
                //$key = $_COOKIE['requestkey'];
                $key = $_COOKIE['eventkey'];
                if (strlen($key) < 3){   // If eventkey is blank, kick the user out & make em log back in.
                        header('Location:logout.php');
                        break;
                }
                $conn = mysqli_connect($host, $username, $password, $db);
		$name 		= isset($_POST['name']) ? mysqli_real_escape_string($conn, strip_tags($_POST['name'])) : '';
		$artist         = isset($_POST['artist']) ? mysqli_real_escape_string($conn, strip_tags($_POST['artist'])) : '';
		$title 		= isset($_POST['title']) ? mysqli_real_escape_string($conn, strip_tags($_POST['title'])) : '';
		$message 	= isset($_POST['message']) ? mysqli_real_escape_string($conn, strip_tags($_POST['message'])) : '';
                $name = strip_tags($_POST['name']);
                $artist = strip_tags($_POST['artist']);
                $title = strip_tags($_POST['title']);
                $message = strip_tags($_POST['message']);

                if (strlen($name) < 2) {
                    $response['status'] = 'nametooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                if (strlen($artist) < 2) {
                    $response['status'] = 'artisttooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                if (strlen($title) < 2) {
                    $response['status'] = 'titletooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                $result = mysqli_query($conn, "INSERT INTO `requests` (timedate, thekey, name, artist, title, message, ipaddr, uniqueid) VALUES ('".$timedate."', '".$key."', '".$name."', '".$artist."', '".$title."', '".$message."', '".$ip_addr."', '".$uniqueid."')");
                $result = mysqli_query($conn, "UPDATE requestusers set numRequests=numRequests+1 WHERE uniqueid='$uniqueid'");
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
