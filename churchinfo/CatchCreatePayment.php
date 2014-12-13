<?php 
require "Include/Config.php";
require "Include/Functions.php";
require "Include/VancoConfig.php";

// set into the Vanco interface by AutoPaymentEditor.php
$iVancoAutID = FilterInput($_POST["customerid"],'int'); 

// this is what we are really after- this handle can be used to initiate authorized transactions
$iVancoPaymentMethodRef = FilterInput($_POST["paymentmethodref"], 'int');

$sVancoPaymentCreditCard = "";
$iEnableCreditCard = 0;
if (FilterInput ($_POST['accounttype']) == "CC") {
	$iVancoPaymentCreditCard = "$iVancoPaymentMethodRef";
	$iEnableCreditCard = 1;
}

$sVancoPaymentBankDraft = "";
$iEnableBankDraft = 0;
if (FilterInput ($_POST['accounttype']) == "C") {
	$iVancoPaymentBankDraft = "$iVancoPaymentMethodRef";
	$iEnableBankDraft = 1;
}

// Other information that was just entered into the payment page that we will store for reference
$sVancoName = FilterInput ($_POST["name"]);
$aVancoNames = explode (" ", $sVancoName, 2);
$sVancoFirstName = $aVancoNames[0];
$sVancoLastName = $aVancoNames[1];
$sVancoAddr1 = FilterInput ($_POST["billingaddr1"]);
$sVancoBillingCity = FilterInput ($_POST["billingcity"]);
$sVancoBillingState = FilterInput ($_POST["billingstate"]);
$sVancoBillingZip = FilterInput ($_POST["billingzip"]);
$sVancoEmail = FilterInput ($_POST["email"]);
$sVancoExpMonth = FilterInput ($_POST["expmonth"]);
$sVancoExpYear = FilterInput ($_POST["expyear"]);

// information reflected back (use for verification)
$sVancoClientID = FilterInput($_POST["clientid"]);

$sSQL = "UPDATE autopayment_aut SET ";
$sSQL .= "aut_FirstName=\"$sVancoFirstName\"";
$sSQL .= ", aut_LastName=\"$sVancoLastName\"";
$sSQL .= ", aut_Address1=\"$sVancoAddr1\"";
$sSQL .= ", aut_City=\"$sVancoBillingCity\"";
$sSQL .= ", aut_State=\"$sVancoBillingState\"";
$sSQL .= ", aut_Zip=\"$sVancoBillingZip\"";
$sSQL .= ", aut_Email=\"$sVancoEmail\"";
$sSQL .= ", aut_EnableCreditCard=\"$iEnableCreditCard\"";
$sSQL .= ", aut_CreditCardVanco=\"$iVancoPaymentCreditCard\"";
$sSQL .= ", aut_EnableBankDraft=\"$iEnableBankDraft\"";
$sSQL .= ", aut_AccountVanco=\"$iVancoPaymentBankDraft\"";
$sSQL .= ", aut_ExpMonth=\"$sVancoExpMonth\"";
$sSQL .= ", aut_ExpYear=\"$sVancoExpYear\"";
$sSQL .= ", aut_DateLastEdited=\"" . date ("YmdHis"). "\"";
$sSQL .= ", aut_EditedBy=" . $_SESSION['iUserID'];
$sSQL .= " WHERE aut_ID=$iVancoAutID";

$bSuccess = false;
if ($result = mysql_query($sSQL, $cnInfoCentral))
    $bSuccess = true;

if (! $bSuccess) {
	$errStr = gettext("Cannot execute query.") . "<p>$sSQL<p>" . mysql_error();
	$var_str = var_export($_POST, true);
	$logf = fopen ("CatchCreatePayment.log", "a");
	fprintf ($logf, "%s\n%s\n", $var_str, $errStr);
	fclose ($logf);
}

header('Content-type: application/json');
echo json_encode(array('Success'=>$bSuccess));
?>
