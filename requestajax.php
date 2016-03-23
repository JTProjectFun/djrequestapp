<?php
include_once 'configuration.php';
include_once 'functions/functions.php';
start_session();

$result=""; // Initialise variables which may have been previously used & would contain data already
$fetch=""; // Initialise variables which may have been previously used & would contain data already
$record=""; // Initialise variables which may have been previously used & would contain data already

$action = $_REQUEST['action'];
// don't trust cookies
$key = makeSafe($_COOKIE['eventkey']);
$uniqueid = makeSafe($_COOKIE['requestuser']);
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

				if (!empty($djName)) {
					$introduceDJ = '<div class="row">';
					$introduceDJ .= '	<div class="col-sm-8 col-md-8 col-sm-offset-2 col-md-offset-2">';
					$introduceDJ .= '		<div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Your DJ ';
					if (date("H") < 12) {
						$introduceDJ .= 'this morning';
					} else if (date("H") > 11 && date("H") < 18) {
						$introduceDJ .= 'this afternoon';
					} else if (date("H") > 17) {
						$introduceDJ .= 'tonight'; 
					}
					$introduceDJ .= ' is: ' . $djName . '</div>';
					$introduceDJ .= '	</div>';
					$introduceDJ .= '</div>';

					// replace the div - easier to do this than re-order everything!
					echo '  <script type="text/javascript">' . PHP_EOL;
					echo '    $(\'#introduceDJ\').html(\'' . $introduceDJ . '\');' . PHP_EOL;
					echo '  </script>' . PHP_EOL;
				}
				?>

				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-success collapse" role="alert" id="goodpopup">
								<p>THANK-YOU! <?php echo $customtext['success']; ?></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger collapse" role="alert" id="databaseerror">
								<p>ERROR: <?php echo $customtext['sqlerror']; ?></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger collapse" role="alert" id="toomanyuser">
								<p>ERROR: <?php echo $customtext['toomanyuser']; ?></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger collapse" role="alert" id="toomany">
								<p>ERROR: <?php echo $customtext['toomany']; ?></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger collapse" role="alert" id="banned">
								<p>ERROR: <?php echo $customtext['banned']; ?></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger collapse" role="alert" id="floodalert">
								<p>ERROR: You may only make one request every <?php if($flood_period > 60) { echo (intval($flood_period / 60) . " minutes.  "); } else { echo $flood_period . " seconds. ";} ?> Please wait and re-submit a new request.</p>
						</div>
					</div>
				</div>

				<form method="post" id="gridder_addform" role="form">				
					<input type="hidden" name="action" value="addnew" />
					<div class="addnewrequest" id="addnew">
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label class="control-label" for="name">Your name:</label>
									<input id="name" name="name" type="text" placeholder="" class="form-control gridder_add" maxlength="64">
									<div class="error collapse" id="nameerror">This field is required.</div>
									<div class="error collapse" id="nameerror_tl"><?php echo str_replace("%FIELD%", "name", $customtext['fieldtoolong']); ?></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label class="control-label" for="artist">Song Artist:</label>
									<input id="artist" name="artist" type="text" placeholder="" class="form-control gridder_add" maxlength="64">
									<div class="error collapse" id="artisterror">This field is required.</div>
									<div class="error collapse" id="artisterror_tl"><?php echo str_replace("%FIELD%", "artist", $customtext['fieldtoolong']); ?></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label class="control-label" for="title">Song Title:</label>
									<input id="title" name="title" type="text" placeholder="" class="form-control gridder_add" maxlength="64">
									<div class="error collapse" id="titleerror">This field is required.</div>
									<div class="error collapse" id="titleerror_tl"><?php echo str_replace("%FIELD%", "title", $customtext['fieldtoolong']); ?></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label class="control-label" for="message">Your Message (up to 140 characters):</label>
									<textarea id="message" name="message" class="form-control" placeholder="" rows="4" maxlength="140"></textarea>
									<div class="error collapse" id="messageerror_tl"><?php echo str_replace("%FIELD%", "message", $customtext['fieldtoolong']); ?></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<div class="newadd" id="submitbutton"><input type="submit" id="gridder_addrecord" value="Submit Request" class="btn btn-primary gridder_addrecord_button" title="Add" /></div>
								</div>
							</div>
						</div>
					</div>
				</form>

            <?php
	break;

	case "populateRequests":
			// this is just a duplication of the above, maybe some could be common or removed?
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

			$requestContent = '<div id="requests-placeholder">';
			if($count <= 0) {
				$requestContent .= '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> There have been no requests made yet.</div>' . PHP_EOL;
			}
            if ($showrequests == 0) {
                if ($count == 1) {
                    $requestContent .= '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> There has been one request made so far.</div>' . PHP_EOL;
                }
                if ($count > 1) {
					$requestContent .=  '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> There have been ' .$count. ' requests made so far.</div>' . PHP_EOL;
				}
			} elseif($count > 0) {
				$i = 0;
				foreach($record as $records) {
					$i = $i + 1;
					$requestContent .= '<div class="well">' . PHP_EOL;
					$requestContent .= $records['name'] . ': &lsquo;' . $records['title'] . '&rsquo; <em>by</em> ' . $records['artist'] . PHP_EOL;
					if ((strlen($records['message']) > 0) && ($showMessages == "1")) {
						$requestContent .= '<br/>Message: ' . $records['message'] . PHP_EOL;
					}
					if ($records['played'] == 1) {
						$requestContent .= '<span class="label label-pill label-success pull-right">Played</span>' . PHP_EOL;
					}
					$requestContent .= '</div>' . PHP_EOL;
				}
			}
			$requestContent .= '</div>';
			// update badge count
			echo '<script type="text/javascript">' . PHP_EOL;
			echo '	$(\'#requests-badge\').text(\'' . $count . '\')' . PHP_EOL;
			echo '</script>' . PHP_EOL;
			// output requests
			echo $requestContent;
	break;

	case "addnew":
                $row="";
                $timedate = date("Y-m-d.H:i:s");
                $ip_addr = $_SERVER['REMOTE_ADDR'];
				// don't trust cookies
				$uniqueid = makeSafe($_COOKIE['requestuser']);
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
                // don't trust cookies
				$key = makeSafe($_COOKIE['eventkey']);
                if (strlen($key) < 3){   // If eventkey is blank, kick the user out & make em log back in.
					header('Location:logout.php');
					break;
                }
                $conn = mysqli_connect($host, $username, $password, $db);
				$name = isset($_POST['name']) ? mysqli_real_escape_string($conn, strip_tags($_POST['name'])) : '';
				$artist = isset($_POST['artist']) ? mysqli_real_escape_string($conn, strip_tags($_POST['artist'])) : '';
				$title = isset($_POST['title']) ? mysqli_real_escape_string($conn, strip_tags($_POST['title'])) : '';
				$message = isset($_POST['message']) ? mysqli_real_escape_string($conn, strip_tags($_POST['message'])) : '';
                $name = strip_tags($_POST['name']);
                $artist = strip_tags($_POST['artist']);
                $title = strip_tags($_POST['title']);
                $message = strip_tags($_POST['message']);

                if (strlen($name) < 1) {
                    $response['status'] = 'nametooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                if (strlen($artist) < 1) {
                    $response['status'] = 'artisttooshort';
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }

                if (strlen($title) < 1) {
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
