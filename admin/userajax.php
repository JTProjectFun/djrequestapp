<?php
include '../configuration.php';
include '../functions/functions.php';
include('../generatekey.php');
session_start();
$record="";
$action = $_REQUEST['action'];
if (isset($_SESSION['listuser'])) {
    $luser = $_SESSION['listuser'];
}

switch($action) {
	case "load":
                $rq = mysqli_connect($host, $username, $password, $db);
                $query = mysqli_query($rq, "SELECT * FROM requestusers WHERE uniqueid='$luser' ORDER BY id ASC");
		$count  = mysqli_num_rows($query);
		if($count > 0) {
			while($fetch = mysqli_fetch_array($query)) {
				$record[] = $fetch;
			}
		}
		?>

        <table class="as_gridder_table">
            <tr>
                <th class="id"><div class="grid_heading">id</div></th>
                <th class="date"><div class="grid_heading">Date &amp; Time Added</div></th>
                <th class="date"><div class="grid_heading">UserString</div></th>
                <th class="date"><div class="grid_heading">Key</div></th>
                <th class="date"><div class="grid_heading">IP Address</div></th>
                <th class="date"><div class="grid_heading">Requests Made</div></th>
                <th class="del"><div class="grid_heading">Delete</div></th>
                <th class="del"><div class="grid_heading">BAN</div></th>
            </tr>

            <?php
            if($count <= 0) {
            ?>
            <tr id="norecords">
                <td colspan="11" align="center">No records found<</td>
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
                        <span><?php echo $records['createdTime']; ?></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><a href="user.php?user=<?php echo $records['uniqueid']; ?>"> <?php echo $records['uniqueid']; ?></a></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><a href="user.php?user=<?php echo $records['uniqueid']; ?>"> <?php echo $records['thekey']; ?></a></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><?php echo $records['ipaddr']; ?></span>
                    </div>
                </td>
                <td class="date">
                    <div class="grid_content sno">
                        <span><?php echo $records['numRequests']; ?></span>
                    </div>
                </td>
                <td>
                    <a href="<?php echo encrypt($records['uniqueid']); ?>" class="gridder_delete">
                        <img src="../images/delete.png" alt="Delete" title="Delete" />
                    </a>
                </td>
                <td>
                    <a href="<?php echo encrypt($records['uniqueid']); ?>" class="gridder_ban">
                        <img src="../images/delete.png" alt="Ban this user" title="BAN" />
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

	case "delete":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= decrypt($_POST['value']);
		$query = mysqli_query($conn, "DELETE FROM `requests` WHERE uniqueid = '$value' ");
                mysqli_close($conn);
	break;

	case "ban":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= decrypt($_POST['value']);
		$query = mysqli_query($conn, "DELETE FROM `requests` WHERE uniqueid = '$value' ");
		$query = mysqli_query($conn, "UPDATE `requestusers` SET banned=1 WHERE uniqueid = '$value' ");
                mysqli_close($conn);
	break;
}
?>
