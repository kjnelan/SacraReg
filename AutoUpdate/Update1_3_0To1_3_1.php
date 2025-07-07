<?php
$sVersion = '1.3.1';

// This syntax is what MySQL used to correct default dates of 0000-00-00
$sSQL = "ALTER TABLE `event_types` CHANGE `type_defrecurDOY` `type_defrecurDOY` DATE NOT NULL DEFAULT '2017-01-01'";
RunQuery($sSQL, FALSE); // False means do not stop on error

$sSQL = "UPDATE `config_cfg` SET `cfg_value`='Include/fpdf185' WHERE `cfg_name`='sPDF_PATH'";
RunQuery($sSQL, FALSE); // False means do not stop on error

// push the queries that incorporate a fiscal year forward
$sSQL = "UPDATE `queryparameteroptions_qpo` SET `qpo_Display` = '2024/2025', qpo_Value = '29' WHERE `queryparameteroptions_qpo`.`qpo_Display` = '2015/2016' ";
RunQuery($sSQL, FALSE);
    
$sSQL = "UPDATE `queryparameteroptions_qpo` SET `qpo_Display` = '2023/2024', qpo_Value = '28' WHERE `queryparameteroptions_qpo`.`qpo_Display` = '2014/2015' ";
RunQuery($sSQL, FALSE);

$sSQL = "UPDATE `queryparameteroptions_qpo` SET `qpo_Display` = '2022/2023', qpo_Value = '27' WHERE `queryparameteroptions_qpo`.`qpo_Display` = '2013/2014' ";
RunQuery($sSQL, FALSE);

$sSQL = "UPDATE `queryparameteroptions_qpo` SET `qpo_Display` = '2021/2022', qpo_Value = '26' WHERE `queryparameteroptions_qpo`.`qpo_Display` = '2012/2013' ";
RunQuery($sSQL, FALSE);

$sSQL = "ALTER TABLE person_per ALTER per_title SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_MiddleName SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_Suffix SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_Address1 SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_Address2 SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_City SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_State SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_Zip SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_Country SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_HomePhone SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_WorkPhone SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_CellPhone SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_Email SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_WorkEmail SET DEFAULT \"\"";
RunQuery($sSQL, FALSE);
$sSQL = "ALTER TABLE person_per ALTER per_BirthYear SET DEFAULT 0";
RunQuery($sSQL, FALSE);

$sSQL = "UPDATE person_per SET per_title = \"\" WHERE per_title IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_MiddleName = \"\" WHERE per_MiddleName IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_Suffix = \"\" WHERE per_Suffix IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_Address1 = \"\" WHERE per_Address1 IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_Address2 = \"\" WHERE per_Address2 IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_City = \"\" WHERE per_City IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_State = \"\" WHERE per_State IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_Zip = \"\" WHERE per_Zip IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_Country = \"\" WHERE per_Zip IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_HomePhone = \"\" WHERE per_HomePhone IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_WorkPhone = \"\" WHERE per_WorkPhone IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_CellPhone = \"\" WHERE per_CellPhone IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_Email = \"\" WHERE per_Email IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_WorkEmail = \"\" WHERE per_WorkEmail IS NULL";
RunQuery($sSQL, FALSE);
$sSQL = "UPDATE person_per SET per_BirthYear = 0 WHERE per_BirthYear IS NULL";
RunQuery($sSQL, FALSE);

$sSQL = "INSERT INTO `version_ver` (`ver_version`, `ver_date`) VALUES ('".$sVersion."',NOW())";
RunQuery($sSQL, FALSE); // False means do not stop on error

$sError = MySQLError ();
$sSQL_Last = $sSQL;

?>
