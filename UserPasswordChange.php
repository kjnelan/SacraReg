<?php
/*******************************************************************************
 *
 *  filename    : UserPasswordChange.php
 *  website     : http://www.churchdb.org
 *  copyright   : Copyright 2001, 2002 Deane Barker
 *                        Copyright 2004-2012 Michael Wilt
 *
 *  Modified by Fr. Kenn Nelan on [date] to integrate Bootstrap and modernize the layout
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
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
$bNoPasswordRedirect = true; // Subdue UserPasswordChange redirect to prevent looping
require "Include/Functions.php";

$bAdminOtherUser = false;
$bAdminOther = false;
$bError = false;
$sOldPasswordError = false;
$sNewPasswordError = false; 

// Get the PersonID out of the querystring if they are an admin user; otherwise, use session.
if ($_SESSION['bAdmin'] && isset($_GET["PersonID"])) {
    $iPersonID = FilterInput($_GET["PersonID"], 'int');
    if ($iPersonID != $_SESSION['iUserID'])
        $bAdminOtherUser = true;
} else {
    $iPersonID = $_SESSION['iUserID'];
}

// Was the form submitted?
if (isset($_POST["Submit"])) {
    // Assign all the stuff locally
    $sOldPassword = "";
    if (array_key_exists("OldPassword", $_POST))
        $sOldPassword = $_POST["OldPassword"];
    $sNewPassword1 = $_POST["NewPassword1"];
    $sNewPassword2 = $_POST["NewPassword2"];

    // Administrators can change other users' passwords without knowing the old ones.
    if ($bAdminOtherUser) {
        if (strlen($sNewPassword1) == 0 && strlen($sNewPassword2) == 0) {
            $sNewPasswordError = '<div class="text-danger">' . gettext("You must enter a password in both boxes") . '</div>';
            $bError = true;
        } elseif ($sNewPassword1 != $sNewPassword2) {
            $sNewPasswordError = '<div class="text-danger">' . gettext("You must enter the same password in both boxes") . '</div>';
            $bError = true;
        } else {
            // Update the user record with the password hash
            $tmp = $sNewPassword1 . $iPersonID;
            $sPasswordHashSha256 = hash("sha256", $tmp);

            $sSQL = "UPDATE user_usr SET usr_Password='" . $sPasswordHashSha256 . "', usr_NeedPasswordChange='0' WHERE usr_per_ID ='" . $iPersonID . "'";
            RunQuery($sSQL);

            // Redirect based on where the request came from
            if (array_key_exists("FromUserList", $_GET) && $_GET["FromUserList"] == "True") {
                Redirect("UserList.php");
            } else {
                Redirect("Menu.php");
            }
        }
    } else {
        // For non-admin users, verify the old password
        $sSQL = "SELECT * FROM user_usr, person_per WHERE per_ID = usr_per_ID AND usr_per_ID = " . $iPersonID;
        extract(mysqli_fetch_array(RunQuery($sSQL)));

        // Check the old password
        $tmp = $sOldPassword;
        $sPasswordHashMd5 = md5($tmp);
        $tmp = $sOldPassword . $usr_per_ID;
        $sPasswordHashSha256 = hash("sha256", $tmp);

        $bPasswordMatch = ($usr_Password == $sPasswordHashMd5 || $usr_Password == $sPasswordHashSha256);

        if (!$bPasswordMatch) {
            $sOldPasswordError = '<div class="text-danger">' . gettext("Invalid password") . '</div>';
            $bError = true;
        } elseif (strlen($sNewPassword1) == 0 || strlen($sNewPassword2) == 0) {
            $sNewPasswordError = '<div class="text-danger">' . gettext("You must enter your new password in both boxes") . '</div>';
            $bError = true;
        } elseif ($sNewPassword1 != $sNewPassword2) {
            $sNewPasswordError = '<div class="text-danger">' . gettext("You must enter the same password in both boxes") . '</div>';
            $bError = true;
        } elseif (strlen($sNewPassword1) < $sMinPasswordLength) {
            $sNewPasswordError = '<div class="text-danger">' . gettext("Your new password must be at least") . ' ' . $sMinPasswordLength . ' ' . gettext("characters in length.") . '</div>';
            $bError = true;
        } elseif ($sNewPassword1 == $sOldPassword) {
            $sNewPasswordError = '<div class="text-danger">' . gettext("You need to actually change your password.") . '</div>';
            $bError = true;
        }

        if (!$bError) {
            $tmp = $sNewPassword1 . $usr_per_ID;
            $sPasswordHashSha256 = hash("sha256", $tmp);
            $sSQL = "UPDATE user_usr SET usr_Password='" . $sPasswordHashSha256 . "', usr_NeedPasswordChange='0' WHERE usr_per_ID ='" . $iPersonID . "'";
            RunQuery($sSQL);

            $_SESSION['bNeedPasswordChange'] = false;

            if ($_GET["FromUserList"] == "True") {
                Redirect("UserList.php");
            } else {
                Redirect("Menu.php");
            }
        }
    }
} else {
    $sOldPassword = "";
    $sNewPassword1 = "";
    $sNewPassword2 = "";
}

// Set the page title and include HTML header
$sPageTitle = gettext("User Password Change");
require "Include/Header.php";

if ($_SESSION['bNeedPasswordChange']) {
    echo '<div class="alert alert-warning">' . gettext("Your account record indicates that you need to change your password before proceeding.") . '</div>';
}

if (!$bAdminOtherUser) {
    echo '<div class="alert alert-info">' . gettext("Enter your current password, then your new password twice. Passwords must be at least") . ' ' . $sMinPasswordLength . ' ' . gettext("characters in length.") . '</div>';
} else {
    echo '<div class="alert alert-info">' . gettext("Enter a new password for this user.") . '</div>';
}
?>

<div class="container">
    <form method="post" action="UserPasswordChange.php?<?php echo "PersonID=" . $iPersonID ?>&FromUserList=<?php echo (array_key_exists("FromUserList", $_GET) ? $_GET["FromUserList"] : ""); ?>">
        <div class="form-group">
            <?php if (!$bAdminOtherUser) { ?>
                <label for="OldPassword"><b><?php echo gettext("Old Password:"); ?></b></label>
                <input type="password" class="form-control" id="OldPassword" name="OldPassword" value="<?php echo $sOldPassword ?>">
                <?php echo $sOldPasswordError ?>
            <?php } ?>
        </div>
        <div class="form-group">
            <label for="NewPassword1"><b><?php echo gettext("New Password:"); ?></b></label>
            <input type="password" class="form-control" id="NewPassword1" name="NewPassword1" value="<?php echo $sNewPassword1 ?>">
        </div>
        <div class="form-group">
            <label for="NewPassword2"><b><?php echo gettext("Confirm New Password:"); ?></b></label>
            <input type="password" class="form-control" id="NewPassword2" name="NewPassword2" value="<?php echo $sNewPassword2 ?>">
            <?php echo $sNewPasswordError ?>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary" name="Submit"><?php echo gettext("Save"); ?></button>
        </div>
    </form>
</div>

<?php
require "Include/Footer.php";
?>
