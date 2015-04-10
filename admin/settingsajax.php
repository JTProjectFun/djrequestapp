<?php
include '../configuration.php';
include '../functions/functions.php';
include('../generatekey.php');

$action = $_REQUEST['action'];
$record = "";
switch($action) {
	case "load":

                $conn = mysqli_connect($host, $username, $password, $db);
		$query 	= mysqli_query($conn, "SELECT * FROM `settings`");
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
                <td colspan="7" align="center">No settings found!  Oh dear oh dear oh dear </td>
            </tr>
            <?php } else {
            foreach($record as $records) {
                ?>
                <tr>
                    <td class="setting_title">Site Base URL</td>
                    <td style="width:300px"><div class="grid_content editable"><span><?php echo $records['baseURL']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("baseURL|".$records['id']); ?>" value="<?php echo $records['baseURL']; ?>" /></div></td>
                </tr>
                <tr>
                    <td class="setting_title">Session Timeout (minutes)</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['session_timeout']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("session_timeout|".$records['id']); ?>" value="<?php echo $records['session_timeout']; ?>" /></div></td>
                </tr>
                <tr>
                    <td class="setting_title">Flood Period (seconds)</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['flood_period']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("flood_period|".$records['id']); ?>" value="<?php echo $records['flood_period']; ?>" /></div></td>
                </tr>
                <tr>
                    <td class="setting_title">Max Requests Per User</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['maxUserRequestDefault']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("maxUserRequestDefault|".$records['id']); ?>" value="<?php echo $records['maxRequests']; ?>" /></div></td>
                </tr>
                    <td class="setting_title">Max Requests Per Event</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['maxRequestDefault']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("maxRequestDefault|".$records['id']); ?>" value="<?php echo $records['maxRequests']; ?>" /></div></td>
                </tr>
                <tr>
                    <td class="setting_title">Overrun by (hours)</td>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['over_hours']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("over_hours|".$records['id']); ?>" value="<?php echo $records['over_hours']; ?>" /></div></td>
                </tr>
                <tr>
                    <td class="setting_title">Show Requests by default</td>
                    <td class="showreq">
                        <div class="gridder_content">
                            <span></span>
                            <input type="checkbox" class="toggle" name="
                            <?php echo encrypt("show_requests|".$records['id']); ?>"
                            <?php if ($records['show_requests'] == 1) { echo ' checked '; } ?> />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="setting_title">Keys expire by default</td>
                    <td class="expire">
                        <div class="gridder_content">
                            <span></span>
                            <input type="checkbox" class="toggle" name="
                            <?php echo encrypt("willexpire|".$records['id']); ?>"
                            <?php if ($records['willexpire'] == 1) { echo ' checked '; } ?> />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="setting_title">Keep Users For (Days)</td>
                        <div class="gridder_content">
                            <span></span>
                    <td class="date"><div class="grid_content editable"><span><?php echo $records['maxUserAge']; ?></span>
                    <input type="text" class="gridder_input" name="<?php echo encrypt("maxUserAge|".$records['id']); ?>" value="<?php echo $records['maxUserAge']; ?>" />
                    </td>
                    </div>
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
		$query = mysqli_query($conn, "UPDATE `settings` SET `$columnName` = '$value'");
                mysqli_close($conn);
	break;
}
?>
