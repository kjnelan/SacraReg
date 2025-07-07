<?php
/*******************************************************************************
 *
 *  filename    : UserList.php
 *  last change : 2003-01-07
 *  description : displays a list of all users
 *
 *  Modified by Fr. Kenn Nelan on [date] to integrate Bootstrap and modernize the layout
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2002 Phillip Hullquist, Deane Barker
 *
 *  InfoCentral is free software; you can redistribute it and/or modify
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
if (!$_SESSION['bAdmin']) {
    Redirect("Menu.php");
    exit;
}

if (isset($_GET["ResetLoginCount"])) {
    $iResetLoginCount = FilterInput($_GET["ResetLoginCount"], 'int');
} else {
    $iResetLoginCount = 0;
}

if ($iResetLoginCount > 0) {
    $sSQL = "UPDATE user_usr SET usr_FailedLogins = 0 WHERE usr_per_ID = " . $iResetLoginCount;
    RunQuery($sSQL);
}

// Get all the User records
$sSQL = "SELECT * FROM user_usr INNER JOIN person_per ON user_usr.usr_per_ID = person_per.per_ID ORDER BY per_LastName";
$rsUsers = RunQuery($sSQL);

// Set the page title and include HTML header
$sPageTitle = gettext("User Listing");
require "Include/Header.php";
?>

<div class="container my-4">
    <div class="text-center mb-3">
        <a href="UserEditor.php" class="btn btn-primary"><?php echo gettext("Add a New User"); ?></a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th><?php echo gettext("Name"); ?></th>
                <th class="text-center"><?php echo gettext("Last Login"); ?></th>
                <th class="text-center"><?php echo gettext("Total Logins"); ?></th>
                <th class="text-center"><?php echo gettext("Failed Logins"); ?></th>
                <th class="text-center" colspan="2"><?php echo gettext("Password"); ?></th>
                <th><?php echo gettext("Edit"); ?></th>
                <th><?php echo gettext("Delete"); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Set the initial row color
            $sRowClass = "RowColorA";

            // Loop through the person recordset
            while ($aRow = mysqli_fetch_array($rsUsers)) {
                extract($aRow);

                // Alternate the row color
                $sRowClass = AlternateRowStyle($sRowClass);
            ?>
                <tr class="<?php echo $sRowClass; ?>">
                    <td>
                        <a href="PersonView.php?PersonID=<?php echo $per_ID; ?>"><?php echo FormatFullName($per_Title, $per_FirstName, $per_MiddleName, $per_LastName, $per_Suffix, 1); ?></a>
                    </td>
                    <td class="text-center"><?php echo $usr_LastLogin; ?></td>
                    <td class="text-center"><?php echo $usr_LoginCount; ?></td>
                    <td class="text-center">
                        <?php
                        if ($iMaxFailedLogins > 0 && $usr_FailedLogins >= $iMaxFailedLogins) {
                            echo '<span class="text-danger">' . $usr_FailedLogins . '</span><br>';
                            echo '<a href="UserList.php?ResetLoginCount=' . $per_ID . '" class="btn btn-sm btn-warning">' . gettext("Reset") . '</a>';
                        } else {
                            echo $usr_FailedLogins;
                        }
                        ?>
                    </td>
                    <td class="text-right"><a href="UserPasswordChange.php?PersonID=<?php echo $per_ID; ?>&FromUserList=True" class="btn btn-sm btn-secondary"><?php echo gettext("Change"); ?></a></td>
                    <td class="text-left">
                        <?php if ($per_ID != $_SESSION['iUserID']) { ?>
                            <a href="UserReset.php?PersonID=<?php echo $per_ID; ?>&FromUserList=True" class="btn btn-sm btn-secondary"><?php echo gettext("Reset"); ?></a>
                        <?php } else {
                            echo "&nbsp;";
                        } ?>
                    </td>
                    <td><a href="UserEditor.php?PersonID=<?php echo $per_ID; ?>" class="btn btn-sm btn-info"><?php echo gettext("Edit"); ?></a></td>
                    <td><a href="UserDelete.php?PersonID=<?php echo $per_ID; ?>" class="btn btn-sm btn-danger"><?php echo gettext("Delete"); ?></a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<?php
require "Include/Footer.php";
?>
