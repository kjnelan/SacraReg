<?php

include "Include/Config.php";

// Connecting, selecting database
$link = mysqli_connect($sSERVERNAME, $sUSER, $sPASSWORD, $sDATABASE)
    or die('Could not connect: ' . mysqli_error());

$reg_randomtag = $link->real_escape_string($_GET['reg_randomtag']);

$sSQL = "SELECT reg_confirmed FROM register_reg WHERE reg_confirmed=1 AND reg_randomtag=\"$reg_randomtag\"";
$result = $link->query ($sSQL);
if ($result->num_rows > 0) {
	printf ("Registration previously confirmed");
	mysqli_close($link);
	exit;
}

$sSQL = "UPDATE register_reg SET reg_confirmed=1 WHERE reg_randomtag=\"$reg_randomtag\"";

echo $sHeader;

if ($link->query ($sSQL) && $link->affected_rows==1) {
	printf ("Registration Confirmed");
} else {
	printf ("Registration Failed");
}

mysqli_close($link);
?>
