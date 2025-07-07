<?php
/*******************************************************************************
*
*  filename    : SettingsIndividual.php
*  website     : http://www.churchdb.org
*  description : Page where users can modify their own settings 
*                   File copied from SettingsUser.php with minor edits.
*
*  Contributors:
*  2006 Ed Davis
*
*  Modified by Fr. Kenn Nelan on [date] to integrate Bootstrap and modernize the layout
*
*  Copyright Contributors
*
*  ChurchInfo is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  This file best viewed in a text editor with tabs stops set to 4 characters.
*  Please configure your editor to use soft tabs (4 spaces for a tab) instead
*  of hard tab characters.
*
******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

$iPersonID = $_SESSION['iUserID'];

// Save Settings
if (isset($_POST['save'])) {
    $new_value = $_POST['new_value'];
    $type = $_POST['type'];
    ksort($type);
    reset($type);
    while ($current_type = current($type)) {
        $id = key($type);
        // Filter Input
        if ($current_type == 'text' || $current_type == "textarea")
            $value = FilterInput($new_value[$id]);
        elseif ($current_type == 'number')
            $value = FilterInput($new_value[$id], "float");
        elseif ($current_type == 'date')
            $value = FilterInput($new_value[$id], "date");
        elseif ($current_type == 'boolean') {
            if ($new_value[$id] != "1")
                $value = "";
            else
                $value = "1";
        }

        // We can't update unless values already exist.
        $sSQL = "SELECT * FROM userconfig_ucfg WHERE ucfg_id=$id AND ucfg_per_id=$iPersonID";
        $bRowExists = TRUE;
        $iNumRows = mysqli_num_rows(RunQuery($sSQL));
        if ($iNumRows == 0) {
            $bRowExists = FALSE;
        }

        if (!$bRowExists) { // If Row does not exist, insert default values.
            $sSQL = "SELECT * FROM userconfig_ucfg WHERE ucfg_id=$id AND ucfg_per_id=0";
            $rsDefault = RunQuery($sSQL);
            $aDefaultRow = mysqli_fetch_row($rsDefault);
            if ($aDefaultRow) {
                list($ucfg_per_id, $ucfg_id, $ucfg_name, $ucfg_value, $ucfg_type, $ucfg_tooltip, $ucfg_permission) = $aDefaultRow;

                $sSQL = "INSERT INTO userconfig_ucfg VALUES ($iPersonID, $id, '$ucfg_name', '$ucfg_value', '$ucfg_type', '$ucfg_tooltip', $ucfg_permission, ' ')";
                $rsResult = RunQuery($sSQL);
            } else {
                echo "<br> Error: Software BUG 3216";
                exit;
            }
        }

        // Save new setting
        $sSQL = "UPDATE userconfig_ucfg SET ucfg_value='$value' WHERE ucfg_id=$id AND ucfg_per_id=$iPersonID";
        $rsUpdate = RunQuery($sSQL);
        next($type);
    }
}

// Set the page title and include HTML header
$sPageTitle = gettext("My User Settings");
require "Include/Header.php";

// Get settings
$sSQL = "SELECT * FROM userconfig_ucfg WHERE ucfg_per_id=" . $iPersonID . " ORDER BY ucfg_id";
$rsConfigs = RunQuery($sSQL);

// Start Bootstrap container
echo '<div class="container">';
echo '<h1 class="my-4">' . gettext("My User Settings") . '</h1>';
echo '<form method="post" action="SettingsIndividual.php">';
echo '<table class="table table-striped table-bordered">';
echo '<thead><tr><th>' . gettext("Variable name") . '</th><th>' . gettext("Current Value") . '</th><th>' . gettext("Notes") . '</th></tr></thead>';
echo '<tbody>';

$r = 1;
// List Individual Settings
while (list($ucfg_per_id, $ucfg_id, $ucfg_name, $ucfg_value, $ucfg_type, $ucfg_tooltip, $ucfg_permission) = mysqli_fetch_row($rsConfigs)) {

    if (!(($ucfg_permission == 'TRUE') || $_SESSION['bAdmin']))
        break; // Don't show rows that can't be changed

    // Cancel, Save Buttons every 13 rows
    if ($r == 13) {
        echo '<tr><td colspan="3" class="text-center">';
        echo '<button type="submit" class="btn btn-primary" name="save">' . gettext("Save Settings") . '</button>';
        echo ' <button type="submit" class="btn btn-secondary" name="cancel">' . gettext("Cancel") . '</button>';
        echo '</td></tr>';
        $r = 1;
    }

    // Variable Name & Type
    echo '<tr>';
    echo '<td>' . $ucfg_name . '<input type="hidden" name="type[' . $ucfg_id . ']" value="' . $ucfg_type . '"></td>';

    // Current Value
    if ($ucfg_type == 'text') {
        echo '<td><input type="text" class="form-control" name="new_value[' . $ucfg_id . ']" value="' . htmlspecialchars($ucfg_value, ENT_QUOTES) . '"></td>';
    } elseif ($ucfg_type == 'textarea') {
        echo '<td><textarea class="form-control" rows="4" name="new_value[' . $ucfg_id . ']">' . htmlspecialchars($ucfg_value, ENT_QUOTES) . '</textarea></td>';
    } elseif ($ucfg_type == 'number' || $ucfg_type == 'date') {
        echo '<td><input type="text" class="form-control" name="new_value[' . $ucfg_id . ']" value="' . $ucfg_value . '"></td>';
    } elseif ($ucfg_type == 'boolean') {
        $sel1 = $ucfg_value == '' ? 'selected' : '';
        $sel2 = $ucfg_value == '1' ? 'selected' : '';
        echo '<td><select class="form-control" name="new_value[' . $ucfg_id . ']">';
        echo '<option value="" ' . $sel1 . '>' . gettext("False") . '</option>';
        echo '<option value="1" ' . $sel2 . '>' . gettext("True") . '</option>';
        echo '</select></td>';
    }

    // Notes
    echo '<td>' . $ucfg_tooltip . '</td>';
    echo '</tr>';

    $r++;
}

// Cancel, Save Buttons
echo '<tr><td colspan="3" class="text-center">';
echo '<button type="submit" class="btn btn-primary" name="save">' . gettext("Save Settings") . '</button>';
echo ' <button type="submit" class="btn btn-secondary" name="cancel">' . gettext("Cancel") . '</button>';
echo '</td></tr>';
echo '</tbody></table></form></div>';

require "Include/Footer.php";
?>
