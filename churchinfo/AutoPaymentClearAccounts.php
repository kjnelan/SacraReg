<?php 
require "Include/Config.php";
require "Include/Functions.php";
require "Include/VancoConfig.php";

$iVancoAutID = FilterInput($_GET['customerid'],'int'); 

$sSQL = "UPDATE autopayment_aut SET ";
$sSQL .= "  aut_CreditCard=\"\"";
$sSQL .= ", aut_Account=\"\"";
$sSQL .= " WHERE aut_ID=$iVancoAutID";

$bSuccess = false;
if ($result = mysql_query($sSQL, $cnInfoCentral))
    $bSuccess = true;

$errStr = "";

if (! $bSuccess)
	$errStr = gettext("Cannot execute query.") . "<p>$sSQL<p>" . mysql_error();

header('Content-type: application/json');
echo json_encode(array('Success'=>$bSuccess, 'ErrStr'=>$errStr));
?>
