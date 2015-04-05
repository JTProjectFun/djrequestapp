<?php
include '../configuration.php';
include '../functions/functions.php';
include('generatekey.php');
session_start();
$record="";
$action = $_REQUEST['action'];

$key = $_SESSION['key'];
switch($action) {
	case "load":
                $rq = mysqli_connect($host, $username, $password, $db);
                if ($key == "0") {
                      $query = mysqli_query($rq, "SELECT * FROM requests ORDER BY id ASC");
                }
                else {
                      $query = mysqli_query($rq, "SELECT requests.* FROM requests LEFT JOIN requestkeys ON requests.thekey=requestkeys.thekey WHERE requestkeys.id='".$key."' ORDER BY id ASC");
                }
		$count  = mysqli_num_rows($query);
		if($count > 0) {
			while($fetch = mysqli_fetch_array($query)) {
				$record[] = $fetch;
			}
		}
		?>

                <form id="gridder_addform" method="post">
                    <input type="hidden" name="action" value="addnew" />

                    <table class="addnewrequest" id="addnew">
                        <tr class="newadd">
                            <td>Key</td>
                            <td class="tbname"><input type="text" name="key" id="key" class="gridder_add" value="<?php echo $key; ?>"/></td>
                        </tr>
                        <tr class="newadd">
                            <td>Your Name</td>
                            <td class="tbname"><input type="text" name="name" id="name" class="gridder_add" /></td>
                        </tr>
                        <tr class="newadd">
                            <td>Song Artist</td>
                            <td class="tbartist"><input type="text" name="artist" id="artist" class="gridder_add" /></td>
                        </tr>
                        <tr class="newadd">
                            <td>Song Title</td>
                            <td class="tbtitle"><input type="text" name="title" id="title" class="gridder_add" /></td>
                        </tr>
                        <tr class="newadd">
                            <td>Your message (up to 140 characters)</td>
                            <td class="message"><textarea name="message" id="message" class="gridder_add"></textarea></td>
                        </tr>
                        <tr class="newadd">
                            <td colspan="2" class="tbadd">&nbsp;
                                <input type="submit" id="gridder_addrecord" value="Submit" class="gridder_addrecord_button" title="Add" />
                                <a href="cancel" id="gridder_cancel" class="gridder_cancel">CANCEL</a></td>
                        </tr>
                     </table>
                 </form>

        <table class="as_gridder_table">
            <tr>
                <th class="id"><div class="grid_heading">id</div></th>
                <th class="date"><div class="grid_heading">Date &amp; Time Added</div></th>
                <th class="key"><div class="grid_heading">Key</div></th>
                <th class="date"><div class="grid_heading">UserString</div></th>
                <th class="date"><div class="grid_heading">IP Address</div></th>
                <th class="date"><div class="grid_heading">Name</div></th>
                <th class="date"><div class="grid_heading">Artist</div></th>
                <th class="date"><div class="grid_heading">Title</div></th>
                <th class="message"><div class="grid_heading">Message</div></th>
                <th class="willplay"><div class="grid_heading">WillPlay</div></th>
                <th class="willplay"><div class="grid_heading">Played</div></th>
                <th class="del"><div class="grid_heading">Delete</div></th>
                <th class="del"><div class="grid_heading">Ban User</div></th>
                <th class="del"><div class="grid_heading">Delete User's Requests</div></th>
            </tr>

            <?php
            if($count <= 0) {
            ?>
            <tr id="norecords">
                <td colspan="11" align="center">No records found <a href="addnew" id="gridder_insert" class="gridder_insert"><img src="../images/insert.png" alt="Add New" title="Add New" /></a></td>
            </tr>
            <?php } else {
            $i = 0;
            foreach($record as $records) {
            $i = $i + 1;
            ?>
            <tr class="<?php if($i%2 == 0) { echo 'even'; } else { echo 'odd'; } ?>">
                <td class="id">
                    <div class="grid_content sno">
                        <span><?php echo $records['id']; ?></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><?php echo $records['timedate']; ?></span>
                    </div>
                </td>
                <td class="key">
                    <div class="grid_content sno">
                        <span><?php echo $records['thekey']; ?></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><a href="user.php?user=<?php echo $records['uniqueid']; ?>"> <?php echo $records['uniqueid']; ?></a></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><?php echo $records['ipaddr']; ?></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content editable">
                        <span><?php echo $records['name']; ?></span>
                        <input type="text" class="gridder_input" name="
                        <?php echo encrypt("name|".$records['id']); ?>" value="<?php echo $records['name']; ?>" />
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content editable">
                        <span><?php echo $records['artist']; ?></span>
                        <input type="text" class="gridder_input" name="
                        <?php echo encrypt("artist|".$records['id']); ?>" value="<?php echo $records['artist']; ?>" />
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content editable">
                        <span><?php echo $records['title']; ?></span>
                        <input type="text" class="gridder_input" name="
                        <?php echo encrypt("title|".$records['id']); ?>" value="<?php echo $records['title']; ?>" />
                    </div>
                </td>
                <td class="message">
                    <div class="grid_content editable">
                        <span><?php echo $records['message']; ?></span>
                        <input type="text" class="gridder_input" name="
                        <?php echo encrypt("message|".$records['id']); ?>" value="<?php echo $records['message']; ?>" />
                    </div>
                </td>
                <td class="willplay">
                    <div class="gridder_content">
                        <span></span>
                        <input type="checkbox" class="toggle" name="<?php echo encrypt("willplay|".$records['id']); ?>"
                        <?php if ($records['willplay'] == 1) { echo ' checked '; } ?> />
                    </div>
                </td>
                <td class="willplay">
                    <div class="gridder_content">
                        <span></span>
                        <input type="checkbox" class="toggle" name="
                        <?php echo encrypt("played|".$records['id']); ?>"
                        <?php if ($records['played'] == 1) { echo ' checked '; } ?> />
                    </div>
                </td>
                <td>
                    <a href="<?php echo encrypt($records['id']); ?>" class="gridder_delete">
                        <img src="../images/delete.png" alt="Delete" title="Delete" />
                    </a>
                </td>
                <td>
                    <a href="<?php echo encrypt($records['uniqueid']); ?>" class="gridder_ban">
                        <img src="../images/delete.png" alt="BAN" title="Ban User" />
                    </a>
                </td>
                <td>
                    <a href="<?php echo encrypt($records['uniqueid']); ?>" class="gridder_deleteuserreq">
                        <img src="../images/delete.png" alt="Delete ALL" title="Delete" />
                    </a>
                </td>
            </tr>
            <?php
                }
            }
            ?>
            </table>
        <?php
	break;
	
	case "addnew":
                $conn = mysqli_connect($host,$username,$password,$db);
                $timedate = date("Y-m-d.H:i:s");
                $ip_addr = $_SERVER['REMOTE_ADDR'];
                $key = isset($_POST['key']) ? mysqli_real_escape_string($rq, $_POST['key']) : '';
		$name 		= isset($_POST['name']) ? mysqli_real_escape_string($rq, $_POST['name']) : '';
		$artist         = isset($_POST['artist']) ? mysqli_real_escape_string($rq, $_POST['artist']) : '';
		$title 		= isset($_POST['title']) ? mysqli_real_escape_string($rq, $_POST['title']) : '';
		$message 	= isset($_POST['message']) ? mysqli_real_escape_string($rq, $_POST['message']) : '';
		mysqli_query($conn, "INSERT INTO `requests` (timedate, thekey, name, artist, title, message, ipaddr) VALUES ('$timedate', '$key', '$name', '$artist', '$title', '$message', '$ip_addr')");
                mysqli_close($conn);
	break;

	case "update":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$crypto = decrypt($_POST['crypto']);
		$explode = explode('|', $crypto);
		$columnName = $explode[0];
		$rowId = $explode[1];
		if($columnName == 'posted_date') { // Check the column is 'date', if yes convert it to date format
			$datevalue = $value;
			$value 	   = date('Y-m-d', strtotime($datevalue));
		}
		$query = mysqli_query($conn, "UPDATE `requests` SET `$columnName` = '$value' WHERE id = '$rowId' ");
                mysqli_close($conn);

	break;

        case "toggle":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$crypto = decrypt($_POST['crypto']);
		$explode = explode('|', $crypto);
		$columnName = $explode[0];
		$rowId = $explode[1];
                if ($value == "on") { $value = 1; } else { $value = 0; }
		$query = mysqli_query($conn, "UPDATE `requests` SET `$columnName` = '$value' WHERE id = '$rowId' ");
                mysqli_close($conn);

        break;

	case "delete":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= decrypt($_POST['value']);
		$query = mysqli_query($conn, "DELETE FROM `requests` WHERE id = '$value' ");
                mysqli_close($conn);
	break;

	case "ban":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= decrypt($_POST['value']);
		$query = mysqli_query($conn, "UPDATE `requestusers` SET banned=1 WHERE uniqueid = '$value' ");
                mysqli_close($conn);
	break;

	case "deleteuserreq":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= decrypt($_POST['value']);
		$query = mysqli_query($conn, "DELETE FROM `requests` WHERE unquieid = '$value' ");
                mysqli_close($conn);
	break;
}
?>
