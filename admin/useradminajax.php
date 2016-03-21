<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
include_once 'adminconfig.php';
include_once ('generatekey.php');
start_session();

$record="";
$action = $_REQUEST['action'];

$key = $_SESSION['key'];
switch($action) {
	case "load":
                $rq = mysqli_connect($host, $username, $password, $db);
                      $query = mysqli_query($rq, "SELECT * FROM systemUser ORDER BY id ASC");
		$count  = mysqli_num_rows($query);
		if($count > 0) {
			while($fetch = mysqli_fetch_array($query)) {
				$record[] = $fetch;
			}
		}
                mysqli_close($rq);
		?>
                <form id="gridder_addform" method="post">
                    <input type="hidden" name="action" value="addnew" />
                    <table class="addnewrequest" id="addnew">
                        <tr class="newadd">
                            <td>Username</td>
                            <td class="tbname"><input type="text" name="username" id="username" class="gridder_add" /></td>
                        </tr>
                        <tr class="newadd">
                            <td>Password</td>
                            <td class="tbname"><input type="text" name="password" id="password" class="gridder_add" /></td>
                        </tr>
                        <tr class="newadd">
                            <td>RealName</td>
                            <td class="tbartist"><input type="text" name="realname" id="realname" class="gridder_add" /></td>
                        </tr>
                        <tr class="newadd">
                            <td>Email Address</td>
                            <td class="tbtitle"><input type="text" name="email" id="email" class="gridder_add" /></td>
                        </tr>
                        <tr class="newadd">
                            <td>User Level</td>
                            <td class="message">
                                <select name="userlevel" class="gridder_add">
                                    <option value="1">Normal User</option>
                                    <option value="2">DJ Admin</option>
                                    <option value="3">Superuser</option>
                                </select>
                            </td>
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
                <th class="key"><div class="grid_heading">Username</div></th>
                <th class="date"><div class="grid_heading">RealName</div></th>
                <th class="date"><div class="grid_heading">Email</div></th>
                <th class="del"><div class="grid_heading">User Level</div></th>
                <th class="del"><div class="grid_heading">Enabled</div></th>
                <th class="del"><div class="grid_heading">Reset Password</div></th>
                <th class="del"><div class="grid_heading">Delete</div></th>
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
                <td>
                    <div class="grid_content sno">
                        <span><?php echo $records['timedate']; ?></span>
                    </div>
                </td>
                <td class="key">
                    <div class="grid_content editable">
                        <span><?php echo $records['username']; ?></span>
                        <input type="text" class="gridder_input" name="
                        <?php echo encrypt("username|".$records['id']); ?>" value="<?php echo $records['username']; ?>" />
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content editable">
                        <span><?php echo $records['realname']; ?></span>
                        <input type="text" class="gridder_input" name="
                        <?php echo encrypt("realname|".$records['id']); ?>" value="<?php echo $records['realname']; ?>" />
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content editable">
                        <span><?php echo $records['email']; ?></span>
                        <input type="text" class="gridder_input" name="
                        <?php echo encrypt("email|".$records['id']); ?>" value="<?php echo $records['email']; ?>" />
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content editable">
                        <span><?php echo $records['userlevel']; ?></span>
                        <input type="text" class="gridder_input" name="
                        <?php echo encrypt("userlevel|".$records['id']); ?>" value="<?php echo $records['userlevel']; ?>" />
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content editable">
                        <span></span>
                        <!-- Only show checkbox if userid isn't self to stop admin disabling their own account -->
                        <?php if ($userid != $records['id']) { ?>
                            <input type="checkbox" class="toggle" name="<?php echo encrypt("enabled|".$records['id']); ?>"
                        <?php if ($records['enabled'] == 1) { echo ' checked '; } ?> />
                        <?php } ?>
                    </div>
                </td>
                <td><a href="resetpassword.php?userid=<?php echo $records['id']; ?>">link</a></td>
                <td>
                    <?php if ($userid != $records['id']) { ?>
                        <!-- Only show delete button if userid isn't self to stop admin deleting their own account -->
                        <a href="<?php echo encrypt($records['id']); ?>" class="gridder_delete">
                        <img src="../images/delete.png" alt="Delete" title="Delete" />
                        </a>
                    <?php } ?>
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
		$username 	= isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
		$realname 		= isset($_POST['realname']) ? mysqli_real_escape_string($conn, $_POST['realname']) : '';
		$password       = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
		$email  	= isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
		$userlevel	= isset($_POST['userlevel']) ? mysqli_real_escape_string($conn, $_POST['userlevel']) : '';
                $timedate = time();
                $salt = strrev(date('U', strtotime($timedate)));
                $newpassword = sha1($salt.$password);
		mysqli_query($conn, "INSERT INTO `systemUser` (timedate, username, password, realname, email, userlevel) VALUES ('$timedate', '$username','$newpassword','$realname','$email', '$userlevel') ");
                $user_q = mysqli_query($conn, "SELECT id FROM systemUser WHERE username='$username'");
                $usern = mysqli_fetch_row($user_q);
                $userid = $usern[0];
                mysqli_query($conn, "CREATE TEMPORARY TABLE temptext SELECT * FROM customtext WHERE userid=0;"); 
                mysqli_query($conn, "UPDATE temptext SET userid='$userid'");
                mysqli_query($conn, "INSERT INTO customtext SELECT * FROM temptext");
                mysqli_query($conn, "DROP TABLE temptext");
                mysqli_close($conn);
                $response['status'] = 'success';
                header('Content-type: application/json');
                echo json_encode($response);
	break;

	case "update":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$crypto = decrypt($_POST['crypto']);
		$explode = explode('|', $crypto);
		$columnName = $explode[0];
		$rowId = $explode[1];
		$query = mysqli_query($conn, "UPDATE `systemUser` SET `$columnName`='$value' WHERE id = '$rowId';");
                if (mysqli_error($conn)) {
                    $error=mysqli_error($conn);
                    mysqli_close($conn);
                    $response['status'] = 'sqlerror'. $error;
                    header('Content-type: application/json');
                    echo json_encode($response);
                    break;
                }
                mysqli_close($conn);

	break;

        case "toggle":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= $_POST['value'];
		$crypto = decrypt($_POST['crypto']);
		$explode = explode('|', $crypto);
		$columnName = $explode[0];
		$rowId = $explode[1];
                // Don't allow the admin user to toggle their own setting
                if ($userid == $rowId) {
                    break;
                }
		$query = mysqli_query($conn, "UPDATE `systemUser` SET `$columnName`='$value' WHERE id='$rowId'");
                mysqli_close($conn);

        break;

	case "resetpassword":
               // do something here
	break;


}
?>
