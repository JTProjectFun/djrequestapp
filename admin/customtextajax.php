<?php
include_once '../configuration.php';
include_once '../functions/functions.php';
include_once '../generatekey.php';
include_once 'adminconfig.php';
start_session();

$action = $_REQUEST['action'];
$record = "";
switch($action) {
	case "load":

                $conn = mysqli_connect($host, $username, $password, $db);
		$query 	= mysqli_query($conn, "SELECT * FROM `customtext` WHERE userid='$userid'");
		$count  = mysqli_num_rows($query);
		if($count > 0) {
			while($fetch = mysqli_fetch_assoc($query)) {
				$record[] = $fetch;
			}
		}
                mysqli_close($conn);
		?>
        <table class="settings_table">
            <?php
            if($count <= 0) {
            ?>
            <tr id="norecords">
                <td colspan="7" align="center">No customtext found for you!  Oh dear oh dear oh dear </td>
            </tr>
            <?php } else {
            foreach($record as $records) {
                ?>
                <tr>
                    <td>success</td>
                    <td><div class="grid_content editable"><span><?php echo $records['success']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("success|".$records['id']); ?>" value="<?php echo $records['success']; ?>" /></div></td>
                    <td>The message displayed to a user when a request submission is successful.</td>
                </tr>
                <tr>
                    <td>banned</td>
                    <td><div class="grid_content2 editable"><span><?php echo $records['banned']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("banned|".$records['id']); ?>" value="<?php echo $records['banned']; ?>" /></div></td>
                    <td>The message displayed to a user when they've been banned from making requests.</td>
                </tr>
                <tr>
                    <td>toomany</td>
                    <td><div class="grid_content2 editable"><span><?php echo $records['toomany']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("toomany|".$records['id']); ?>" value="<?php echo $records['toomany']; ?>" /></div></td>
                    <td>The message displayed to a user when the maximum number of requests for an event has been made.</td>
                </tr>
                <tr>
                    <td>sqlerror</td>
                    <td><div class="grid_content2 editable"><span><?php echo $records['sqlerror']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("sqlerror|".$records['id']); ?>" value="<?php echo $records['sqlerror']; ?>" /></div></td>
                    <td>The message displayed to a user when there's a problem with the database.</td>
                </tr>
                <tr>
                    <td>tooshort</td>
                    <td><div class="grid_content2 editable"><span><?php echo $records['tooshort']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("tooshort|".$records['id']); ?>" value="<?php echo $records['tooshort']; ?>" /></div></td>
                    <td>The message displayed to a user when the text they enter into a field is too short.</td>
                </tr>
                <tr>
                    <td>toomanyuser</td>
                    <td><div class="grid_content2 editable"><span><?php echo $records['toomanyuser']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("toomanyuser|".$records['id']); ?>" value="<?php echo $records['toomanyuser']; ?>" /></div></td>
                    <td>The message displayed to a user when they've used up their allocation of requests.</td>
                </tr>
                <tr>
                    <td>floodalert</td>
                    <td><div class="grid_content2 editable"><span><?php echo $records['floodalert']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("floodalert|".$records['id']); ?>" value="<?php echo $records['floodalert']; ?>" /></div></td>
                    <td>This text informs the user they must wait between request submissions. $UNITS% will display minutes or seconds. %AMOUNT% will give the number of time units.</td>
                </tr>
                <tr>
                    <td>fieldblank</td>
                    <td><div class="grid_content2 editable"><span><?php echo $records['fieldblank']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("fieldblank|".$records['id']); ?>" value="<?php echo $records['fieldblank']; ?>" /></div></td>
                    <td>This text informs the user they must not leave the %FIELD% name blank. %FIELD% will be replaced by the name of the field they left blank.</td>
                </tr>
                <tr>
                    <td>fieldtoolong</td>
                    <td><div class="grid_content2 editable"><span><?php echo $records['fieldtoolong']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("fieldtoolong|".$records['id']); ?>" value="<?php echo $records['fieldtoolong']; ?>" /></div></td>
                    <td>This text informs the user the text they entered in the %FIELD% was too long. %FIELD% will be replaced by the name of the field.</td>
                </tr>
                <tr>
                    <td>norequests</td>
                    <td><div class="grid_content2 editable"><span><?php echo $records['norequests']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("norequests|".$records['id']); ?>" value="<?php echo $records['norequests']; ?>" /></div></td>
                    <td>This text informs the user there have been no requests made.</td>

                </tr>
                <tr>
                    <td>numrequests</td>
                    <td><div class="grid_content2 editable"><span><?php echo $records['numrequests']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("numrequests|".$records['id']); ?>" value="<?php echo $records['numrequests']; ?>" /></div></td>
                    <td>This text informs the user how many requests have been made.  %HAS% will be replaced with 'have' or 'has', %NUM% by the actual number and %S% pluralises, e.g. 10 request%S% will be '10 requests'.</td>
                </tr>
                <?php
                }
            }
            ?>
            </table>
        <?php
	break;

	case "update":
                $conn = mysqli_connect($host, $username, $password, $db);
		$value 	= $_POST['value'];
		$crypto = decrypt($_POST['crypto']);
		$explode = explode('|', $crypto);
		$columnName = $explode[0];
		$query = mysqli_query($conn, "UPDATE `customtext` SET `$columnName` = '$value' WHERE userid='$userid'");
                mysqli_close($conn);
	break;
}
?>
