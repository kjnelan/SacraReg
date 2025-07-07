<?php
    $sPageTitle = "Custom Fields";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: What are Custom Fields? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What are Custom Fields?
        </div>
        <div class="card-body">
            <p>Custom Fields allow you to expand the functionality of ChurchInfo beyond the base information that can be stored by default. Custom fields allow you to personalize the database to meet your specific needs. Custom fields can be added to individuals and to groups. For individuals, you could have a custom field that shows an individual's mentor. For groups, you could have a start and stop date for a group of ushers.</p>
        </div>
    </div>

    <!-- Section: How do I assign Custom Fields? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I assign Custom Fields?
        </div>
        <div class="card-body">
            <p>The process for people and groups differs:</p>

            <h5>For People:</h5>
            <p>Click on <strong>"Edit Custom Person Fields"</strong> under <strong>"Admin"</strong> in the drop-down menu. To add a new field, select the type, name, and side (left or right) where it should appear. The name will appear in the shaded box on the Person View, and the side determines which column it shows up in.</p>

            <h5>For Groups:</h5>
            <p>Click on the group you want to add a custom field to, and click <strong>"Edit Group-Specific Properties Form"</strong>. If this link is not visible, the group may not have group-specific properties enabled. Click on <strong>"Edit this Group"</strong> and check the box for <strong>"Use group-specific properties?"</strong>. To add a new field, select the type, name, description, and click <strong>"Add new field"</strong>.</p>
        </div>
    </div>

    <!-- Section: What are the Types? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What are the Types?
        </div>
        <div class="card-body">
            <p>See the <a href="Help.php?page=Types">Types</a> help topic for more information.</p>
        </div>
    </div>

    <!-- Section: How do I edit a Custom Field? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I edit a Custom Field?
        </div>
        <div class="card-body">
            <h5>For People:</h5>
            <p>You can change the name, special option, and the side where it appears on Person View. Be sure to click <strong>"Save Changes"</strong> after making changes, or the updates will be lost. If the type needs to be changed, you'll need to create a new field and delete the old one. You can also use the up and down arrows to change the order of the fields, and delete a field by clicking the "X" on the left.</p>

            <h5>For Groups:</h5>
            <p>You can change the name, description, and Person View settings. After making changes, click <strong>"Save Changes"</strong> to avoid losing updates. Enabling Person View allows the property to be shown when viewing an individual in Person View. To change the type of a field, you'll need to create a new one and delete the existing one. The order of fields can be changed with the up and down arrows, and fields can be deleted by clicking the "X" on the left.</p>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
