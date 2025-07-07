<?php
/*******************************************************************************
*
*  filename    : Include/Header.php
*  website     : http://www.churchdb.org
*  description : page header used for most pages
*
*  Copyright 2001-2004 Phillip Hullquist, Deane Barker, Chris Gebhardt, Michael Wilt
*
*  LICENSE:
*  (C) Free Software Foundation, Inc.
*
*  ChurchInfo is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful, but
*  WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
*  General Public License for more details.
*
*  http://www.gnu.org/licenses
*
*  This file best viewed in a text editor with tabs stops set to 4 characters
*
******************************************************************************/

// Fetch parish info from the config table
$sSQL = "SELECT cfg_name, cfg_value FROM config_cfg WHERE cfg_name IN ('sChurchName', 'sChurchAddress', 'sChurchCity', 'sChurchState', 'sChurchZip', 'sChurchPhone', 'sChurchEmail')";
$rsParishInfo = RunQuery($sSQL);

// Create an array to store the config values
$parishInfo = [];
while ($row = mysqli_fetch_assoc($rsParishInfo)) {
    $parishInfo[$row['cfg_name']] = $row['cfg_value'];
}

// Assign the values to variables with fallback defaults
$parishName    = $parishInfo['sChurchName'] ?? 'Your Parish Name';
$parishAddress = $parishInfo['sChurchAddress'] ?? '123 Parish Lane';
$parishCity    = $parishInfo['sChurchCity'] ?? 'City';
$parishState   = $parishInfo['sChurchState'] ?? 'State';
$parishZip     = $parishInfo['sChurchZip'] ?? 'ZIP';
$parishPhone   = $parishInfo['sChurchPhone'] ?? '(123) 456-7890';
$parishEmail   = $parishInfo['sChurchEmail'] ?? 'info@parish.org';

// Turn ON output buffering
ob_start();

require_once ('Header-function.php');

// Top level menu index counter
$MenuFirst = 1;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php Header_head_metatag(); ?>

<!-- Link to the new header styles -->
<link rel="stylesheet" type="text/css" href="<?php echo $sURLPath; ?>/Include/Style-header.css">

</head>
<body onload="javascript:scrollToCoordinates()">

<!-- Custom Header Bar -->
<div class="custom-header-bar">
    <div class="header-logo">
        <img src="<?php echo $sURLPath; ?>/Images/ParishLogo.png" alt="Parish Logo" height="80px">
    </div>
    <div class="header-info">
        <p class="parish-name"><?php echo htmlspecialchars($parishName); ?></p>
        <p class="parish-address"><?php echo htmlspecialchars($parishAddress . ', ' . $parishCity . ', ' . $parishState . ' ' . $parishZip); ?></p>
        <p class="parish-phone"><?php echo htmlspecialchars($parishPhone); ?></p>
        <p class="parish-email"><?php echo htmlspecialchars($parishEmail); ?></p>
    </div>
</div>

<?php
// Existing Menu Bar
Header_body_scripts();
if ($iNavMethod != 2) {
    Header_body_menu();
} else {
    Header_body_nomenu();
}
?>
</body>
</html>
