<?php
    $sPageTitle = "Classifications";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: What are Classifications? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What are Classifications?
        </div>
        <div class="card-body">
            <p>Classifications allow you to place your people into specific classifications for record-keeping. For example, you can note if the person is a Guest, a Church Member, or a Sunday School Member.</p>
        </div>
    </div>

    <!-- Section: How do I change the Classifications? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I change the Classifications?
        </div>
        <div class="card-body">
            <p>Click on the link <strong>"Classification Manager"</strong> (under "People/Families") in the drop-down menu.</p>
            <p>To add a new classification, type in a new name and click "Add New Classification."</p>
            <p>To edit an existing classification, type in the new value in the relevant field. <strong>Note:</strong> Changes will be lost if you do not click "Save Changes" before using the "up," "down," "delete," or "add new" buttons!</p>
            <p>To delete a classification, click the "Delete" button next to the classification you wish to remove. To change the order, use the "up" or "down" buttons to adjust its position.</p>
        </div>
    </div>

    <!-- Section: How do I use Classifications? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I use Classifications?
        </div>
        <div class="card-body">
            <p>When exporting records, you can select which classification to include as part of the export process.</p>
        </div>
    </div>

    <!-- Section: How do I change an individual’s Classification? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I change an individual’s Classification?
        </div>
        <div class="card-body">
            <p>There are two ways to change a classification:</p>
            <ol>
                <li>You can change an entire family's classification by clicking on the "Assign a New Classification" link in the Family View. <strong>Note:</strong> This will change the classification for every family member, regardless of their previous role.</li>
                <li>You can change an individual’s classification by clicking on the "Classification" menu when editing their record, provided you have the necessary permissions.</li>
            </ol>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
