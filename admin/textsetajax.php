<?php
include '../configuration.php';
include '../functions/functions.php';
include('../generatekey.php');

$action = $_REQUEST['action'];
$record = "";
switch($action) {
	case "load":

                $conn = mysqli_connect($host, $username, $password, $db);
		$query 	= mysqli_query($conn, "SELECT * FROM `customtext`");
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
                <td colspan="7" align="center">No custom text found!  Oh dear oh dear oh dear </td>
            </tr>
            <?php } else {
            foreach($record as $records) {
                ?>

                <tr>
                    <td class="setting_title">toomanyuser</td>
                    <td style="width:300px"><div class="grid_content editable"><span><?php echo $records['toomanyuser']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("toomanyuser|".$records['id']); ?>" value="<?php echo $records['toomanyuser']; ?>" /></div></td>
                    <td>You have already made the maximum amount of requests allowed, sorry.</td>
                </tr>
                <tr>
                    <td class="setting_title">toomany</td>
                    <td style="width:300px"><div class="grid_content editable"><span><?php echo $records['toomany']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("toomany|".$records['id']); ?>" value="<?php echo $records['toomany']; ?>" /></div></td>
                   <td>The maximum number of requests for this event has been reached, sorry.</td>
                </tr>
                <tr>
                    <td class="setting_title">floodalert</td>
                    <td style="width:300px"><div class="grid_content editable"><span><?php echo $records['floodalert']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("floodalert|".$records['id']); ?>" value="<?php echo $records['floodalert']; ?>" /></div></td>
                    <td>You may only make one request every %AMOUNT% %UNITS%. Try again later.</td>
                </tr>
                <tr>
                    <td class="setting_title">goodpopup</td>
                    <td style="width:300px"><div class="grid_content editable"><span><?php echo $records['goodpopup']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("goodpopup|".$records['id']); ?>" value="<?php echo $records['goodpopup']; ?>" /></div></td>
                    <td>Your request submission was successful.</td>
                </tr>
                <tr>
                    <td class="setting_title">fielblankerror</td>
                    <td style="width:300px"><div class="grid_content editable"><span><?php echo $records['fieldblankerror']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("fieldblankerror|".$records['id']); ?>" value="<?php echo $records['fieldblankerror']; ?>" /></div></td>
                    <td>The %FIELD% cannot be left blank. Please try again.</td>
                </tr>
                <tr>
                    <td class="setting_title">fieldtoolongerror</td>
                    <td style="width:300px"><div class="grid_content editable"><span><?php echo $records['fieldtoolongerror']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("fieldtoolongerror|".$records['id']); ?>" value="<?php echo $records['fieldtoolongerror']; ?>" /></div></td>
                    <td>The %FIELD% you entered is too long. Please try again.</td>
                </tr>

                <tr>
                    <td class="setting_title">norequests</td>
                    <td style="width:300px"><div class="grid_content editable"><span><?php echo $records['norequests']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("norequests|".$records['id']); ?>" value="<?php echo $records['norequests']; ?>" /></div></td>
                    <td>There have been no requests made yet</td>
                </tr>
                <tr>
                    <td class="setting_title">numrequests</td>
                    <td style="width:300px"><div class="grid_content editable"><span><?php echo $records['numrequests']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("numrequests|".$records['id']); ?>" value="<?php echo $records['numrequests']; ?>" /></div></td>
                    <td>There %HAS% been %NUM% request%S% made so far</td>
                </tr>
                <tr>
                    <td class="setting_title">Banned</td>
                    <td style="width:300px"><div class="grid_content editable"><span><?php echo $records['baseURL']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("banned|".$records['id']); ?>" value="<?php echo $records['banned']; ?>" /></div></td>
                    <td>I'm sorry. You cannot make any more requests</td>
                </tr>
                <tr>
                    <td class="setting_title">Too many requests</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['toomany']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("toomany|".$records['id']); ?>" value="<?php echo $records['toomany']; ?>" /></div></td>
                    <td>You have already submitted the maximum number of requests set by the administrator</td>
                </tr>
                <tr>
                    <td class="setting_title">SQL Error</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['sqlerror']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("sqlerror|".$records['id']); ?>" value="<?php echo $records['sqlerror']; ?>" /></div></td>
                    <td>There seems to be a problem with the database</td>
                </tr>
                <tr>
                    <td class="setting_title">tooshort</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['tooshort']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("tooshort|".$records['id']); ?>" value="<?php echo $records['tooshort']; ?>" /></div></td>
                    <td>There was a problem with the %FIELD%. Please try again</td>
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
		$query = mysqli_query($conn, "UPDATE `customtext` SET `$columnName` = '$value'");
                mysqli_close($conn);
	break;
}
?>
