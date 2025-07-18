<?php
/*******************************************************************************
 *
 *  filename    : PledgeEditor.php
 *  last change : 2012-06-29
 *  website     : http://www.churchdb.org
 *  copyright   : Copyright 2001, 2002, 2003 Deane Barker, Chris Gebhardt
 *                Copyright 2004-2012Michael Wilt
 *
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

global $iChecksPerDepositForm;

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";
require "Include/MICRFunctions.php";

if ($bUseScannedChecks) { // Instantiate the MICR class
   $micrObj = new MICRReader();
}

$iEnvelope = 0;
$sCheckNoError = "";
$iCheckNo = "";
$sDateError = "";
$sAmountError = "";
$iTotalAmount = 0;
$nNonDeductible = array ();
$sComment = "";
$tScanString = "";
$dep_Closed = false;
$iAutID = 0;
$iCurrentDeposit = 0;
$iSelectedFund = 0;
$fundID = 0;
$PledgeOrPayment = "";
$iMethod = "";
$dDate = "";

$iOriginalSelectedFund = 0;

$nAmount = array (); // this will be the array for collecting values for each fund
$sAmountError = array ();
$sComment = array ();

$checkHash = array();

// Get the list of funds
$sSQL = "SELECT fun_ID,fun_Name,fun_Description,fun_Active FROM donationfund_fun";
$sSQL .= " WHERE fun_Active = 'true'"; // New donations should show only active funds.

$rsFunds = RunQuery($sSQL);
mysqli_data_seek($rsFunds, 0);
while ($aRow = mysqli_fetch_array($rsFunds)) {
	extract($aRow);
	$fundId2Name[$fun_ID] = $fun_Name;
	$nAmount[$fun_ID] = 0.0;
	$nNonDeductible[$fun_ID] = 0.0;
	$sAmountError[$fun_ID] = "";
	$sComment[$fun_ID] = "";
	if (!isset($defaultFundID)) {
		$defaultFundID = $fun_ID;
	}
	$fundIdActive[$fun_ID] = $fun_Active;
} // end while

// Handle URL via _GET first
if (array_key_exists ("PledgeOrPayment", $_GET))
	$PledgeOrPayment = FilterInput($_GET["PledgeOrPayment"],'string');
$sGroupKey = "";
if (array_key_exists ("GroupKey", $_GET))
	$sGroupKey = FilterInput($_GET["GroupKey"],'string'); // this will only be set if someone pressed the 'edit' button on the Pledge or Deposit line
if (array_key_exists ("CurrentDeposit", $_GET))
	$iCurrentDeposit = FilterInput($_GET["CurrentDeposit"],'integer');
$linkBack = FilterInput($_GET["linkBack"],'string');
$iFamily = 0;
if (array_key_exists ("FamilyID", $_GET))
	$iFamily = FilterInput($_GET["FamilyID"],'int');

$fund2PlgIds = array(); // this will be the array cross-referencing funds to existing plg_plgid's

if ($sGroupKey != "") {
	$sSQL = "SELECT plg_plgID, plg_fundID, plg_EditedBy, plg_depID, plg_method from pledge_plg where plg_GroupKey=\"" . $sGroupKey . "\"";
	$rsKeys = RunQuery($sSQL);
	while ($aRow = mysqli_fetch_array($rsKeys)) {
		$onePlgID = $aRow["plg_plgID"];
		$oneFundID = $aRow["plg_fundID"];
		$iOriginalSelectedFund = $oneFundID; // remember the original fund in case we switch to splitting
		$fund2PlgIds[$oneFundID] = $onePlgID;
		$iCurrentDeposit = $aRow["plg_depID"];
		$iMethod = $aRow["plg_method"];

		// Security: User must have Finance permission or be the one who entered this record originally
		if (! ($_SESSION['bFinance'] || $_SESSION['iUserID']==$aRow["plg_EditedBy"])) {
			Redirect("Menu.php");
			exit;
		}	
	}
}

// Handle _POST input if the form was up and a button press came in
if (isset($_POST["PledgeSubmit"]) or
    isset($_POST["PledgeSubmitAndAdd"]) or
    isset($_POST["MatchFamily"]) or 
    isset($_POST["MatchEnvelope"]) or 
    isset($_POST["SetDefaultCheck"]) or
    isset($_POST["SetFundTypeSelection"]) or
    isset($_POST["SplitTotal"])) {

	$iFamily = FilterInput($_POST["FamilyID"],'int');

   	$dDate = FilterInput($_POST["Date"]);
	if (!$dDate) {
		if (array_key_exists ('idefaultDate', $_SESSION))
			$dDate = $_SESSION['idefaultDate'];
		else
			$dDate = date ("Y-m-d");
	}
	$_SESSION['idefaultDate'] = $dDate;

	if (isset($_POST["FundSplit"])) {
		$iSelectedFund = FilterInput($_POST["FundSplit"]);
		$_SESSION['iSelectedFund'] = $iSelectedFund;
	} 
	$_SESSION['iSelectedFund'] = $iSelectedFund;

	// set from drop-down if set, saved session default, or by calcuation
	$iFYID = FilterInput($_POST["FYID"], 'int');
	if (!$iFYID) {
		$iFYID =  $_SESSION['idefaultFY'];
	}
	if (!$iFYID) {
		$iFYID = CurrentFY();
	}
	$_SESSION['idefaultFY'] = $iFYID;
	
	if (array_key_exists ("CheckNo", $_POST))
		$iCheckNo = FilterInput($_POST["CheckNo"], 'int');
	else
		$iCheckNo = 0;
	
	if (array_key_exists ("Schedule", $_POST))
		$iSchedule = FilterInput($_POST["Schedule"]);
	else
		$iSchedule='Once';
	$_SESSION['iDefaultSchedule'] = $iSchedule;
	
	$iMethod = "";
	if (array_key_exists ("Method", $_POST))
    	$iMethod = FilterInput($_POST["Method"]);
	if ($iMethod=="") {
		if ($sGroupKey) {
			$sSQL = "SELECT DISTINCT plg_method FROM pledge_plg WHERE plg_GroupKey='" . $sGroupKey . "'";
			$rsResults = RunQuery($sSQL);
			list($iMethod) = mysqli_fetch_row($rsResults);
		} elseif ($iCurrentDeposit) {
			$sSQL = "SELECT plg_method from pledge_plg where plg_depID=\"" . $iCurrentDeposit . "\" ORDER by plg_plgID DESC LIMIT 1";
			$rsMethod = RunQuery($sSQL);
			$num = mysqli_num_rows($rsMethod);
			if ($num) {	// set iMethod to last record's setting
				extract(mysqli_fetch_array($rsMethod));  
				$iMethod = $plg_method;
			} else {
				$iMethod = "CHECK";
			}
		} else {
			$iMethod = "CHECK";
		}
	}
	$_SESSION['idefaultPaymentMethod'] = $iMethod;
	
	$iEnvelope = 0;
	if (array_key_exists ("Envelope", $_POST))
		$iEnvelope = FilterInput($_POST["Envelope"], 'int');
	$iTotalAmount = FilterInput($_POST["TotalAmount"]);
	if (array_key_exists ("OneComment", $_POST))
		$sOneComment = FilterInput($_POST["OneComment"]);
	else
		$sOneComment = "";
	if ($iSelectedFund) {
		$nAmount[$iSelectedFund] = $iTotalAmount;
		$sComment[$iSelectedFund] = $sOneComment;
	}
} else { // Form was not up previously, take data from existing records or make default values
	if ($sGroupKey) {
		$sSQL = "SELECT COUNT(plg_GroupKey) FROM pledge_plg WHERE plg_GroupKey='" . $sGroupKey. "'";
		$rsResults = RunQuery($sSQL);		
		list($numGroupKeys) = mysqli_fetch_row($rsResults);
		$iSelectedFund = 0;

		$iTotalAmount = 0;
		$sSQL = "SELECT DISTINCT plg_famID, plg_CheckNo, plg_date, plg_method, plg_FYID from pledge_plg where plg_GroupKey='" . $sGroupKey . "'";
	 	//	don't know if we need plg_date or plg_method here...  leave it here for now
		$rsFam = RunQuery($sSQL);
		extract(mysqli_fetch_array($rsFam));
	
		$iFamily = $plg_famID;
		$iCheckNo = $plg_CheckNo;
		$iFYID = $plg_FYID;
	
		$sSQL = "SELECT plg_plgID, plg_fundID, plg_amount, plg_comment from pledge_plg where plg_GroupKey='" . $sGroupKey . "'";
	
		$rsAmounts = RunQuery($sSQL);
		while ($aRow = mysqli_fetch_array($rsAmounts)) {
			extract($aRow);
			$nAmount[$plg_fundID] = $plg_amount;
			$sComment[$plg_fundID] = $plg_comment;
			$iTotalAmount += $plg_amount;
			if (mysqli_num_rows($rsAmounts) == 1)
			    $iSelectedFund = $plg_fundID;
		}
	} else {
		if (array_key_exists ('idefaultDate', $_SESSION))
			$dDate = $_SESSION['idefaultDate'];
		else
			$dDate = date ("Y-m-d");
		if (array_key_exists ('iSelectedFund', $_SESSION))
			$iSelectedFund = $_SESSION['iSelectedFund'];
		else
			$iSelectedFund = 0;
	 	$fundId = $iSelectedFund;
	 	if (array_key_exists ('idefaultFY', $_SESSION))
			$iFYID = $_SESSION['idefaultFY'];
		else
			$iFYID = CurrentFY ();
	 	if (array_key_exists ('iDefaultSchedule', $_SESSION))
			$iSchedule = $_SESSION['iDefaultSchedule'];
		else
			$iSchedule = 'Once';
		if (array_key_exists ('idefaultPaymentMethod', $_SESSION))
			$iMethod = $_SESSION['idefaultPaymentMethod'];
		else
			$iMethod = 'Check';
	}
	if (!$iEnvelope and $iFamily) {
		$sSQL = "SELECT fam_Envelope FROM family_fam WHERE fam_ID=\"" . $iFamily . "\";";
		$rsEnv = RunQuery($sSQL);
		extract(mysqli_fetch_array($rsEnv));
		if ($fam_Envelope) {
			$iEnvelope = $fam_Envelope;
		}
	}
}

if ($PledgeOrPayment == 'Pledge') { // Don't assign the deposit slip if this is a pledge
	$iCurrentDeposit = 0;
} else { // its a deposit
	if ($iCurrentDeposit > 0) {
		$_SESSION['iCurrentDeposit'] = $iCurrentDeposit;
	} else {
		$iCurrentDeposit = $_SESSION['iCurrentDeposit'];
	}

	// Get the current deposit slip data
	if ($iCurrentDeposit) {
		$sSQL = "SELECT dep_Closed, dep_Date, dep_Type from deposit_dep WHERE dep_ID = " . $iCurrentDeposit;
		$rsDeposit = RunQuery($sSQL);
		extract(mysqli_fetch_array($rsDeposit));
	}
}

$dep_Type = "";
if ($iMethod == "CASH" or $iMethod == "CHECK")
	$dep_Type = "Bank";
elseif ($iMethod == "CREDITCARD")
	$dep_Type = "CreditCard";
elseif ($iMethod == "BANKDRAFT")
	$dep_Type = "BankDraft";

if ($PledgeOrPayment == 'Payment') {
	$bEnableNonDeductible = 1; // this could/should be a config parm?  regardless, having a non-deductible amount for a pledge doesn't seem possible
}

if (isset($_POST["PledgeSubmit"]) or isset($_POST["PledgeSubmitAndAdd"])) {
	//Initialize the error flag
	$bErrorFlag = false;

	if (!$iSelectedFund) { // split
		// make sure at least one fund has a non-zero numer
		$nonZeroFundAmountEntered = 0;
		foreach ($fundId2Name as $fun_id => $fun_name) {
			//$fun_active = $fundActive[$fun_id];
			$nAmount[$fun_id] = FilterInput($_POST[$fun_id . "_Amount"]);
			$sComment[$fun_id] = FilterInput($_POST[$fun_id . "_Comment"]);
			if ($nAmount[$fun_id] > 0) {
				++$nonZeroFundAmountEntered;
			}

			if ($bEnableNonDeductible) {
				$nNonDeductible[$fun_id] = FilterInput($_POST[$fun_id . "_NonDeductible"]);
				//Validate the NonDeductible Amount
				if ($nNonDeductible[$fun_id] > $nAmount[$fun_id]) { //Validate the NonDeductible Amount
					$sNonDeductibleError[$fun_id] = gettext("NonDeductible amount can't be greater than total amount.");
				$bErrorFlag = true;
				}
			}
		} // end foreach

		if (!$nonZeroFundAmountEntered) {
			$sAmountError[$fun_id] = gettext("At least one fund must have a non-zero amount.");
			$bErrorFlag = true;
		}
	}

	if (array_key_exists ("ScanInput", $_POST))
		$tScanString = FilterInput($_POST["ScanInput"]);
	else
		$tScanString = "";
	$iAutID = 0;
	if (array_key_exists ("AutoPay", $_POST))
		$iAutID = FilterInput($_POST["AutoPay"]);
	//$iEnvelope = FilterInput($_POST["Envelope"], 'int');

	if ($PledgeOrPayment=='Payment' and !$iCheckNo and $iMethod == "CHECK") {
		$sCheckNoError = "<span style=\"color: red; \">" . gettext("Must specify non-zero check number") . "</span>";
		$bErrorFlag = true;
	}

	// detect check inconsistencies
	if ($PledgeOrPayment=='Payment' and $iCheckNo) {
		if ($iMethod == "CASH") {
			$sCheckNoError = "<span style=\"color: red; \">" . gettext("Check number not valid for 'CASH' payment") . "</span>";
			$bErrorFlag = true;
		} elseif ($iMethod=='CHECK' and !$sGroupKey) {
			$chkKey = $iFamily . "|" . $iCheckNo;
			if (array_key_exists($chkKey, $checkHash)) {
				$text = "Check number '" . $iCheckNo . "' for selected family already exists.";
				$sCheckNoError = "<span style=\"color: red; \">" . gettext($text) . "</span>";
				$bErrorFlag = true;
			}
		}
	}

	// Validate Date
	if (strlen($dDate) > 0) {
		list($iYear, $iMonth, $iDay) = sscanf($dDate,"%04d-%02d-%02d");
		if ( !checkdate($iMonth,$iDay,$iYear) ) {
			$sDateError = "<span style=\"color: red; \">" . gettext("Not a valid Date") . "</span>";
			$bErrorFlag = true;
		}
	}

	//If no errors, then let's update...
	if (!$bErrorFlag and !$dep_Closed) {
		// Only set PledgeOrPayment when the record is first created
		// loop through all funds and create non-zero amount pledge records
		foreach ($fundId2Name as $fun_id => $fun_name) {
			if (!$iCheckNo) { $iCheckNo = 0; }
			unset($sSQL);
			if ($fund2PlgIds and array_key_exists($fun_id, $fund2PlgIds)) {
				if ($nAmount[$fun_id] > 0) {
					$sSQL = "UPDATE pledge_plg SET plg_famID = '" . $iFamily . "',plg_FYID = '" . $iFYID . "',plg_date = '" . $dDate . "', plg_amount = '" . $nAmount[$fun_id] . "', plg_schedule = '" . $iSchedule . "', plg_method = '" . $iMethod . "', plg_comment = '" . $sComment[$fun_id] . "'";
					$sSQL .= ", plg_DateLastEdited = '" . date("YmdHis") . "', plg_EditedBy = " . $_SESSION['iUserID'] . ", plg_CheckNo = '" . $iCheckNo . "', plg_scanString = '" . $tScanString . "', plg_aut_ID='" . $iAutID . "', plg_NonDeductible='" . $nNonDeductible[$fun_id] . "' WHERE plg_plgID='" . $fund2PlgIds[$fun_id] . "'";
				} else { // delete that record
					$sSQL = "DELETE FROM pledge_plg WHERE plg_plgID =" . $fund2PlgIds[$fun_id];
				}
			} elseif ($nAmount[$fun_id] > 0) {
				if ($iMethod <> "CHECK") {
					$iCheckNo = "NULL";
				}
				if (!$sGroupKey) {
					$sGroupKey = genGroupKeyByMethod($iMethod, $iCheckNo, $iFamily, $fun_id, $dDate, $iAutID);
//					if ($iMethod == "CHECK") {
//						$sGroupKey = genGroupKey($iCheckNo, $iFamily, $fun_id, $dDate);
//					} elseif ($iMethod == "BANKDRAFT") {
//						if (!$iAutID) {
//							$iAutID = "draft";
//						}
//						$sGroupKey = genGroupKey($iAutID, $iFamily, $fun_id, $dDate);
//					} elseif ($iMethod == "CREDITCARD") {
//						if (!$iAutID) {
//							$iAutID = "credit";
//						}
//						$sGroupKey = genGroupKey($iAutID, $iFamily, $fun_id, $dDate);
//					} else {
//						$sGroupKey = genGroupKey("cash", $iFamily, $fun_id, $dDate);
//					} 
				}
				$sSQL = "INSERT INTO pledge_plg (plg_famID, plg_FYID, plg_date, plg_amount, plg_schedule, plg_method, plg_comment, plg_DateLastEdited, plg_EditedBy, plg_PledgeOrPayment, plg_fundID, plg_depID, plg_CheckNo, plg_scanString, plg_aut_ID, plg_NonDeductible, plg_GroupKey)
			VALUES ('" . $iFamily . "','" . $iFYID . "','" . $dDate . "','" . $nAmount[$fun_id] . "','" . $iSchedule . "','" . $iMethod  . "','" . $sComment[$fun_id] . "'";
				$sSQL .= ",'" . date("YmdHis") . "'," . $_SESSION['iUserID'] . ",'" . $PledgeOrPayment . "'," . $fun_id . "," . $iCurrentDeposit . "," . $iCheckNo . ",'" . $tScanString . "','" . $iAutID  . "','" . $nNonDeductible[$fun_id] . "','" . $sGroupKey . "')";
			}
			if (isset ($sSQL)) {
				RunQuery($sSQL);
				unset($sSQL);
			}
		} // end foreach of $fundId2Name
		if (isset($_POST["PledgeSubmit"])) {
			// Check for redirection to another page after saving information: (ie. PledgeEditor.php?previousPage=prev.php?a=1;b=2;c=3)
			if ($linkBack != "") {
				Redirect($linkBack);
			} else {
				//Send to the view of this pledge
				Redirect("PledgeEditor.php?PledgeOrPayment=" . $PledgeOrPayment . "&GroupKey=" . $sGroupKey . "&linkBack=", $linkBack);
			}
		} elseif (isset($_POST["PledgeSubmitAndAdd"])) {
			//Reload to editor to add another record
			Redirect("PledgeEditor.php?CurrentDeposit=$iCurrentDeposit&PledgeOrPayment=" . $PledgeOrPayment . "&linkBack=", $linkBack);
		}
	} // end if !$bErrorFlag
} elseif (isset($_POST["MatchFamily"]) or isset($_POST["MatchEnvelope"]) or isset($_POST["SetDefaultCheck"]) or isset($_POST["TotalAmount"])) {

	//$iCheckNo = 0;
	// Take care of match-family first- select the family based on the scanned check
	if ($bUseScannedChecks and isset($_POST["MatchFamily"])) {
		$tScanString = FilterInput($_POST["ScanInput"]);

		$routeAndAccount = $micrObj->FindRouteAndAccount ($tScanString); // use routing and account number for matching

    	if ($routeAndAccount) {
		   $sSQL = "SELECT fam_ID FROM family_fam WHERE fam_scanCheck=\"" . $routeAndAccount . "\"";
		   $rsFam = RunQuery($sSQL);
		   extract(mysqli_fetch_array($rsFam));
		   $iFamily = $fam_ID;

		   $iCheckNo = $micrObj->FindCheckNo ($tScanString);
      	} else {
		   $iFamily = FilterInput($_POST["FamilyID"],'int');
		   $iCheckNo = FilterInput($_POST["CheckNo"], 'int');
    	}
	} elseif (isset($_POST["MatchEnvelope"])) {
		// Match envelope is similar to match check- use the envelope number to choose a family
		
		$iEnvelope = FilterInput($_POST["Envelope"], 'int');
		if ($iEnvelope and strlen($iEnvelope) > 0) {
			$sSQL = "SELECT fam_ID FROM family_fam WHERE fam_Envelope=" . $iEnvelope;
			$rsFam = RunQuery($sSQL);
			$numRows = mysqli_num_rows($rsFam);
			if ($numRows) {
				extract(mysqli_fetch_array($rsFam));
				$iFamily = $fam_ID;
			}
		}
	} elseif (isset ($_POST["SplitTotal"])) { // split total button pressed
		$sSQL = "SELECT plg_fundID, plg_amount from pledge_plg where plg_famID=\"" . $iFamily . "\" AND plg_PledgeOrPayment=\"Pledge\" AND plg_FYID=\"" . $iFYID . "\";";
//echo "sSQL: " . $sSQL . "\n";
		$rsPledge = RunQuery($sSQL);
		$totalPledgeAmount = 0;
		while ($row = mysqli_fetch_array($rsPledge)) {
			$fundID = $row["plg_fundID"];
			$plgAmount = $row["plg_amount"];
			$fundID2Pledge[$fundID] = $plgAmount;
			$totalPledgeAmount = $totalPledgeAmount + $plgAmount;
		} // end while
		if ($fundID2Pledge) {
			// division rounding can cause total of calculations to not equal total.  Keep track of running total, and asssign any rounding error to 'default' fund
			$calcTotal = 0;
			$calcOtherFunds = 0;
			foreach ($fundID2Pledge as $fundID => $plgAmount) {
				$calcAmount = round($iTotalAmount * ($plgAmount / $totalPledgeAmount), 2);

				$nAmount[$fundID] = number_format($calcAmount, 2, ".", "");
				if ($fundID <> $defaultFundID) {
					$calcOtherFunds = $calcOtherFunds + $calcAmount;
				}

				$calcTotal += $calcAmount;
			}
			if ($calcTotal <> $iTotalAmount) {
				$nAmount[$defaultFundID] = number_format($iTotalAmount - $calcOtherFunds, 2, ".", "");
			}
		} else {
			$nAmount[$defaultFundID] = number_format($iTotalAmount, 2, ".", "");
		}
	} elseif (!$iSelectedFund) { // We have a total amount set and fund set to split
		if ($iOriginalSelectedFund) { // put all in the originally assigned fund if there was one
			$nAmount[$iOriginalSelectedFund] = number_format($iTotalAmount, 2, ".", "");
			$sComment[$iOriginalSelectedFund] = $sOneComment;
			$iSelectedFund = $iOriginalSelectedFund;
		}
	} else {
		$iFamily = FilterInput($_POST["FamilyID"]);
		$iCheckNo = FilterInput($_POST["CheckNo"], 'int');
	}

	// Handle special buttons at the bottom of the form.
	if (isset($_POST["SetDefaultCheck"])) {
		$tScanString = FilterInput($_POST["ScanInput"]);
		$routeAndAccount = $micrObj->FindRouteAndAccount ($tScanString); // use routing and account number for matching
		$iFamily = FilterInput($_POST["FamilyID"],'int');
		$sSQL = "UPDATE family_fam SET fam_scanCheck=\"" . $routeAndAccount . "\" WHERE fam_ID = " . $iFamily;
		RunQuery($sSQL);
	}
}

// Set Current Deposit setting for user
if ($iCurrentDeposit) {
	$sSQL = "UPDATE user_usr SET usr_currentDeposit = '$iCurrentDeposit' WHERE usr_per_id = \"".$_SESSION['iUserID']."\"";
	$rsUpdate = RunQuery($sSQL);
}

//Set the page title
if ($PledgeOrPayment == 'Pledge') {
	$sPageTitle = gettext("Pledge Editor");
} elseif ($iCurrentDeposit) {
	$sPageTitle = gettext("Payment Editor: ") . $dep_Type . gettext(" Deposit Slip #") . $iCurrentDeposit . " ($dep_Date)";

	// form assumed by Reports/PrintDeposit.php. 
	$checksFit = $iChecksPerDepositForm;

	$sSQL = "SELECT plg_FamID, plg_plgID, plg_checkNo, plg_method from pledge_plg where plg_method=\"CHECK\" and plg_depID=" . $iCurrentDeposit;
	$rsChecksThisDep = RunQuery ($sSQL);
	$depositCount = 0;
	while ($aRow = mysqli_fetch_array($rsChecksThisDep)) {
		extract($aRow);
		$chkKey = $plg_FamID . "|" . $plg_checkNo;
		if ($plg_method=='CHECK' and (!array_key_exists($chkKey, $checkHash))) {
			$checkHash[$chkKey] = $plg_plgID;
			++$depositCount;
		}
	}

	//$checkCount = mysql_num_rows ($rsChecksThisDep);
	$roomForDeposits = $checksFit - $depositCount;
	if ($roomForDeposits <= 0)
		$sPageTitle .= "<font color=red>";
	$sPageTitle .= " (" . $roomForDeposits . gettext (" more entries will fit.") . ")";
	if ($roomForDeposits <= 0)
		$sPageTitle .= "</font>";
} else { // not a plege and a current deposit hasn't been created yet
	if ($sGroupKey) {
		$sPageTitle = gettext("Payment Editor - Modify Existing Payment");
	} else {
		$sPageTitle = gettext("Payment Editor - New Deposit Slip Will Be Created");
	}
} // end if $PledgeOrPayment

if ($dep_Closed && $sGroupKey && $PledgeOrPayment == 'Payment') {
	$sPageTitle .= " &nbsp; <font color=red>Deposit closed</font>";
}			

//$familySelectHtml = buildFamilySelect($iFamily, $sDirRoleHead, $sDirRoleSpouse);
$sFamilyName = "";
if ($iFamily) {
    $sSQL = "SELECT fam_Name, fam_Address1, fam_City, fam_State FROM family_fam WHERE fam_ID =" . $iFamily;
    $rsFindFam = RunQuery($sSQL);
    while ($aRow = mysqli_fetch_array($rsFindFam))
    {
        extract($aRow);
        $sFamilyName = $fam_Name . " " . FormatAddressLine($fam_Address1, $fam_City, $fam_State);
    }
}

require "Include/Header.php";

?>
<form method="post" action="PledgeEditor.php?<?php echo "CurrentDeposit=" . $iCurrentDeposit . "&GroupKey=" . $sGroupKey . "&PledgeOrPayment=" . $PledgeOrPayment. "&linkBack=" . $linkBack; ?>" name="PledgeEditor">

<input type="hidden" name="FamilyID" id="FamilyID" value="<?php echo $iFamily; ?>">
<input type="hidden" name="PledgeOrPayment" id="PledgeOrPayment" value="<?php echo $PledgeOrPayment; ?>">

<table cellpadding="2" align="center">
	<tr>
		<td align="left">
		<?php if (!$dep_Closed) { ?>
			<input type="submit" class="icButton" value="<?php echo gettext("Save"); ?>" name="PledgeSubmit">
			<?php if ($_SESSION['bAddRecords']) { echo "<input type=\"submit\" class=\"icButton\" value=\"" . gettext("Save and Add") . "\" name=\"PledgeSubmitAndAdd\">"; } ?>
		<?php } ?>
			<?php if (!$dep_Closed) {
				$cancelText = "Cancel";
			} else {
				$cancelText = "Return";
			} ?>	
			<input type="button" class="icButton" value="<?php echo gettext($cancelText); ?>" name="PledgeCancel" onclick="javascript:document.location='<?php if (strlen($linkBack) > 0) { echo $linkBack; } else {echo "Menu.php"; } ?>';">
		</td>
	</tr>

	<tr>
		<td>
		<table border="0" cellspacing="0" cellpadding="2">
		<td valign="top" align="left">
		<table cellpadding="2">
			<?php if ($dep_Type == 'Bank' and $bUseDonationEnvelopes) {?>
			<tr>
				<td class="PaymentLabelColumn"><?php echo gettext("Envelope #"); ?></td>
				<td class="TextColumn"><input type="text" name="Envelope" size=8 id="Envelope" value="<?php echo $iEnvelope; ?>">
				<?php if (!$dep_Closed) { ?>
				<input type="submit" class="icButton" value="<?php echo gettext("Find family->"); ?>" name="MatchEnvelope">
				<?php } ?>
			</td>
			</tr>
			<?php } ?>
			<tr>
				<?php if ($PledgeOrPayment=='Pledge') { ?>
					<td <?php if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">"; ?><?php echo gettext("Payment Schedule"); ?></td>
					<td class="TextColumnWithBottomBorder">
						<select name="Schedule">
							<option value="0"><?php echo gettext("Select Schedule"); ?></option>
							<option value="Weekly" <?php if ($iSchedule == "Weekly") { echo "selected"; } ?>><?php echo gettext("Weekly"); ?></option>
							<option value="Monthly" <?php if ($iSchedule == "Monthly") { echo "selected"; } ?>><?php echo gettext("Monthly"); ?></option>
							<option value="Quarterly" <?php if ($iSchedule == "Quarterly") { echo "selected"; } ?>><?php echo gettext("Quarterly"); ?></option>
							<option value="Once" <?php if ($iSchedule == "Once") { echo "selected"; } ?>><?php echo gettext("Once"); ?></option>
							<option value="Other" <?php if ($iSchedule == "Other") { echo "selected"; } ?>><?php echo gettext("Other"); ?></option>
						</select>
					</td>
				<?php }?>

			</tr>
			<tr>
				<td <?php if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">"; ?><?php echo gettext("Payment by"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="Method">
						<?php if ($PledgeOrPayment=='Pledge' or $dep_Type == "Bank" or !$iCurrentDeposit) { ?>
						<option value="CHECK" <?php if ($iMethod == "CHECK") { echo "selected"; } ?>><?php echo gettext("CHECK"); 						?></option>
						<option value="CASH" <?php if ($iMethod == "CASH") { echo "selected"; } ?>><?php echo gettext("CASH"); 						?></option>
						<?php } ?>
						<?php if ($PledgeOrPayment=='Pledge' or $dep_Type == "CreditCard" or !$iCurrentDeposit) { ?>
						<option value="CREDITCARD" <?php if ($iMethod == "CREDITCARD") { echo "selected"; } ?>><?php echo 						gettext("Credit Card"); ?></option>
						<?php } ?>
						<?php if ($PledgeOrPayment=='Pledge' or $dep_Type == "BankDraft" or !$iCurrentDeposit) { ?>
						<option value="BANKDRAFT" <?php if ($iMethod == "BANKDRAFT") { echo "selected"; } ?>><?php echo 						gettext("Bank Draft"); ?></option>
						<?php } ?>
                                                <?php if ($PledgeOrPayment=='Pledge') { ?>
                                                <option value="EGIVE" <?php if ($iMethod == "EGIVE") { echo "selected"; } ?>><?php echo
                          gettext("eGive"); ?></option>
                                                <?php } ?>
					</select>
				</td>
			</tr>

			<tr>
				<td <?php if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">"; ?><?php echo gettext("Fiscal Year"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<?php PrintFYIDSelect ($iFYID, "FYID") ?>
				</td>
			</tr>
			<tr>
				<td <?php if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">"; echo gettext("Fund"); ?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="FundSplit">
						<option value=0 <?php if (!$iSelectedFund) { echo ' selected'; } ?>><?php echo gettext("Split");?></option>
						<?php foreach ($fundId2Name as $fun_id => $fun_name) {
							echo "<option value=\"" . $fun_id . "\""; if ($iSelectedFund==$fun_id) echo " selected"; echo ">"; echo gettext($fun_name) . "</option>";
						} ?>
					</select>
					<?php if (!$dep_Closed) { ?>
					<input type="submit" class="icButton" name="SetFundTypeSelection" value="<-Set">
					<?php } ?>
				</td>
			</tr>
			<tr>
			<?php if ($iSelectedFund) { ?>
				<td valign="top" align="left" <?php  if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">"; echo gettext("Comment"); ?></td>
				<td <?php echo "class=\"TextColumnWithBottomBorder\">"; echo "<input type=\"text\" name=\"OneComment\" id=\"OneComment\" value=\" ". $sComment[$iSelectedFund] . "\""; ?>">
			<?php }?>
			</tr>
		</table>
		</td>
		<td valign="top" align="center">
		<table cellpadding="2">
			<tr>
				<td <?php if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\""; else echo "class=\"PaymentLabelColumn\""; ?><?php addToolTip("Select the pledging family from the list."); ?>><?php echo gettext("Family"); ?></td>
				<td class="TextColumn">
                                             
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	$("#FamilyName").autocomplete({
		source: function (request, response)
		{
			$.ajax(
			{
				url:"AjaxFunctions.php?f=famlist_s",
				dataType: "json",
				data:
				{
					term: request.term
				},
				success: function (data)
				{
					response (data);
				},
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
//                    alert("Status: " + textStatus); alert("Error: " + errorThrown); 
				}
			});
		},
		minLength: 3,
		select: function(event,ui) {
			$('[name=FamilyName]').val(ui.item.value);
			$('[name=FamilyID]:eq(1)').val(ui.item.id);
		}
	});
});

</script>
					<input style='width:350px;' type="text" id="FamilyName" name="FamilyName" value='<?php echo $sFamilyName; ?>' />
					<input type="hidden" name="FamilyID" value='<?php echo $iFamily; ?>'>
					</select>
				</td>
			</tr>

			<?php if ($PledgeOrPayment=='Payment' and $dep_Type == 'Bank') {?>
				<tr>
					<td class="PaymentLabelColumn"><?php echo gettext("Check #"); ?></td>
					<td class="TextColumn"><input type="text" name="CheckNo" id="CheckNo" value="<?php echo $iCheckNo; ?>"><font color="red"><?php echo $sCheckNoError ?></font></td>
				</tr>
			<?php } ?>



			<tr>
				<td <?php if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\""; else echo "class=\"PaymentLabelColumn\""; ?><?php addToolTip("Format: YYYY-MM-DD<br>or enter the date by clicking on the calendar icon to the right."); ?>><?php echo gettext("Date"); ?></td>
<?php	if ($dDate=="")	$dDate = $dep_Date ?>
	
				<td class="TextColumn"><input type="text" name="Date" value="<?php echo $dDate; ?>" maxlength="10" id="sel1" size="11">&nbsp;<input type="image" onclick="return showCalendar('sel1', 'y-mm-dd');" src="Images/calendar.gif"> <span class="SmallText"><?php echo gettext("[format: YYYY-MM-DD]"); ?></span><font color="red"><?php echo $sDateError ?></font></td>
			</tr>



		<tr> 
			<td valign="top" align="left" <?php  if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">"; echo gettext("Total $"); ?></td>
			<td <?php echo "class=\"TextColumnWithBottomBorder\">"; echo "<input type=\"text\" name=\"TotalAmount\" id=\"TotalAmount\" value=\" ". $iTotalAmount . "\""; ?>">
		    <?php if ($PledgeOrPayment=='Payment') { ?>

				<?php if (!$iSelectedFund and !$dep_Closed) { ?>

				<input type="submit" class="icButton" value="<?php echo gettext("Split to Funds by pledge"); ?>" name="SplitTotal"></td>

			<?php } ?>

		<?php } ?>
		</tr>
			<td valign="top" align="left">

<?php
			if (($dep_Type == 'CreditCard') or ($dep_Type == 'BankDraft')) {
?>
			<tr>
				<td <?php  if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">";echo gettext("Choose online payment method");?></td>
				<td class="TextColumnWithBottomBorder">
					<select name="AutoPay">
<?php
					echo "<option value=0";
					if ($iAutID == 0)
						echo " selected";
					echo ">" . gettext ("Select online payment record") . "</option>\n";
					$sSQLTmp = "SELECT aut_ID, aut_CreditCard, aut_BankName, aut_Route, aut_Account FROM autopayment_aut WHERE aut_FamID=" . $iFamily;
					$rsFindAut = RunQuery($sSQLTmp);
					while ($aRow = mysqli_fetch_array($rsFindAut))
					{
						extract($aRow);
						if ($aut_CreditCard <> "") {
							$showStr = gettext ("Credit card ...") . substr ($aut_CreditCard, strlen ($aut_CreditCard) - 4, 4);
						} else {
							$showStr = gettext ("Bank account ") . $aut_BankName . " " . $aut_Route . " " . $aut_Account;
						}
						echo "<option value=" . $aut_ID;
						if ($iAutID == $aut_ID)
							echo " selected";
						echo ">" . $showStr . "</option>\n";
					}
?>
					</select>
				</td>
			</tr>
			<?php } ?>
		</td>
		</tr>
		</table>
		</td>

		<tr>
		<?php if ($bUseScannedChecks and ($dep_Type == 'Bank' or $PledgeOrPayment=='Pledge')) {?>
			<td <?php  if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\" align=\"center\">"; else echo "class=\"PaymentLabelColumn\" align=\"center\">";echo gettext("Scan check");?>
			<textarea name="ScanInput" rows="2" cols="70"><?php echo $tScanString?></textarea></td>
		<?php } ?>

			<td align="center">
			<?php if ($dep_Type == 'Bank' and $bUseScannedChecks) { ?>
				<input type="submit" class="icButton" value="<?php echo gettext("find family from check account #"); ?>" name="MatchFamily">
				<input type="submit" class="icButton" value="<?php echo gettext("Set default check account number for family"); ?>" name="SetDefaultCheck">
	        <?php } ?>
			</td>
		</tr>
	</tr>

		</table>
		</td>

		<?php if (!$iSelectedFund) { ?>

	<tr>
		<td valign="top" align="left">
		<table cellpadding="2">

			<tr>

				<td <?php if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">"; ?><?php echo gettext("Fund Name"); ?></td>
				<td <?php if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">"; ?><?php echo gettext("Amount"); ?></td>

				<?php if ($bEnableNonDeductible) {?>
					<td <?php if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">"; ?><?php echo gettext("Non-deductible amount"); ?></td>
				<?php }?>

				<td <?php if ($PledgeOrPayment=='Pledge') echo "class=\"LabelColumn\">"; else echo "class=\"PaymentLabelColumn\">"; ?><?php echo gettext("Comment"); ?></td>
             </tr>

			<?php foreach ($fundId2Name as $fun_id => $fun_name) {
				echo "<tr>";
				echo "<td class=\"TextColumn\"><b>" . $fun_name . "</b></td>";
				echo "<td class=\"TextColumn\"><input type=\"text\" name=\"" . $fun_id . "_Amount\" id=\"" . $fun_id . "_Amount\" value=\"" . $nAmount[$fun_id] . "\"><br><font color=\"red\">" . $sAmountError[$fun_id] . "</font></td>";
				if ($bEnableNonDeductible) {
					echo "<td class=\"TextColumn\"><input type=\"text\" name=\"" . $fun_id . "_NonDeductible\" id=\"" . $fun_id . "_Amount\" value=\"" . $nNonDeductible[$fun_id] . "\"><br><font color=\"red\">" . $sAmountError[$fun_id] . "</font></td>";
				}
				echo "<td class=\"TextColumn\"><input type=\"text\" size=40 name=\"" . $fun_id . "_Comment\" id=\"" . $fun_id . "_Comment\" value=\"" . $sComment[$fun_id] . "\"></td>";
				echo "</tr>";
			}
			?>
		</td>
		</table>
		</tr>
	<?php } ?>
</table>
</form>

<?php

require "Include/Footer.php";
?>
