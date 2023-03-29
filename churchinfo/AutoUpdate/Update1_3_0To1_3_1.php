<?php
$sVersion = '1.3.1';

// This syntax is what MySQL used to correct default dates of 0000-00-00
$sSQL = "ALTER TABLE `event_types` CHANGE `type_defrecurDOY` `type_defrecurDOY` DATE NOT NULL DEFAULT '2017-01-01'";

$sSQL = "INSERT INTO `version_ver` (`ver_version`, `ver_date`) VALUES ('".$sVersion."',NOW())";
RunQuery($sSQL, FALSE); // False means do not stop on error

$sError = MySQLError ();
$sSQL_Last = $sSQL;

?>
