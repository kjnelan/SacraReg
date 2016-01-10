<?php
/*******************************************************************************
 *
 *  filename    : SelfRegisterHome.php
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
include "Include/UtilityFunctions.php";

error_reporting(-1);

// Connecting, selecting database
$link = mysqli_connect($sSERVERNAME, $sUSER, $sPASSWORD, $sDATABASE)
    or die('Could not connect: ' . mysqli_error());

$reg_id = 0;

$loginMsg = "";

if (isset($_POST["Forgot"])) {
	header('Location: SelfRegisterForgot.php');
	exit();
} else if (isset($_POST["Register"])) {
	header('Location: SelfRegister.php');
	exit();
} else if (isset($_POST["Login"])) { // log in using data from the form
	$reg_username = $link->real_escape_string($_POST["UserName"]);
	$reg_password = $link->real_escape_string($_POST["Password"]);
	
	$query = "SELECT * FROM register_reg WHERE reg_password=SHA2('$reg_password', 0) AND reg_confirmed=1 AND reg_username='$reg_username'";
	
	$result = $link->query($query) or die('Query failed: ' . $link->error());
	if ($result->num_rows == 1) {
		$line = $result->fetch_array(MYSQL_ASSOC);
		extract ($line);
		$_SESSION["RegID"] = $reg_id;
		$_SESSION['CaptchaPassed'] = 'true';
		$fullURL = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$startURL = substr ($fullURL, 0, strlen($fullURL) - strlen("/SelfRegisterHome.php"));
        $_SESSION['sURLPath'] = $startURL;
        $_SESSION['iUserID'] = $reg_perid;
        $_SESSION['LoginType'] = "SelfService";
	} else {
		session_destroy ();
		$reg_id = 0;
		$loginMsg = "Invalid User Name or Password";
	}
	$result->free();
}

if (array_key_exists ("RegID", $_SESSION)) {
	$reg_id = intval ($_SESSION["RegID"]);
	// make sure this user actually exists
	$query = "SELECT * FROM register_reg WHERE reg_id='$reg_id'"; //reg_firstname, reg_lastname
	$result = $link->query($query) or die('Query failed: ' . $link->error());
	if ($result->num_rows == 1) {
		$line = $result->fetch_array(MYSQL_ASSOC);
		extract ($line);
		
		$query = "SELECT * FROM family_fam WHERE fam_id='$reg_famid'";
		$result = $link->query($query) or die('Query failed: ' . $link->error());
		$line = $result->fetch_array(MYSQL_ASSOC);
		if ($result->num_rows == 1)
			extract ($line);
			
		$query = "SELECT * FROM person_per WHERE per_id='$reg_perid'";
		$result = $link->query($query) or die('Query failed: ' . $link->error());
		$line = $result->fetch_array(MYSQL_ASSOC);
		if ($result->num_rows == 1)
			extract ($line);
	}
	$result->free();
}

// initialize everything if the form did not provide values OR the database record did not provide values
if (  (! isset($_POST["Login"])) && $reg_id == 0) {
	$reg_username = "";
	$reg_password = "";
}

?>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link rel="stylesheet" type="text/css" href="Include/RegStyle.css">

<?php 
if ($reg_id == 0) {
?>
<form method="post" action="SelfRegisterHome.php" name="SelfRegisterHome">

<table cellpadding="1" align="center">	
	<tr>
		<td class="RegLabelColumn"><?php echo gettext("User Name");?></td>
		<td class="RegTextColumn"><input type="text" class="RegEnterText" id="UserName" name="UserName" value="<?php echo $reg_username; ?>"></td>
	</tr>
	
	<tr>
		<td class="RegLabelColumn"><?php echo gettext("Password");?></td>
		<td class="RegTextColumn"><input type="password" class="RegEnterText" id="Password" name="Password" value="<?php echo $reg_password; ?>"></td>
	</tr>
<?php if ($loginMsg != "") {?>
	<tr>
		<td></td><td class="RegTextColumn"><?php echo $loginMsg;?></td>
	</tr>
<?php }?>

	<tr>
		<td></td>
		<td align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Login"); ?>" name="Login">
			<input type="submit" class="icButton" value="<?php echo gettext("Register"); ?>" name="Register">
			<input type="submit" class="icButton" value="<?php echo gettext("Forgot User Name or Password"); ?>" name="Forgot">
		</td>
	</tr>

</table>
</form>

<?php 
} else {
?>

<h1><?php echo "$reg_firstname $reg_lastname"; ?></h1>

<h2><?php echo gettext("Personal"); ?></h2>
<?php echo gettext("Name: $per_FirstName $per_LastName<br>"); ?>
<?php echo gettext("Birth date: Year $per_BirthYear, Month $per_BirthMonth, Day $per_BirthDay<br>"); ?>
<?php echo gettext("Email: $per_Email<br>"); ?>
<?php echo gettext("Cell Phone: $per_CellPhone<br>"); ?>
<a href="SelfEditPerson.php"><?php echo gettext("Edit personal information"); ?></a>

<h2><?php echo gettext("Family"); ?></h2>
<?php echo gettext("Address: $fam_Address1 $fam_Address2 $fam_City, $fam_State $fam_Zip<br>"); ?>
<?php echo gettext("Home Phone: $fam_HomePhone<br>"); ?>
<?php echo gettext("Family Email: $fam_Email<br>"); ?>
<a href="SelfEditFamily.php"><?php echo gettext("Edit family information"); ?></a>

<h2><?php echo gettext("Online Registration"); ?></h2>
<?php echo gettext("Address: $reg_address1 $reg_address2 $reg_city, $reg_state $reg_zip<br>"); ?>
<?php echo gettext("Email: $reg_email<br>"); ?>
<a href="SelfRegister.php">Edit Registration</a><br>

<?php 
$currentFYID = CurrentFY(); // self-service just focuses on this fiscal year and next fiscal year
$nextFYID = $currentFYID + 1;

if ($reg_famid > 0) {	// logged in and matched to a family, can show financial information
//Get the pledges for this family
$sSQL = "SELECT plg_plgID, plg_FYID, plg_date, plg_amount, plg_schedule, plg_method,
         plg_comment, plg_DateLastEdited, plg_PledgeOrPayment, a.per_FirstName AS EnteredFirstName,
         a.Per_LastName AS EnteredLastName, b.fun_Name AS fundName, 
         plg_GroupKey
		 FROM pledge_plg
		 LEFT JOIN person_per a ON plg_EditedBy = a.per_ID
		 LEFT JOIN donationfund_fun b ON plg_fundID = b.fun_ID
		 WHERE plg_famID =$reg_famid AND (plg_fyid=$currentFYID OR plg_fyid=$nextFYID) ORDER BY pledge_plg.plg_date";
$rsPledges = $link->query($sSQL);

//Get the automatic payments for this family
$sSQL = "SELECT *, a.per_FirstName AS AutoEnteredFirstName, 
                   a.Per_LastName AS AutoEnteredLastName, 
                   b.fun_Name AS AutoFundName
		 FROM autopayment_aut
		 LEFT JOIN person_per a ON aut_EditedBy = a.per_ID
		 LEFT JOIN donationfund_fun b ON aut_Fund = b.fun_ID
		 WHERE aut_famID = " . $reg_famid . " ORDER BY autopayment_aut.aut_NextPayDate";
$rsAutoPayments = $link->query($sSQL);

}
?>

<h2><?php echo gettext("Pledges and Payments for This Fiscal Year and Next Fiscal Year"); ?></h2>

<table cellpadding="4" cellspacing="0" width="100%">

<tr class="TableHeader" align="center">
	<td><?php echo gettext("Edit"); ?></td>
	<td><?php echo gettext("Pledge or Payment"); ?></td>
	<td><?php echo gettext("Fund"); ?></td>
	<td><?php echo gettext("Fiscal Year"); ?></td>
	<td><?php echo gettext("Date"); ?></td>
	<td><?php echo gettext("Amount"); ?></td>
	<td><?php echo gettext("Schedule"); ?></td>
	<td><?php echo gettext("Method"); ?></td>
	<td><?php echo gettext("Comment"); ?></td>
	<td><?php echo gettext("Date Updated"); ?></td>
	<td><?php echo gettext("Updated By"); ?></td>
</tr>

<?php
$tog = 0;
//Loop through all pledges
while ($aRow = $rsPledges->fetch_array(MYSQL_ASSOC))
{
	$tog = (! $tog);

	extract($aRow);

	//Alternate the row style
	if ($tog)
		$sRowClass = "RowColorA";
	else
		$sRowClass = "RowColorB";

	if ($plg_PledgeOrPayment == 'Payment') {
		if ($tog)
			$sRowClass = "PaymentRowColorA";
		else
			$sRowClass = "PaymentRowColorB";
	}
	?>
	<tr class="<?php echo $sRowClass ?>" align="center">
	<?php if ($plg_method=="CREDITCARD" || $plg_method=="BANKDRAFT") { ?>
		<td><a href=SelfPledgeEdit.php?PledgeOrPayment=<?php echo $plg_PledgeOrPayment?>&PlgID=<?php echo $plg_plgID ?>><?php echo gettext ("Edit");?></a></td>
	<?php } else { ?>
		<td></td>
	<?php } ?>		
		<td><?php echo $plg_PledgeOrPayment ?>&nbsp;</td>
		<td><?php echo $fundName ?>&nbsp;</td>
		<td><?php echo MakeFYString ($plg_FYID) ?>&nbsp;</td>
		<td><?php echo $plg_date ?>&nbsp;</td>
		<td align=center><?php echo $plg_amount ?>&nbsp;</td>
		<td><?php echo $plg_schedule ?>&nbsp;</td>
		<td><?php echo $plg_method; ?>&nbsp;</td>
		<td><?php echo $plg_comment; ?>&nbsp;</td>
		<td><?php echo $plg_DateLastEdited; ?>&nbsp;</td>
		<td><?php echo $EnteredFirstName . " " . $EnteredLastName; ?>&nbsp;</td>
	</tr>
<?php
}
?>
</table>

<h2><?php echo gettext("Electronic Payment Methods"); ?></h2>

<table cellpadding="4" cellspacing="0" width="100%">

<tr class="TableHeader" align="center">
	<td><?php echo gettext("Edit"); ?></td>
	<td><?php echo gettext("Method"); ?></td>
	<td><?php echo gettext("Fund"); ?></td>
	<td><?php echo gettext("Amount"); ?></td>
	<td><?php echo gettext("Schedule"); ?></td>
	<td><?php echo gettext("Name"); ?></td>
	<td><?php echo gettext("Address"); ?></td>
	<td><?php echo gettext("Phone"); ?></td>
	<td><?php echo gettext("Email"); ?></td>
	<td><?php echo gettext("Fiscal Year"); ?></td>
	<td><?php echo gettext("Next Payment Day"); ?></td>
	<td><?php echo gettext("Date Updated"); ?></td>
	<td><?php echo gettext("Updated By"); ?></td>
</tr>

<?php
$tog = 0;
//Loop through all payment methods
while ($aRow = $rsAutoPayments->fetch_array(MYSQL_ASSOC))
{
	$tog = (! $tog);

	extract($aRow);

	//Alternate the row style
	if ($tog)
		$sRowClass = "RowColorA";
	else
		$sRowClass = "RowColorB";
		
	$AutoPaymentMethod = "";
	if ($aut_EnableBankDraft)
		$AutoPaymentMethod = "Bank ACH";
	else if (aut_EnableCreditCard)
		$AutoPaymentMethod = "Credit Card";
		
	$AutoSchedule = "";
	if ($aut_Interval == 1)
		$AutoSchedule = "Monthly";
	else if ($aut_Interval == 3)
		$AutoSchedule = "Quartely";
	else
		$AutoSchedule = "Other";
	$AutoAddress = "$aut_Address1 $aut_Address2 $aut_City, $aut_State $aut_Zip $aut_Country"; 
	$AutoName = "$aut_FirstName $aut_LastName";
	?>
  
	<tr class="<?php echo $sRowClass ?>" align="center">
		<td><a href=SelfAutoPaymentEdit.php?AutID=<?php echo $aut_ID ?>>Edit</a></td>
		<td><?php echo $AutoPaymentMethod ?>&nbsp;</td>
		<td><?php echo $AutoFundName ?>&nbsp;</td>
		<td align=center><?php echo $aut_Amount ?>&nbsp;</td>
		<td><?php echo $AutoSchedule ?>&nbsp;</td>
		<td><?php echo $AutoName ?>&nbsp;</td>
		<td><?php echo $AutoAddress ?>&nbsp;</td>
		<td><?php echo $aut_Phone ?>&nbsp;</td>
		<td><?php echo $aut_Email ?>&nbsp;</td>		
		<td><?php echo MakeFYString ($aut_FYID) ?>&nbsp;</td>
		<td><?php echo $aut_NextPayDate ?>&nbsp;</td>
		<td><?php echo $aut_DateLastEdited; ?>&nbsp;</td>
		<td><?php echo $AutoEnteredFirstName . " " . $AutoEnteredLastName; ?>&nbsp;</td>
	</tr>
<?php
}
?>
</table>

<?php 
$currentFYID = CurrentFY(); // self-service just focuses on this fiscal year and next fiscal year
$nextFYID = $currentFYID + 1;

if ($reg_id > 0) {	// show any pledges entered through this self-service interface
$sSQL = "SELECT *, a.per_FirstName AS EnteredFirstName,
         a.Per_LastName AS EnteredLastName, b.fun_Name AS fundName FROM 
         register_pledge_rpg
		 LEFT JOIN person_per a ON rpg_enteredby = a.per_ID
		 LEFT JOIN donationfund_fun b ON rpg_fund = b.fun_ID
		 WHERE rpg_reguser=$reg_id AND (rpg_fyid=$currentFYID OR rpg_fyid=$nextFYID) ORDER BY register_pledge_rpg.rpg_date";
$rsPledges = $link->query($sSQL);
}
?>

<?php if (0) {?>

<h2><?php echo gettext("Pledges and Payments Entered Through This Interface"); ?></h2>

<table cellpadding="4" cellspacing="0" width="100%">

<tr class="TableHeader" align="center">
	<td><?php echo gettext("Edit"); ?></td>
	<td><?php echo gettext("Pledge or Payment"); ?></td>
	<td><?php echo gettext("Fund"); ?></td>
	<td><?php echo gettext("Fiscal Year"); ?></td>
	<td><?php echo gettext("Date"); ?></td>
	<td><?php echo gettext("Amount"); ?></td>
	<td><?php echo gettext("Schedule"); ?></td>
	<td><?php echo gettext("Method"); ?></td>
	<td><?php echo gettext("Comment"); ?></td>
	<td><?php echo gettext("Date Updated"); ?></td>
	<td><?php echo gettext("Updated By"); ?></td>
</tr>

<?php
$tog = 0;
//Loop through all pledges
while ($aRow = $rsPledges->fetch_array(MYSQL_ASSOC))
{
	$tog = (! $tog);

	extract($aRow);

	//Alternate the row style
	if ($tog)
		$sRowClass = "RowColorA";
	else
		$sRowClass = "RowColorB";

	if ($rpg_pledgeorpayment == 'Payment') {
		if ($tog)
			$sRowClass = "PaymentRowColorA";
		else
			$sRowClass = "PaymentRowColorB";
	}
	?>
	<tr class="<?php echo $sRowClass ?>" align="center">
		<td><a href=SelfPledge.php?RpgID=<?php echo $rpg_id ?>>Edit</a></td>
		<td><?php echo $rpg_pledgeorpayment ?>&nbsp;</td>
		<td><?php echo $fundName ?>&nbsp;</td>
		<td><?php echo MakeFYString ($rpg_fyid) ?>&nbsp;</td>
		<td><?php echo $rpg_date ?>&nbsp;</td>
		<td align=center><?php echo $rpg_annual_amount ?>&nbsp;</td>
		<td><?php echo $rpg_schedule ?>&nbsp;</td>
		<td><?php echo $rpg_method; ?>&nbsp;</td>
		<td><?php echo $rpg_comment; ?>&nbsp;</td>
		<td><?php echo $rpg_changedate; ?>&nbsp;</td>
		<td><?php echo $EnteredFirstName . " " . $EnteredLastName; ?>&nbsp;</td>
	</tr>
<?php
}
?>
</table>
<?php } // if (0) {?>

<a href="SelfPledgeEdit.php?PledgeOrPayment=Pledge">Enter New Pledge</a><br>
<a href="SelfAutoPaymentEdit.php">Enter New Payment Method</a><br>
<a href="SelfPledgeEdit.php?PledgeOrPayment=Payment">Donate Now</a><br>
<a href="SelfRegisterLogout.php">Log Out</a>
<?php 
}
?>

<?php
mysqli_close($link);
?>
