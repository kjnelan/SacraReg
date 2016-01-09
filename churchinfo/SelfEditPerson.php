<?php
/*******************************************************************************
 *
 *  filename    : SelfEditPerson.php
 *  copyright   : Copyright 2016 Michael Wilt
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

$reg_id = 0; // will be registration id for current user

$errStr = "";

if (array_key_exists ("RegID", $_SESSION)) { // Make sure we have a valid login 
	$reg_id = intval ($_SESSION["RegID"]);
		
	$sSQL = "SELECT * FROM  register_reg JOIN person_per on reg_perid=per_ID WHERE reg_id=$reg_id";
	$result = $link->query($sSQL);

	if ($result->num_rows != 1) {
		session_destroy ();
		header('Location: SelfRegisterHome.php');
		exit();
	}
			
	$line = $result->fetch_array(MYSQLI_ASSOC);
	extract ($line); // get $reg_firstname, $reg_lastname, per_* etc.
} else {
	header('Location: SelfRegisterHome.php');
	exit();
}

if (isset($_POST["Cancel"])) {
	// bail out without saving
	header('Location: SelfRegisterHome.php');
	exit();
} else if (isset($_POST["Save"])) { // trying to save, use data from the form
	$per_FirstName = $link->real_escape_string($_POST["FirstName"]);
	$per_MiddleName = $link->real_escape_string($_POST["MiddleName"]);
	$per_LastName = $link->real_escape_string($_POST["LastName"]);
	$per_BirthYear = $link->real_escape_string($_POST["BirthYear"]);
	$per_BirthMonth = $link->real_escape_string($_POST["BirthMonth"]);
	$per_BirthDay = $link->real_escape_string($_POST["BirthDay"]);
	$per_Email = $link->real_escape_string($_POST["Email"]);
	$per_CellPhone = $link->real_escape_string($_POST["CellPhone"]);
	
	$errStr = "";
	if ($per_FirstName == "") {
		$errStr .= "Please check First Name.<br>\n";
	}
	if ($per_LastName == "") {
		$errStr .= "Please check First Name.<br>\n";
	}
	if ($per_BirthYear == "") {
		$errStr .= "Please check birth year.<br>\n";
	}
	if ($per_BirthMonth == "") {
		$errStr .= "Please check birth month.<br>\n";
	}
	if ($per_BirthDay == "") {
		$errStr .= "Please check birth day.<br>\n";
	}
	if ($per_Email == "") {
		$errStr .= "Please check Email.<br>\n";
	}
	
	if ($errStr == "") {
		// Ok to create or update
		
		$setValueSQL = "SET " .
			"per_FirstName = \"$per_FirstName\",".
			"per_MiddleName = \"$per_MiddleName\",".
			"per_LastName = \"$per_LastName\",".
			"per_BirthYear = \"$per_BirthYear\",".
			"per_BirthMonth = \"$per_BirthMonth\",".
			"per_BirthDay = \"$per_BirthDay\",".
			"per_Email = \"$per_Email\",".
			"per_CellPhone = \"$per_CellPhone\",".
			"per_EditedBy=$reg_perid,".
			"per_DateLastEdited=NOW()";
		
		if ($per_ID == 0) { // creating a new record
			$sSQL = "INSERT INTO person_per " . $setValueSQL;
			$result = $link->query($sSQL);
			
			$sSQL = "SELECT LAST_INSERT_ID();";
			$result = $link->query($sSQL);
			
			$line = $result->fetch_array(MYSQLI_ASSOC);
			$per_ID = $line["LAST_INSERT_ID()"];
		} else {
			$sSQL = "UPDATE person_per " . $setValueSQL . " WHERE per_id=".$per_ID;
			$result = $link->query($sSQL);
		}
		header('Location: SelfRegisterHome.php');
		exit();
	}
}

// initialize everything if the form did not provide values OR the database record did not provide values
if (  (! isset($_POST["Submit"])) && $per_ID == 0) {
	$per_FirstName = "";
	$per_MiddleName = "";
	$per_LastName = "";
	$per_BirthYear = "";
	$per_BirthMonth = "";
	$per_BirthDay = "";
	$per_Email = "";
	$per_CellPhone = "";
}
?>

<!DOCTYPE html>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link rel="stylesheet" type="text/css" href="Include/RegStyle.css">

<h1>
<?php echo "$reg_firstname $reg_lastname"; ?>
</h1>

<h2>
<?php echo "Update personal information"; ?>
</h2>

<form method="post" action="SelfEditPerson.php" name="SelfEditPerson">

<table cellpadding="1" align="center">
	<tr>
		<td class="RegLabelColumn"><?php echo gettext("First Name");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="FirstName" name="FirstName" value="<?php echo $per_FirstName; ?>"></td>
	</tr>

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Middle Name");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="MiddleName" name="MiddleName" value="<?php echo $per_MiddleName; ?>"></td>
	</tr>

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Last Name");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="LastName" name="LastName" value="<?php echo $per_LastName; ?>"></td>
	</tr>

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Birth Year");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="BirthYear" name="BirthYear" value="<?php echo $per_BirthYear; ?>"></td>
	</tr>

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Birth Month");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="BirthMonth" name="BirthMonth" value="<?php echo $per_BirthMonth; ?>"></td>
	</tr>

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Birth Day");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="BirthDay" name="BirthDay" value="<?php echo $per_BirthDay; ?>"></td>
	</tr>

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Email");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="Email" name="Email" value="<?php echo $per_Email; ?>"></td>
	</tr>

	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Cell Phone");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="CellPhone" name="CellPhone" value="<?php echo $per_CellPhone; ?>"></td>
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
		</td>
	</tr>

</table>
</form>

<?php
mysqli_close($link);
?>
