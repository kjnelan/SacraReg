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
$iPersonID = FilterInput($_GET["PersonID"],'int');

$iRemoveVO = 0;
if (array_key_exists ("RemoveVO", $_GET))
	$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

if ( isset($_POST["GroupAssign"]) && $_SESSION['bManageGroups'] )
{
	$iGroupID = FilterInput($_POST["GroupAssignID"],'int');
	AddToGroup($iPersonID,$iGroupID,0);
}

if ( isset($_POST["VolunteerOpportunityAssign"]) && $_SESSION['bEditRecords'])
{
	$volIDs = $_POST["VolunteerOpportunityIDs"];
	if ($volIDs) {
		foreach ($volIDs as $volID) {
			AddVolunteerOpportunity($iPersonID, $volID);
		}
	}
}

// Service remove-volunteer-opportunity (these links set RemoveVO)
if ($iRemoveVO > 0  && $_SESSION['bEditRecords'])
{
	RemoveVolunteerOpportunity($iPersonID, $iRemoveVO);
}

$dSQL= "SELECT per_ID FROM person_per order by per_LastName, per_FirstName";
$dResults = RunQuery($dSQL);

$last_id = 0;
$next_id = 0;
$capture_next = 0;
$previous_id = 0;
while($myrow = mysqli_fetch_row($dResults))
{
	$pid = $myrow[0];
	if ($capture_next == 1)
	{
	    $next_id = $pid;
		break;
	}
	if ($pid == $iPersonID)
	{
		$previous_id = $last_id;
		$capture_next = 1;
	}
	$last_id = $pid;
}

if (($previous_id > 0)) {
    $previous_link_text = "<a class=\"SmallText\" href=\"PersonView.php?PersonID=$previous_id\">" . gettext("Previous Person") . "</a>";
}

$next_link_text = "";
if (($next_id > 0)) {
    $next_link_text = "<a class=\"SmallText\" href=\"PersonView.php?PersonID=$next_id\">" . gettext("Next Person") . "</a>";
}

// Get this person's data
$sSQL = "SELECT a.*, family_fam.*, cls.lst_OptionName AS sClassName, fmr.lst_OptionName AS sFamRole, b.per_FirstName AS EnteredFirstName,
				b.Per_LastName AS EnteredLastName, c.per_FirstName AS EditedFirstName, c.per_LastName AS EditedLastName
			FROM person_per a
			LEFT JOIN family_fam ON a.per_fam_ID = family_fam.fam_ID
			LEFT JOIN list_lst cls ON a.per_cls_ID = cls.lst_OptionID AND cls.lst_ID = 1
			LEFT JOIN list_lst fmr ON a.per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
			LEFT JOIN person_per b ON a.per_EnteredBy = b.per_ID
			LEFT JOIN person_per c ON a.per_EditedBy = c.per_ID
			WHERE a.per_ID = " . $iPersonID;
$rsPerson = RunQuery($sSQL);
extract(mysqli_fetch_array($rsPerson));

// Get the lists of custom person fields
$sSQL = "SELECT person_custom_master.* FROM person_custom_master
			WHERE custom_Side = 'left' ORDER BY custom_Order";
$rsLeftCustomFields = RunQuery($sSQL);

$sSQL = "SELECT person_custom_master.* FROM person_custom_master
			WHERE custom_Side = 'right' ORDER BY custom_Order";
$rsRightCustomFields = RunQuery($sSQL);

// Get the custom field data for this person.
$sSQL = "SELECT * FROM person_custom WHERE per_ID = " . $iPersonID;
$rsCustomData = RunQuery($sSQL);
$aCustomData = mysqli_fetch_array($rsCustomData,  MYSQLI_BOTH);

// Get the notes for this person
$sSQL = "SELECT nte_Private, nte_ID, nte_Text, nte_DateEntered, nte_EnteredBy, nte_DateLastEdited, nte_EditedBy, a.per_FirstName AS EnteredFirstName, a.Per_LastName AS EnteredLastName, b.per_FirstName AS EditedFirstName, b.per_LastName AS EditedLastName ";
$sSQL .= "FROM note_nte ";
$sSQL .= "LEFT JOIN person_per a ON nte_EnteredBy = a.per_ID ";
$sSQL .= "LEFT JOIN person_per b ON nte_EditedBy = b.per_ID ";
$sSQL .= "WHERE nte_per_ID = " . $iPersonID;

// Admins should see all notes, private or not.  Otherwise, only get notes marked non-private or private to the current user.
if (!$_SESSION['bAdmin'])
	$sSQL .= " AND (nte_Private = 0 OR nte_Private = " . $_SESSION['iUserID'] . ")";

$rsNotes = RunQuery($sSQL);

// Get the Groups this Person is assigned to
$sSQL = "SELECT grp_ID, grp_Name, grp_hasSpecialProps, role.lst_OptionName AS roleName
		FROM group_grp
		LEFT JOIN person2group2role_p2g2r ON p2g2r_grp_ID = grp_ID
		LEFT JOIN list_lst role ON lst_OptionID = p2g2r_rle_ID AND lst_ID = grp_RoleListID
		WHERE person2group2role_p2g2r.p2g2r_per_ID = " . $iPersonID . "
		ORDER BY grp_Name";
$rsAssignedGroups = RunQuery($sSQL);

// Get all the Groups
$sSQL = "SELECT grp_ID, grp_Name FROM group_grp ORDER BY grp_Name";
$rsGroups = RunQuery($sSQL);

// Get the volunteer opportunities this Person is assigned to
$sSQL = "SELECT vol_ID, vol_Name, vol_Description FROM volunteeropportunity_vol
		LEFT JOIN person2volunteeropp_p2vo ON p2vo_vol_ID = vol_ID
		WHERE person2volunteeropp_p2vo.p2vo_per_ID = " . $iPersonID . " ORDER by vol_Order";
$rsAssignedVolunteerOpps = RunQuery($sSQL);

// Get all the volunteer opportunities
$sSQL = "SELECT vol_ID, vol_Name FROM volunteeropportunity_vol ORDER BY vol_Order";
$rsVolunteerOpps = RunQuery($sSQL);

// Get the Properties assigned to this Person
$sSQL = "SELECT pro_Name, pro_ID, pro_Prompt, r2p_Value, prt_Name, pro_prt_ID
		FROM record2property_r2p
		LEFT JOIN property_pro ON pro_ID = r2p_pro_ID
		LEFT JOIN propertytype_prt ON propertytype_prt.prt_ID = property_pro.pro_prt_ID
		WHERE pro_Class = 'p' AND r2p_record_ID = " . $iPersonID .
		" ORDER BY prt_Name, pro_Name";
$rsAssignedProperties = RunQuery($sSQL);

// Get all the properties
$sSQL = "SELECT * FROM property_pro WHERE pro_Class = 'p' ORDER BY pro_Name";
$rsProperties = RunQuery($sSQL);

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysqli_fetch_array($rsSecurityGrp))
{
	extract ($aRow);
	$aSecurityType[$lst_OptionID] = $lst_OptionName;
}


$dBirthDate = FormatBirthDate($per_BirthYear, $per_BirthMonth, $per_BirthDay,"-",$per_Flags);

$sFamilyInfoBegin = "<span style=\"color: red;\">";
$sFamilyInfoEnd = "</span>";

// Assign the values locally, after selecting whether to display the family or person information

SelectWhichAddress($sAddress1, $sAddress2, $per_Address1, $per_Address2, $fam_Address1, $fam_Address2, True);
$sCity = SelectWhichInfo($per_City, $fam_City, True);
$sState = SelectWhichInfo($per_State, $fam_State, True);
$sZip = SelectWhichInfo($per_Zip, $fam_Zip, True);
$sCountry = SelectWhichInfo($per_Country, $fam_Country, True);
$sPhoneCountry = SelectWhichInfo($per_Country, $fam_Country, False);
$sHomePhone = SelectWhichInfo(ExpandPhoneNumber($per_HomePhone,$sPhoneCountry,$dummy), ExpandPhoneNumber($fam_HomePhone,$fam_Country,$dummy), True);
$sWorkPhone = SelectWhichInfo(ExpandPhoneNumber($per_WorkPhone,$sPhoneCountry,$dummy), ExpandPhoneNumber($fam_WorkPhone,$fam_Country,$dummy), True);
$sCellPhone = SelectWhichInfo(ExpandPhoneNumber($per_CellPhone,$sPhoneCountry,$dummy), ExpandPhoneNumber($fam_CellPhone,$fam_Country,$dummy), True);
$sEmail = SelectWhichInfo($per_Email, $fam_Email, True);
$sUnformattedEmail = SelectWhichInfo($per_Email, $fam_Email, False);

if ($per_Envelope > 0)
	$sEnvelope = $per_Envelope;
else
	$sEnvelope = gettext("Not assigned");

// Set the page title and include HTML header
$sPageTitle = gettext("Person View");
require "Include/Header.php";

// Set the permission to edit
$bOkToEdit = ($_SESSION['bEditRecords'] || 
              ($_SESSION['bEditSelf'] && $per_ID == $_SESSION['iUserID']) || 
              ($_SESSION['bEditSelf'] && $per_fam_ID == $_SESSION['iFamID']));

// Photo Management (Upload, Display, and Delete)
if (isset($_POST["UploadPhoto"]) && ($_SESSION['bAddRecords'] || $bOkToEdit)) {
    if ($_FILES['Photo']['name'] == "") {
        $PhotoError = gettext("No photo selected for uploading.");
    } elseif ($_FILES['Photo']['type'] != "image/pjpeg" && $_FILES['Photo']['type'] != "image/jpeg") {
        $PhotoError = gettext("Only jpeg photos can be uploaded.");
    } else {
        // Ensure file permissions for upload directories are correct
        if (!is_writable("Images/Person/") || !is_writable("Images/Person/thumbnails/")) {
            die("Error: Upload directories are not writable. Please check permissions.");
        }

        // Create the thumbnail used by PersonView
        chmod($_FILES['Photo']['tmp_name'], 0777);

        $srcImage = imagecreatefromjpeg($_FILES['Photo']['tmp_name']);
        $src_w = imagesx($srcImage);
        $src_h = imagesy($srcImage);

        // Calculate thumbnail's height and width (a "maxpect" algorithm)
        $dst_max_w = 200;
        $dst_max_h = 350;
        if ($src_w > $dst_max_w) {
            $thumb_w = $dst_max_w;
            $thumb_h = $src_h * ($dst_max_w / $src_w);
            if ($thumb_h > $dst_max_h) {
                $thumb_h = $dst_max_h;
                $thumb_w = $src_w * ($dst_max_h / $src_h);
            }
        } elseif ($src_h > $dst_max_h) {
            $thumb_h = $dst_max_h;
            $thumb_w = round($src_w * ($dst_max_h / $src_h));
            if ($thumb_w > $dst_max_w) {
                $thumb_w = $dst_max_w;
                $thumb_h = round($src_h * ($dst_max_w / $src_w));
            }
        } else {
            $thumb_w = $dst_max_w;
            $thumb_h = round($src_h * ($dst_max_w / $src_w));
        }

        // Create the thumbnail image and save it
        $dstImage = ImageCreateTrueColor($thumb_w, $thumb_h);
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h);
        imagejpeg($dstImage, "Images/Person/thumbnails/" . $iPersonID . ".jpg");
        imagedestroy($dstImage);
        imagedestroy($srcImage);

        // Move the uploaded full-size image to the correct directory
        move_uploaded_file($_FILES['Photo']['tmp_name'], "Images/Person/" . $iPersonID . ".jpg");
    }
} elseif (isset($_POST["DeletePhoto"]) && $_SESSION['bDeleteRecords']) {
    // Delete the person's photo if requested
    unlink("Images/Person/" . $iPersonID . ".jpg");
    unlink("Images/Person/thumbnails/" . $iPersonID . ".jpg");
}

?>

<div class="container">
    <div class="row">
        <!-- Photo Column -->
        <div class="col-md-3">
            <?php
            // Display photo or upload form
            $photoFile = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
            if (file_exists($photoFile)) {
                echo '<a target="_blank" href="Images/Person/' . $iPersonID . '.jpg">';
                echo '<img border="1" src="' . $photoFile . '" class="img-fluid"></a>';
                if ($bOkToEdit) {
                    echo '
                        <form method="post" action="PersonView.php?PersonID=' . $iPersonID . '">
                        <br>
                        <input type="submit" class="icTinyButton" value="' . gettext("Delete Photo") . '" name="DeletePhoto">
                        </form>';
                }
            } else {
                echo '<img border="0" src="Images/NoPhoto.png" class="img-fluid"><br><br><br>';
                if ($bOkToEdit) {
                    if (isset($PhotoError)) echo '<span style="color: red;">' . $PhotoError . '</span><br>';
                    echo '<form method="post" action="PersonView.php?PersonID=' . $iPersonID . '" enctype="multipart/form-data">
                        <input class="icTinyButton" type="file" name="Photo">
                        <input type="submit" class="icTinyButton" value="' . gettext("Upload Photo") . '" name="UploadPhoto">
                        </form>';
                }
            }
            ?>
            <hr class="my-4">
            <?php
            // Edit Link
            if ($bOkToEdit) {
                echo "<a class=\"SmallText\" href=\"PersonEditor.php?PersonID=" . $iPersonID . "\">" . gettext("Edit this Record") . "</a> | ";
            }
            if ($_SESSION['bDeleteRecords']) { 
                echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=person&PersonID=" . $per_ID . "\">" . gettext("Delete this Record") . "</a> | <br />"; 
            }
            ?>
            <a href="PrintView.php?PersonID=<?php echo $per_ID; ?>" class="SmallText"><?php echo gettext("Printable Page"); ?></a> | 
            <a href="PersonView.php?PersonID=<?php echo $per_ID; ?>&AddToPeopleCart=<?php echo $per_ID; ?>" class="SmallText"><?php echo gettext("Add to Cart"); ?></a>

            <hr class="my-4">
            <?php
            // Display the custom fields
            if (mysqli_num_rows($rsRightCustomFields) > 0) {
                echo '<h5>' . gettext("Custom Fields:") . '</h5>';
                echo '<table class="table table-bordered table-striped">';
                while ($Row = mysqli_fetch_array($rsRightCustomFields)) {
                    extract($Row);
                    echo '<tr>';
                    // Display the custom field name
                    echo '<td class="font-weight-bold">' . $custom_Name . '</td>';
                    
                    // Check if the custom field has data and display it
                    if (array_key_exists($custom_Field, $aCustomData) && (!is_null($aCustomData[$custom_Field]))) {
                        $currentData = trim($aCustomData[$custom_Field]);
                        $custom_Special = "";
                        if ($type_ID == 11) $custom_Special = $sPhoneCountry;
                        echo '<td>' . nl2br(displayCustomField($type_ID, $currentData, $custom_Special)) . '</td>';
                    } else {
                        // Display an empty cell if no data exists
                        echo '<td><div class="text-muted">No data available</div></td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No custom fields available.</p>';
            }
            ?>
            
    <?php
            // Get Next and Previous Person Navigation
            $dSQL = "SELECT per_ID FROM person_per ORDER BY per_LastName, per_FirstName";
            $dResults = RunQuery($dSQL);

            $last_id = 0;
            $next_id = 0;
            $capture_next = 0;
            $previous_id = 0;
            while ($myrow = mysqli_fetch_row($dResults)) {
                $pid = $myrow[0];
                if ($capture_next == 1) {
                    $next_id = $pid;
                    break;
                }
                if ($pid == $iPersonID) {
                    $previous_id = $last_id;
                    $capture_next = 1;
                }
                $last_id = $pid;
            }

            if (($previous_id > 0)) {
                echo "<a class=\"SmallText\" href=\"PersonView.php?PersonID=$previous_id\">" . gettext("Previous Person") . "</a> | ";
            }
            if (($next_id > 0)) {
                echo "<a class=\"SmallText\" href=\"PersonView.php?PersonID=$next_id\">" . gettext("Next Person") . "</a> | ";
            }

            // Assigned Groups Section
            $sSQL = "SELECT grp_ID, grp_Name, role.lst_OptionName AS roleName
                     FROM group_grp
                     LEFT JOIN person2group2role_p2g2r ON p2g2r_grp_ID = grp_ID
                     LEFT JOIN list_lst role ON role.lst_OptionID = p2g2r_rle_ID
                     WHERE p2g2r_per_ID = " . $iPersonID;
            $rsAssignedGroups = RunQuery($sSQL);
            ?>    
        </div>

        <!-- General Information Column -->
        <div class="col-md-9">
        <h5 class="font-weight-bold text-primary mb-3"><?php echo gettext("General Information:"); ?></h5>
            <p><span class="font-weight-bold"><?php echo gettext("Name:"); ?></span> <?php echo FormatFullName($per_Title, $per_FirstName, $per_MiddleName, $per_LastName, $per_Suffix, 0); ?></p>
            <p><span class="font-weight-bold"><?php echo gettext("Gender:"); ?></span> <?php echo ($per_Gender == 1) ? gettext("Male") : gettext("Female"); ?></p>
            <p><span class="font-weight-bold"><?php echo gettext("Birthdate:"); ?></span> <?php echo FormatBirthDate($per_BirthYear, $per_BirthMonth, $per_BirthDay, "-", $per_Flags); ?></p>
            <p><span class="font-weight-bold"><?php echo gettext("Membership Date:"); ?></span> <?php echo FormatDate($per_MembershipDate, false); ?></p>
            <p><span class="font-weight-bold"><?php echo gettext("Phone:"); ?></span> 
                <span class="<?php echo ($sHomePhone != $fam_HomePhone) ? '' : 'text-danger'; ?>">
                    <?php echo $sHomePhone; ?>
                </span>
            </p>
            <p><span class="font-weight-bold"><?php echo gettext("Cell Phone:"); ?></span> 
                <span class="<?php echo ($sCellPhone != $fam_CellPhone) ? '' : 'text-danger'; ?>">
                    <?php echo $sCellPhone; ?>
                </span>
            </p>
            <p><span class="font-weight-bold"><?php echo gettext("Email:"); ?></span> 
                <span class="<?php echo ($sEmail != $fam_Email) ? '' : 'text-danger'; ?>">
                    <a href="mailto:<?php echo $sUnformattedEmail; ?>"><?php echo $sEmail; ?></a>
                </span>
            </p>
            <p><span class="font-weight-bold"><?php echo gettext("Address:"); ?></span>
                <span class="<?php echo ($sAddress1 != $fam_Address1 || $sCity != $fam_City || $sState != $fam_State) ? '' : 'text-danger'; ?>">
                    <?php echo $sAddress1 . ' ' . $sCity . ', ' . $sState . ' ' . $sZip; ?>
                </span>
            </p>
            
            <?php if ($_SESSION['bNotes']) { ?>
            <p><b>
            <?php echo gettext("Notes:"); ?></b>
            </p>
            <p>
                <a class="SmallText" href="WhyCameEditor.php?PersonID=<?php echo $per_ID ?>"><?php echo gettext("Edit \"Why Came\" Notes"); ?></a></font>
                <br>
                <a class="SmallText" href="NoteEditor.php?PersonID=<?php echo $per_ID ?>"><?php echo gettext("Add a Note to this Record"); ?></a></font>
            </p>

            <?php

            //Loop through all the notes
            while($aRow = mysqli_fetch_array($rsNotes))
            {
                extract($aRow);
                ?>

                <p class="ShadedBox")>
                        <?php echo $nte_Text ?>
                </p>
                <span class="SmallText"><?php echo gettext("Entered:") . ' ' . FormatDate($nte_DateEntered,True) . ' ' . gettext("by") . ' ' . $EnteredFirstName . " " . $EnteredLastName ?></span>
                <br>
                <?php

                if (strlen($nte_DateLastEdited))
                { ?>
                
                <span class="SmallText"><?php echo gettext("Last Edited:") . ' ' . FormatDate($nte_DateLastEdited,True) . ' ' . gettext("by") . ' ' . $EditedFirstName . " " . $EditedLastName ?></span>
                <br>
                <?php
                }
                if ($_SESSION['bNotes']) { ?><a class="SmallText" href="NoteEditor.php?PersonID=<?php echo $iPersonID ?>&NoteID=<?php echo $nte_ID ?>"><?php echo gettext("Edit This Note"); ?></a>&nbsp;|&nbsp;<?php }
                if ($_SESSION['bNotes']) { ?><a class="SmallText" href="NoteDelete.php?NoteID=<?php echo $nte_ID ?>"><?php echo gettext("Delete This Note"); ?></a> <?php }
            }
            ?>
            <?php } ?>
        </div>
    </div>
    <hr class="my-4">
    <div class="row">
        <!-- Photo Column -->
        <div class="col-md-3"></div>
        <div class="col-md-9">
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

            // Volunteer Opportunities Section
            $sSQL = "SELECT vol_ID, vol_Name, vol_Description FROM volunteeropportunity_vol
                     LEFT JOIN person2volunteeropp_p2vo ON p2vo_vol_ID = vol_ID
                     WHERE p2vo_per_ID = " . $iPersonID;
            $rsAssignedVolunteerOpps = RunQuery($sSQL);
            ?>
            <hr class="my-4">
            <!-- Explanation for Red Text -->
            <div class="row">
                <div class="col-md-12">
                    <p class="text-danger">
                        <?php echo gettext("Red text indicates items inherited from the associated family record."); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require "Include/Footer.php";
?>
