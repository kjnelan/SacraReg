<?php
$sVersion = '1.3.1';

// This syntax is what MySQL used to correct default dates of 0000-00-00
$sSQL = "ALTER TABLE `event_types` CHANGE `type_defrecurDOY` `type_defrecurDOY` DATE NOT NULL DEFAULT '2017-01-01'";
RunQuery($sSQL, FALSE); // False means do not stop on error

$sSQL = "UPDATE `config_cfg` SET `cfg_value`='Include/fpdf185' WHERE `cfg_name`='sPDF_PATH'";
RunQuery($sSQL, FALSE); // False means do not stop on error

$sSQL = "INSERT INTO `version_ver` (`ver_version`, `ver_date`) VALUES ('".$sVersion."',NOW())";
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

$sError = MySQLError ();
$sSQL_Last = $sSQL;

?>
