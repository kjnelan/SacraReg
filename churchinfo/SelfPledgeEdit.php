<?php
/*******************************************************************************
 *
 *  filename    : SelfPledgeEdit.php
 *  copyright   : Copyright 2015 Michael Wilt
 *
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

session_start();

include "Include/Config.php";
require "Include/UtilityFunctions.php";

error_reporting(-1);

// Connecting, selecting database
$link = mysqli_connect($sSERVERNAME, $sUSER, $sPASSWORD, $sDATABASE)
    or die('Could not connect: ' . mysqli_error());

// Get the list of funds
$sSQL = "SELECT fun_ID,fun_Name,fun_Description,fun_Active FROM donationfund_fun WHERE fun_Active = 'true'";
$rsFunds = $link->query($sSQL);
    
$reg_id = 0; // will be registration id for current user

$errStr = "";

if (array_key_exists ("RegID", $_SESSION)) { // Make sure we have a valid login 
	$reg_id = intval ($_SESSION["RegID"]);	
	
	$sSQL = "SELECT * FROM register_reg JOIN family_fam ON reg_famid=fam_ID WHERE reg_id=$reg_id";
	$result = $link->query($sSQL);
	
	if ($result->num_rows != 1) {
		session_destroy ();
		header('Location: SelfRegisterHome.php');
		exit();
	}

	$line = $result->fetch_array(MYSQLI_ASSOC);
	extract ($line); // get $reg_firstname, $reg_lastname, etc.
} else {
	header('Location: SelfRegisterHome.php');
	exit();
}

$plg_plgID = 0; // id for current pledge record
if (array_key_exists ("PlgID", $_GET)) { // See if we are editing an existing record
	$plg_plgID = $_GET["PlgID"];
}

if (isset($_POST["Cancel"])) {
	// bail out without saving
	header('Location: SelfRegisterHome.php');
	exit();
} else if (isset($_POST["Save"])) { // trying to save, use data from the form
	$plg_FYID = $link->real_escape_string($_POST["FYID"]);
	$plg_date = $link->real_escape_string($_POST["Date"]);
	$plg_amount = $link->real_escape_string($_POST["Amount"]);
	$plg_schedule = $link->real_escape_string($_POST["Schedule"]);
	$plg_method = $link->real_escape_string($_POST["Method"]);
	$plg_comment = $link->real_escape_string($_POST["Comment"]);
	$plg_fundID = $link->real_escape_string($_POST["FundID"]);

	$errStr = "";
	if ($plg_amount <= 0.0) {
		$errStr .= "Please check amount.<br>\n";
	}

	if ($errStr == "") {
		// Ok to create or update
		
		$setValueSQL = "SET " .
			"plg_FamID=$fam_ID,". 
			"plg_PledgeOrPayment=\"Pledge\",". 
			"plg_FYID=$plg_FYID,". 
			"plg_date=\"$plg_date\",".
			"plg_amount=$plg_amount,".
			"plg_schedule=\"$plg_schedule\",".
			"plg_method=\"$plg_method\",".
			"plg_comment=\"$plg_comment\",".
			"plg_fundID=$plg_fundID,".
			"plg_EditedBy=$reg_perid,".
			"plg_DateLastEdited=NOW()";
		
		if ($plg_plgID == 0) { // creating a new record
			$sSQL = "INSERT INTO pledge_plg " . $setValueSQL;
			$result = $link->query($sSQL);
			
			$sSQL = "SELECT LAST_INSERT_ID();";
			$result = $link->query($sSQL);
			
			$line = $result->fetch_array(MYSQLI_ASSOC);
			$plg_plgID = $line["LAST_INSERT_ID()"];
		} else {
			$sSQL = "UPDATE pledge_plg " . $setValueSQL . " WHERE plg_plgID=".$plg_plgID;
			$result = $link->query($sSQL);
		}
		header('Location: SelfRegisterHome.php');
		exit();
	}
} else if ($plg_plgID > 0) { // working on a pledge
	$query = "SELECT * FROM pledge_plg WHERE plg_plgID=$plg_plgID";
	$result = $link->query($query) or die('Query failed: ' . $link->error());
	if ($result->num_rows == 0) {
		$plg_plgID = 0;
	} else {
		while ($line = $result->fetch_array(MYSQL_ASSOC)) {
			extract ($line);
		}
	}
	$result->free();
}

// initialize everything if the form did not provide values OR the database record did not provide values
if (  (! isset($_POST["Submit"])) && $plg_plgID == 0) {
	$plg_FYID = CurrentFY();
	$plg_date = date ("Y-m-d");
	$plg_amount = 0.0;
	$plg_schedule = "Monthly";
	$plg_method = "BankDraft";
	$plg_comment = "";
	$plg_fundID = 0;
}
?>

<!DOCTYPE html>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link rel="stylesheet" type="text/css" href="Include/RegStyle.css">

<?php require "Include/CalendarJava.php";?>

<h1>
<?php echo "$reg_firstname $reg_lastname"; ?>
</h1>

<h2>
<?php echo "Pledge"; ?>
</h2>

<form method="post" action="SelfPledgeEdit.php?PlgID=<?php echo $plg_plgID; ?>" name="SelfPledgeEdit">

<table cellpadding="1" align="center">

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Annual Amount");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="Amount" name="Amount" value="<?php echo $plg_amount; ?>"></td>
	</tr>

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Payment Schedule");?></td>
		<td class="RegTextColumn">
			<select class="RegEnterText" id="Schedule" name="Schedule">
				<option value="Monthly" <?php if ($plg_schedule=='Monthly') echo 'Selected'; ?>>Monthly</option>
			    <option value="Quarterly" <?php if ($plg_schedule=='Quarterly') echo 'Selected'; ?>>Quarterly</option>
			    <option value="Once" <?php if ($plg_schedule=='Once') echo 'Selected'; ?>>Once</option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Payment Method");?></td>
		<td class="RegTextColumn">
			<select class="RegEnterText" id="Method" name="Method">
				<option value="BankDraft">Bank Account ACH (preferred) <?php if ($plg_method=='BankDraft') echo 'Selected'; ?></option>
			    <option value="CreditCard" <?php if ($plg_method=='CreditCard') echo 'Selected'; ?>>Credit Card</option>
			    <option value="Check" <?php if ($plg_method=='CHECK') echo 'Selected'; ?>>Check</option>
			    <option value="Cash" <?php if ($plg_method=='CASH') echo 'Selected'; ?>>Cash</option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Fiscal year");?></td>
		<td class="RegTextColumn">
			<select class="RegEnterText" id="FYID" name="FYID">
				<option value="<?php echo CurrentFY(); ?>"<?php if ($plg_FYID==CurrentFY()) echo ' Selected'; ?>>This fiscal year <?php echo MakeFYString (CurrentFY()); ?></option>
				<option value="<?php echo CurrentFY()+1; ?>"<?php if ($plg_FYID==CurrentFY()+1) echo ' Selected'; ?>>Next fiscal year <?php echo MakeFYString (CurrentFY()+1); ?></option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Fund");?></td>
		<td class="TextColumn">
			<select name="FundID">
			<option value="0"><?php echo gettext("None"); ?></option>
			<?php
			mysqli_data_seek($rsFunds,0);
			while ($row = $rsFunds->fetch_array(MYSQL_ASSOC)) {
				$fun_id = $row["fun_ID"];
				$fun_name = $row["fun_Name"];
				$fun_active = $row["fun_Active"];
				echo "<option value=\"$fun_id\" " ;
				if ($plg_fundID == $fun_id)
					echo "selected" ;
				echo ">$fun_name";
				if ($fun_active != 'true') echo " (" . gettext("inactive") . ")";
				echo "</option>" ;
			}
			?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Comment");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="Comment" name="Comment" value="<?php echo $plg_comment; ?>"></td>
	</tr>

	<tr>
		<td class="RegLabelColumn"<?php addToolTip("Format: YYYY-MM-DD<br>or enter the date by clicking on the calendar icon to the right."); ?>><?php echo gettext("Date:"); ?></td>
		<td class="RegTextColumn"><input type="text" name="Date" id="Date" value="<?php echo $plg_date; ?>" maxlength="10" id="Date" size="11">&nbsp;<input type="image" onclick="return showCalendar('Date', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span></td>
	</tr>

<?php if ($errStr != "") { ?>
	<tr>
		<td></td><td class="RegError" align="center"><?php echo $errStr; ?></td>
	</tr>

<?php } ?>

	<tr>
		<td></td><td align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Save"); ?>" name="Save">
			<input type="submit" class="icButton" value="<?php echo gettext("Cancel"); ?>" name="Cancel">
			
			<input type="hidden" name="PledgeOrPayment" id="PledgeOrPayment" value="<?php echo $plg_PledgeOrPayment; ?>">

		</td>
	</tr>

</table>
</form>

<?php
mysqli_close($link);
?>
