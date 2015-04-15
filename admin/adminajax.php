<?php
session_start();
include_once '../configuration.php';
include_once '../functions/functions.php';
include_once ('generatekey.php');

$action = $_REQUEST['action'];
$record="";
$id = $_SESSION['login_user'];
switch($action) {
	case "load":
                $tempkey = random_string();
                // Generate a new key. If it's already in the database, keep trying.
                while (checkkey($tempkey)) {
                    $tempkey = random_string();
                }
                $conn = mysqli_connect($host, $username, $password, $db);
                $query = mysqli_query($conn, "SELECT requestkeys.*,systemUser.username FROM requestkeys LEFT JOIN systemUser ON requestkeys.userid = systemUser.id");
		$count  = mysqli_num_rows($query);
		if($count > 0) {
			while($fetch = mysqli_fetch_array($query)) {
				$record[] = $fetch;
			}
		}
		?>

                    <form id="gridder_addform" method="post">
                    <input type="hidden" name="action" value="addnew" />

                    <div class="addnewkey" id="addnew">
                        <div class="keyadd">Event Key<input type="text" name="thekey" id="thekey" class="gridder_add" value="<?php echo $tempkey; ?>"/> </div>
                        <div class="keyadd" id="inputlabel">Date</div>
                        <div class="keyadd" id="inputbox"><input type="text" name="date" id="date" class="datepiker" /> </div>
                        <div class="keyadd" id="inputlabel">Show Requests</div>
                        <div class="keyadd" id="inputbox"><input type="checkbox" name="showrequests" id="showrequests" class="gridder_add" /></div>
                        <div class="keyadd" id="inputlabel">Will Expire</div>
                        <div class="keyadd" id="inputbox">
                            <input type="checkbox" name="willexpire" id="willexpire" class="gridder_add" /></div>
                        <div class="keyadd" id="inputlabel">Max User Requests</div>
                        <div class="keyadd" id="inputbox">
                            <input type="text" class="gridder_add" name="maxUserRequests" value="<?php echo $maxUserRequestDefault; ?>" />
                        <div class="keyadd" id="inputlabel">Max Requests</div>
                        <div class="keyadd" id="inputbox">
                                 <input type="text" class="gridder_add" name="maxRequests" value="<?php echo $maxRequestDefault; ?>" />
                        </div>

                        <div class="newadd" id="submitbutton"><input type="submit" id="gridder_addrecord" value="submit" class="gridder_addrecord_button" $
                        <div class="newadds" id="cancelbutton">
                            <a href="cancel" id="gridder_cancel" class="gridder_cancel">Cancel</a>
                        </div>
                 </div>
                 </form>
</div>
        <table class="as_gridder_table">
            <tr class="grid_header">
                <td class="id"><div class="grid_heading">id</div></td>
                <td class="date"><div class="grid_heading">Date Added</div></td>
                <td><div class="grid_heading">Key</div></td>
                <td><div class="grid_heading">Created by</div></td>
                <td class="date"><div class="grid_heading">Active Date</div></td>
                <td><div class="grid_heading">Show Requests</div></td>
                <td><div class="grid_heading">Will Expire</div></td>
                <td><div class="grid_heading">Max Requests per user</div></td>
                <td><div class="grid_heading">Max Requests</div></td>
                <td><div class="grid_heading">Manage</div></td>
                <td><div class="grid_heading">Print</div></td>
                <td><div class="grid_heading">Delete</div></td>
            </tr>
            <?php
            if($count <= 0) {
            ?>
            <tr id="norecords">
                <td colspan="7" align="center">No records found <a href="addnew" id="gridder_insert" class="gridder_insert"><img src="../images/insert.png" alt="Add New" title="Add New" /></a></td>
            </tr>
            <?php } else {
            $i = 0;
            foreach($record as $records) {
            $i = $i + 1;
            ?>
            <tr class="<?php if($i%2 == 0) { echo 'even'; } else { echo 'odd'; } ?>">
                <td class="id"><div class="grid_content sno"><span><?php echo $records['id']; ?></span></div></td>
                <td class="date"><div class="grid_content sno"><span><?php echo $records['timedate']; ?></span></div></td>
                <td class="key"><div class="grid_content editable"><span><?php echo $records['thekey']; ?></span><input type="text" class="gridder_input" name="<?php echo encrypt("thekey|".$records['id']); ?>" value="<?php echo $records['thekey']; ?>" /></div></td>
                <td class="key"><div class="grid_content sno"><span><?php echo $records['username']; ?></span></div></td>
                <td class="date"><div class="grid_content editable"><span><?php echo $records['date']; ?></span>
                    <input type="text" class="gridder_input datepiker" name="<?php echo encrypt("date|".$records['id']); ?>" value="<?php echo $records['date']; ?>" /></div></td>
                <td class="showreq">
                    <div class="gridder_content">
                        <span></span>
                        <input type="checkbox" class="toggle" name="
                        <?php echo encrypt("showrequests|".$records['id']); ?>"
                        <?php if ($records['showrequests'] == 1) { echo ' checked '; } ?> />
                    </div>
                </td>
                <td class="expire">
                    <div class="gridder_content">
                        <span></span>
                        <input type="checkbox" class="toggle" name="
                        <?php echo encrypt("willexpire|".$records['id']); ?>"
                        <?php if ($records['willexpire'] == 1) { echo ' checked '; } ?> />
                    </div>
                </td>
                <td class="maxrequests">
                    <div class="grid_content editable">
                        <span><?php echo $records['maxUserRequests']; ?></span>
                        <input type="text" class="gridder_input" name="<?php echo encrypt("maxUserRequests|".$records['id']); ?>" value="<?php echo $records['maxUserRequests']; ?>" />
                    </div>
                </td>
                <td class="maxrequests">
                    <div class="grid_content editable">
                        <span><?php echo $records['maxRequests']; ?></span>
                        <input type="text" class="gridder_input" name="<?php echo encrypt("maxRequests|".$records['id']); ?>" value="<?php echo $records['maxRequests']; ?>" />
                    </div>
                </td>
                <td class="manage">
                    <div class="gridder_content">
                        <a href="requests.php?key=<?php echo $records['id'] ;?>">manage</a>
                    </div>
                </td>
                <td class="print">
                    <a href="print.php?key=<?php echo $records['thekey'] ; ?>">Print</a>
                </td>
                <td class="del">
                    <a href="<?php echo encrypt($records['id']); ?>" class="gridder_delete"><img src="../images/delete.png" alt="Delete" title="Delete" /></a>
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
		$thekey 		= isset($_POST['thekey']) ? mysqli_real_escape_string($conn, $_POST['thekey']) : '';
		$date 		= isset($_POST['date']) ? mysqli_real_escape_string($conn, $_POST['date']) : '';
		$showrequests = isset($_POST['showrequests']) ? mysqli_real_escape_string($conn, $_POST['showrequests']) : '';
		$willexpire		= isset($_POST['willexpire']) ? mysqli_real_escape_string($conn, $_POST['willexpire']) : '';
		$maxuserrequests		= isset($_POST['maxUserRequests']) ? mysqli_real_escape_string($conn, $_POST['maxUserRequests']) : '';
		$maxrequests		= isset($_POST['maxRequests']) ? mysqli_real_escape_string($conn, $_POST['maxRequests']) : '';
                if ($showrequests == "on") { $showrequests = "1"; } else { $showrequests = "0"; }
                if ($willexpire == "on") { $willexpire = "1"; } else { $willexpire = "0"; }
                $useridq = mysqli_query($conn, "SELECT id FROM `systemUser` WHERE username='$id'");
                $userids = mysqli_fetch_row($useridq);
                $userid = $userids[0];
		mysqli_query($conn, "INSERT INTO `requestkeys` (timedate, thekey, date, showrequests, willexpire,userid,maxUserRequests,maxRequests) VALUES ('$timedate', '$thekey', '$date', '$showrequests', '$willexpire', '$userid','$maxuserrequests','$maxrequests')");
                mysqli_close($conn);
	break;

	case "update":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$crypto = decrypt($_POST['crypto']);
		$explode = explode('|', $crypto);
		$columnName = $explode[0];
		$rowId = $explode[1];
                if($columnName == 'thekey') { // if updating the 'thekey' field also update the requests table
                     $query = mysqli_query($conn, "SELECT thekey FROM `requestkeys` where id = '$rowId' ");
                     $data = mysqli_fetch_row($query);
                     $thekey = $data[0];
                     $query = mysqli_query($conn, "UPDATE `requests` SET thekey = '$value' WHERE thekey = '$thekey' ");
                     $query = mysqli_query($conn, "UPDATE `requestusers` SET thekey = '$value' WHERE thekey = '$thekey' ");
                }
		$query = mysqli_query($conn, "UPDATE `requestkeys` SET `$columnName` = '$value' WHERE id = '$rowId' ");
                mysqli_close($conn);
	break;

	case "delete":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= decrypt($_POST['value']);
                $query = mysqli_query($conn, "SELECT thekey FROM `requestkeys` WHERE id = '$value' limit 1");
                $data = mysqli_fetch_row($query);
                $delkey = $data[0];
		$query = mysqli_query($conn, "DELETE FROM `requestkeys` WHERE id = '$value' ");
		$query = mysqli_query($conn, "DELETE FROM `requests` WHERE thekey = '$delkey' ");
                mysqli_close($conn);
	break;
}
?>
