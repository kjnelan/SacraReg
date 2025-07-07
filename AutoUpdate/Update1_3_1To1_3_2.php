<?php
$sVersion = '1.3.2';

$sSQL = "INSERT INTO `version_ver` (`ver_version`, `ver_date`) VALUES ('".$sVersion."',NOW())";
RunQuery($sSQL, FALSE); // False means do not stop on error

$sError = MySQLError ();
$sSQL_Last = $sSQL;

?>
