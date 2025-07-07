<?php
    $sPageTitle = "Administration";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: How to Add New Users -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I add new Users?
        </div>
        <div class="card-body">
            Users can be added by clicking on "Add New User" under "Admin" in the drop-down menu. A list of all non-users will appear, and you can select the individual you wish to make a user. Select the rights and then click "Save".
        </div>
    </div>

    <!-- Section: User Rights -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What are the different rights available?
        </div>
        <div class="card-body">
            The Rights are as follows:
            <ul>
                <li><b>Add Records:</b> This right allows records to be entered.</li>
                <li><b>Edit Records:</b> This allows for records to be modified.</li>
                <li><b>Delete Records:</b> This allows for records to be deleted.</li>
                <li><b>Manage Properties and Classifications:</b> This allows for properties and classifications to be managed for the database.</li>
                <li><b>Manage Groups and Roles:</b> Groups can be added, edited, and deleted as well as roles edited with this option.</li>
                <li><b>Manage Donations and Finances:</b> Financial donations can be added, edited, and deleted with this option.</li>
                <li><b>View, Add, and Edit Notes:</b> Notes can be added, edited, and deleted with this option.</li>
                <li><b>Edit Self:</b> This allows editing of the user and family members only. This option allows users to maintain their own data, especially email addresses and phone numbers, which change frequently.</li>
                <li><b>Canvasser:</b> This allows editing of canvass data and operation of the canvass automation features.</li>
                <li><b>Admin:</b> This option automatically selects all previous options.</li>
            </ul>
        </div>
    </div>

    <!-- Section: How to Edit Users -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I edit Users?
        </div>
        <div class="card-body">
            Users can be edited by clicking on "Edit Users" under "Admin" in the drop-down menu. A list of users will appear, and you can select which individual you wish to edit. Clicking "Reset" will reset the password for the next logon. "Edit" allows the rights and style to be edited. "Delete" removes user rights from the individual.
        </div>
    </div>

    <!-- Section: Default Password -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is the default password assigned to new Users?
        </div>
        <div class="card-body">
            In the subfolder `Include`, the file `Config.php` contains a line that reads: `$sDefault_Pass = "password"`. The word in the quotations is the default password. This can be changed at any time by editing `Config.php`.
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
