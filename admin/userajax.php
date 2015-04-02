<?php
include '../configuration.php';
include '../functions/functions.php';
include('../generatekey.php');
session_start();
$record="";
$action = $_REQUEST['action'];
$user = $_GET['user'];
switch($action) {
	case "load":
                $rq = mysqli_connect($host, $username, $password, $db);
                      $query = mysqli_query($rq, "SELECT * FROM requests WHERE uniqueid='$user' ORDER BY id ASC");
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
                <th class="del"><div class="grid_heading">BAN</div></th>
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
		$query = mysqli_query($conn, "DELETE FROM `requests` WHERE id = '$value' ");
                mysqli_close($conn);
	break;

	case "ban":
                $conn = mysqli_connect($host,$username,$password,$db);
		$value 	= decrypt($_POST['value']);
	//	$query = mysqli_query($conn, "DELETE FROM `requests` WHERE id = '$value' ");
                mysqli_close($conn);
	break;
}
?>
