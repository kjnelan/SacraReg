<?php
/*******************************************************************************
 *
 *  filename    : PersonView.php
 *  last change : 2024-09-12
 *  description : Displays all the information about a single person
 *
 *  Changes made by: Fr. Kenn Nelan
 *  - Updated to Bootstrap framework
 *  - Modernized layout
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Get the person ID from the querystring
$iPersonID = FilterInput($_GET["PersonID"], 'int');

// Various logic for group and volunteer opportunity assignments
$iRemoveVO = isset($_GET["RemoveVO"]) ? FilterInput($_GET["RemoveVO"], 'int') : 0;

if (isset($_POST["GroupAssign"]) && $_SESSION['bManageGroups']) {
    $iGroupID = FilterInput($_POST["GroupAssignID"], 'int');
    AddToGroup($iPersonID, $iGroupID, 0);
}

if (isset($_POST["VolunteerOpportunityAssign"]) && $_SESSION['bEditRecords']) {
    $volIDs = $_POST["VolunteerOpportunityIDs"];
    if ($volIDs) {
        foreach ($volIDs as $volID) {
            AddVolunteerOpportunity($iPersonID, $volID);
        }
    }
}

if ($iRemoveVO > 0 && $_SESSION['bEditRecords']) {
    RemoveVolunteerOpportunity($iPersonID, $iRemoveVO);
}

// Get person's information
$sSQL = "SELECT a.*, family_fam.*, cls.lst_OptionName AS sClassName, fmr.lst_OptionName AS sFamRole,
        b.per_FirstName AS EnteredFirstName, b.Per_LastName AS EnteredLastName,
        c.per_FirstName AS EditedFirstName, c.per_LastName AS EditedLastName
        FROM person_per a
        LEFT JOIN family_fam ON a.per_fam_ID = family_fam.fam_ID
        LEFT JOIN list_lst cls ON a.per_cls_ID = cls.lst_OptionID AND cls.lst_ID = 1
        LEFT JOIN list_lst fmr ON a.per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
        LEFT JOIN person_per b ON a.per_EnteredBy = b.per_ID
        LEFT JOIN person_per c ON a.per_EditedBy = c.per_ID
        WHERE a.per_ID = " . $iPersonID;

$rsPerson = RunQuery($sSQL);

// Check if the person query succeeded
if (!$rsPerson) {
    die("Error: Could not execute query to fetch person details. " . mysqli_error($cnInfoCentral));
}

extract(mysqli_fetch_array($rsPerson));

// Select between individual and family information
SelectWhichAddress($sAddress1, $sAddress2, $per_Address1, $per_Address2, $fam_Address1, $fam_Address2, True);
$sCity = SelectWhichInfo($per_City, $fam_City, True);
$sState = SelectWhichInfo($per_State, $fam_State, True);
$sZip = SelectWhichInfo($per_Zip, $fam_Zip, True);
$sCountry = SelectWhichInfo($per_Country, $fam_Country, True);
$sPhoneCountry = SelectWhichInfo($per_Country, $fam_Country, False);
$sHomePhone = SelectWhichInfo(ExpandPhoneNumber($per_HomePhone, $sPhoneCountry, $dummy), ExpandPhoneNumber($fam_HomePhone, $fam_Country, $dummy), True);
$sWorkPhone = SelectWhichInfo(ExpandPhoneNumber($per_WorkPhone, $sPhoneCountry, $dummy), ExpandPhoneNumber($fam_WorkPhone, $fam_Country, $dummy), True);
$sCellPhone = SelectWhichInfo(ExpandPhoneNumber($per_CellPhone, $sPhoneCountry, $dummy), ExpandPhoneNumber($fam_CellPhone, $fam_Country, $dummy), True);
$sEmail = SelectWhichInfo($per_Email, $fam_Email, True);
$sUnformattedEmail = SelectWhichInfo($per_Email, $fam_Email, False);

// Get assigned groups
$sSQL = "SELECT grp_ID, grp_Name, role.lst_OptionName AS roleName
         FROM group_grp
         LEFT JOIN person2group2role_p2g2r ON p2g2r_grp_ID = grp_ID
         LEFT JOIN list_lst role ON role.lst_OptionID = p2g2r_rle_ID
         WHERE p2g2r_per_ID = " . $iPersonID;

$rsAssignedGroups = RunQuery($sSQL);

// Check if the groups query succeeded
if (!$rsAssignedGroups) {
    die("Error: Could not execute query to fetch assigned groups. " . mysqli_error($cnInfoCentral));
}

// Get volunteer opportunities
$sSQL = "SELECT vol_ID, vol_Name, vol_Description
         FROM volunteeropportunity_vol
         LEFT JOIN person2volunteeropp_p2vo ON p2vo_vol_ID = vol_ID
         WHERE p2vo_per_ID = " . $iPersonID;

$rsAssignedVolunteerOpps = RunQuery($sSQL);

// Check if the volunteer opportunities query succeeded
if (!$rsAssignedVolunteerOpps) {
    die("Error: Could not execute query to fetch volunteer opportunities. " . mysqli_error($cnInfoCentral));
}

// Set the page title and include HTML header
$sPageTitle = gettext("Person View");
require "Include/Header.php";
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3><?php echo FormatFullName($per_Title, $per_FirstName, $per_MiddleName, $per_LastName, $per_Suffix, 0); ?></h3>
        </div>
        <div class="card-body">
            <!-- General Information Section -->
            <h5><?php echo gettext("General Information"); ?></h5>
            <table class="table table-striped">
                <tr>
                    <th><?php echo gettext("Gender:"); ?></th>
                    <td><?php echo ($per_Gender == 1) ? gettext("Male") : gettext("Female"); ?></td>
                </tr>
                <tr>
                    <th><?php echo gettext("Birthdate:"); ?></th>
                    <td><?php echo FormatBirthDate($per_BirthYear, $per_BirthMonth, $per_BirthDay, "-", $per_Flags); ?></td>
                </tr>
                <tr>
                    <th><?php echo gettext("Age:"); ?></th>
                    <td><?php PrintAge($per_BirthMonth, $per_BirthDay, $per_BirthYear, $per_Flags); ?></td>
                </tr>
                <tr>
                    <th><?php echo gettext("Membership Date:"); ?></th>
                    <td><?php echo FormatDate($per_MembershipDate, false); ?></td>
                </tr>
                <tr>
                    <th><?php echo gettext("Home Phone:"); ?></th>
                    <td><?php echo $sHomePhone; ?></td>
                </tr>
                <tr>
                    <th><?php echo gettext("Work Phone:"); ?></th>
                    <td><?php echo $sWorkPhone; ?></td>
                </tr>
                <tr>
                    <th><?php echo gettext("Cell Phone:"); ?></th>
                    <td><?php echo $sCellPhone; ?></td>
                </tr>
                <tr>
                    <th><?php echo gettext("Email:"); ?></th>
                    <td><?php if ($sEmail != "") { echo "<a href=\"mailto:" . $sUnformattedEmail . "\">" . $sEmail . "</a>"; } ?></td>
                </tr>
                <tr>
                    <th><?php echo gettext("Address:"); ?></th>
                    <td>
                        <?php
                        echo $sAddress1 . "<br>";
                        if ($sAddress2 != "") echo $sAddress2 . "<br>";
                        echo $sCity . ", " . $sState . " " . $sZip . "<br>";
                        if ($sCountry != "") echo $sCountry;
                        ?>
                    </td>
                </tr>
            </table>

            <!-- Family Information -->
            <h5><?php echo gettext("Family Information"); ?></h5>
            <?php if ($fam_ID != "") { ?>
                <p><?php echo gettext("Family:") . " <a href=\"FamilyView.php?FamilyID=" . $fam_ID . "\">" . $fam_Name . "</a> (" . $sFamRole . ")"; ?></p>
            <?php } else { ?>
                <p><?php echo gettext("No assigned family."); ?></p>
            <?php } ?>

            <!-- Assigned Groups -->
            <h5><?php echo gettext("Assigned Groups"); ?></h5>
            <?php
            if (mysqli_num_rows($rsAssignedGroups) > 0) {
                echo "<table class=\"table table-striped\">";
                echo "<thead><tr><th>" . gettext("Group Name") . "</th><th>" . gettext("Role") . "</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($rsAssignedGroups)) {
                    echo "<tr><td><a href=\"GroupView.php?GroupID=" . $row['grp_ID'] . "\">" . $row['grp_Name'] . "</a></td>";
                    echo "<td>" . $row['roleName'] . "</td></tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>" . gettext("No group assignments.") . "</p>";
            }
            ?>

            <!-- Volunteer Opportunities -->
            <h5><?php echo gettext("Volunteer Opportunities"); ?></h5>
            <?php
            if (mysqli_num_rows($rsAssignedVolunteerOpps) > 0) {
                echo "<table class=\"table table-striped\">";
                echo "<thead><tr><th>" . gettext("Opportunity Name") . "</th><th>" . gettext("Description") . "</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($rsAssignedVolunteerOpps)) {
                    echo "<tr><td>" . $row['vol_Name'] . "</td>";
                    echo "<td>" . $row['vol_Description'] . "</td></tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>" . gettext("No volunteer opportunities.") . "</p>";
            }
            ?>

            <!-- Custom Fields -->
            <h5><?php echo gettext("Custom Fields"); ?></h5>
            <table class="table table-striped">
                <?php
                // Display the left-side custom fields
                while ($Row = mysqli_fetch_array($rsLeftCustomFields)) {
                    extract($Row);
                    if (($aSecurityType[$custom_FieldSec] == 'bAll') || ($_SESSION[$aSecurityType[$custom_FieldSec]])) {
                        $currentData = isset($aCustomData[$custom_Field]) ? trim($aCustomData[$custom_Field]) : '';
                        echo "<tr><th>" . $custom_Name . "</th><td>" . nl2br(displayCustomField($type_ID, $currentData, $custom_Special)) . "</td></tr>";
                    }
                }
                ?>
            </table>

            <!-- Properties -->
            <h5><?php echo gettext("Assigned Properties"); ?></h5>
            <?php
            if (mysqli_num_rows($rsAssignedProperties) > 0) {
                echo "<table class=\"table table-striped\">";
                echo "<thead><tr><th>" . gettext("Property Type") . "</th><th>" . gettext("Property Name") . "</th><th>" . gettext("Value") . "</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($rsAssignedProperties)) {
                    echo "<tr><td>" . $row['prt_Name'] . "</td>";
                    echo "<td>" . $row['pro_Name'] . "</td>";
                    echo "<td>" . $row['r2p_Value'] . "</td></tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>" . gettext("No assigned properties.") . "</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php
require "Include/Footer.php";
?>
