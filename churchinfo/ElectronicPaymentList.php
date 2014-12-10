<?php
/*******************************************************************************
 *
 *  filename    : ElectronicPaymentLIst.php
 *  last change : 2014-11-29
 *  description : displays a list of all automatic payment records
 *
 *  http://www.churchdb.org/
 *  Copyright 2014 Michael Wilt
 *
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Security: User must be an Admin to access this page.
// Otherwise, re-direct them to the main menu.
if (!$_SESSION['bAdmin'])
{
	Redirect("Menu.php");
	exit;
}

// Get all the electronic payment records
$sSQL = "SELECT * FROM autopayment_aut INNER JOIN family_fam ON autopayment_aut.aut_FamID = family_fam.fam_ID LEFT JOIN donationfund_fun ON autopayment_aut.aut_Fund=donationfund_fun.fun_ID ORDER BY fam_Name";
$rsAutopayments = RunQuery($sSQL);

// Set the page title and include HTML header
$sPageTitle = gettext("Electronic Payment Listing");
require "Include/Header.php";
?>

<?php if ($sElectronicTransactionProcessor == "Vanco") { ?>
<script>
function CreatePaymentMethodsForChecked()
{
	checkboxes = document.getElementsByName("SelectForAction");
	for(var i=0, n=checkboxes.length;i<n;i++) {
	    if (checkboxes[i].checked) {
		    var id = checkboxes[i].id.split("Select")[1];

		    var xmlhttp;
			xmlhttp=new XMLHttpRequest();
		    xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		            document.getElementById('p1').innerHTML=xmlhttp.getAllResponseHeaders();
	            }
		    };
		    xmlhttp.open("GET","https://svr.uunashua.org/churchinfo/ConvertOnePaymentXML.php?autid="+id,true);
		    xmlhttp.send();
	    }
	}
}
</script>
<?php } ?>

<script>
function toggle(source, groupName) {
	  checkboxes = document.getElementsByName(groupName);
	  for(var i=0, n=checkboxes.length;i<n;i++) {
	    checkboxes[i].checked = source.checked;
  }
}
</script>

<p align="center"><a href="AutoPaymentEditor.php?linkBack=ElectronicPaymentList.php"><?php echo gettext("Add a New Electronic Payment Method"); ?></a></p>

<table cellpadding="4" align="center" cellspacing="0" width="100%">
	<tr class="TableHeader">
		<td>
		<input type=checkbox onclick="toggle(this, 'SelectForAction')" />
		</td>
		<td align="center"><b><?php echo gettext("Family"); ?></b></td>
		<td align="center"><b><?php echo gettext("Type"); ?></b></td>
		<td align="center"><b><?php echo gettext("Fiscal Year"); ?></b></td>
		<td align="center"><b><?php echo gettext("Next Date"); ?></b></td>
		<td align="center"><b><?php echo gettext("Amount"); ?></b></td>
		<td align="center"><b><?php echo gettext("Interval"); ?></b></td>
		<td align="center"><b><?php echo gettext("Fund"); ?></b></td>
		<td align="center"><b><?php echo gettext("Bank"); ?></b></td>
		<td align="center"><b><?php echo gettext("Routing"); ?></b></td>
		<td align="center"><b><?php echo gettext("Account"); ?></b></td>
		<td align="center"><b><?php echo gettext("Credit Card"); ?></b></td>
		<td align="center"><b><?php echo gettext("Month"); ?></b></td>
		<td align="center"><b><?php echo gettext("Year"); ?></b></td>
		<td><b><?php echo gettext("Edit"); ?></b></td>
		<td><b><?php echo gettext("Delete"); ?></b></td>
	</tr>
<?php

//Set the initial row color
$sRowClass = "RowColorA";

//Loop through the autopayment records
while ($aRow = mysql_fetch_array($rsAutopayments)) {

	extract($aRow);

	//Alternate the row color
	$sRowClass = AlternateRowStyle($sRowClass);

	//Display the row
?>
	<tr class="<?php echo $sRowClass; ?>">
		<td>
		<?php
			echo "<input type=checkbox id=Select$aut_ID name=SelectForAction />"; 
		?>
		</td>
		
		<td>
		<?php
			echo "<a href=\"FamilyView.php?FamilyID=" . $fam_ID . "\">" . $fam_Name . "</a>";
		?>
		</td>

		<td>
		<?php 
			if ($aut_EnableBankDraft) 
		        echo "Bank ACH";
		    elseif ($aut_EnableCreditCard)
		    	echo "Credit Card";
		    else
		    	echo "Disabled";
		?>
		</td>

		<td><?php echo MakeFYString ($aut_FYID);?></td>
		<td><?php echo $aut_NextPayDate;?></td>
		<td><?php echo $aut_Amount;?></td>
		<td><?php echo $aut_Interval;?></td>
		<td><?php echo $fun_Name;?></td>
		<td><?php echo $aut_BankName;?></td>
		<td><?php if (strlen($aut_Route)==9) echo "*****".substr($aut_Route,5,4);?></td>
		<td><?php if (strlen($aut_Account)>4) echo "*****".substr($aut_Account,strlen($aut_Account)-4,4);?></td>
		<td><?php if (strlen($aut_CreditCard)==16) echo "*************".substr($aut_CreditCard,12,4);?></td>
		<td><?php echo $aut_ExpMonth;?></td>
		<td><?php echo $aut_ExpYear;?></td>
		<td><a href="AutoPaymentEditor.php?AutID=<?php echo $aut_ID; ?>&amp;FamilyID=<?php echo $fam_ID?>&amp;linkBack=ElectronicPaymentList.php"><?php echo gettext("Edit"); ?></a></td>
		<td><a href="AutoPaymentDelete.php?AutID=<?php echo $aut_ID; ?>&amp;FamilyID=<?php echo $fam_ID?>&amp;linkBack=ElectronicPaymentList.php"><?php echo gettext("Delete"); ?></a></td>

	</tr>
	<?php
}
?>
</table>
<?php if ($sElectronicTransactionProcessor == "Vanco") { ?>
<input type="button" id="CreatePaymentMethodsForChecked" value="Store Private Data at Vanco" onclick="CreatePaymentMethodsForChecked();" />
<?php }?>

<?php
require "Include/Footer.php";
?>
