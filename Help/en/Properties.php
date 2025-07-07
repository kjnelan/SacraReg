<?php
    $sPageTitle = "Properties";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: What is a Property? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is a Property?
        </div>
        <div class="card-body">
            <p>A Property is a label that can be applied to a Person, a Group, or a Family. Separate sets of Properties are defined for the three different record types, and new Properties can be created as needed. A record can be assigned an unlimited number of Properties.</p>
            <p>Additionally, Properties can have values which contain information related to that Property. For example, a Property for a Person record might be "Hospitalized." A person with this Property is currently in the hospital, and the value of this Property could contain the name of the hospital and the room number.</p>
        </div>
    </div>

    <!-- Section: How do I know what Properties have been assigned? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I know what Properties have been assigned?
        </div>
        <div class="card-body">
            <p>On the Person, Family, or Group View, you'll find a section called "Assigned Properties" which will list all the Properties assigned to that Person, Family, or Group along with the Property Values, if supported by that Property.</p>
        </div>
    </div>

    <!-- Section: How do I assign a Property to a Person/Family/Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I assign a Property to a Person/Family/Group?
        </div>
        <div class="card-body">
            <ol>
                <li>For a person or family, filter for the desired person and bring up the Person/Family View for that record. For a group, click on "List Groups" under "Groups" in the drop-down menu and select the desired group.</li>
                <li>Under the "Assigned Properties" section, there will be a drop-down list of all available Properties not currently assigned to that Person. Select the desired Property and press "Assign."</li>
                <li>If the Property supports a Property Value, you'll be prompted to enter the Value. Otherwise, the Property will automatically be assigned.</li>
            </ol>
        </div>
    </div>

    <!-- Section: How do I edit a Property Value assigned to a Person/Family/Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I edit a Property Value assigned to a Person/Family/Group?
        </div>
        <div class="card-body">
            <ol>
                <li>For a person or family, filter for the desired person and bring up the Person/Family View for that record. For a group, click on "List Groups" under "Groups" in the drop-down menu and select the desired group.</li>
                <li>Under the "Assigned Properties" section, find the Property you wish to edit. Click the "Edit" link (if not present, the Property does not support a value).</li>
                <li>On the resulting page, edit the Value and press "Update."</li>
            </ol>
        </div>
    </div>

    <!-- Section: How do I remove or un-assign a Property? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I remove or un-assign a Property from a Person/Family/Group?
        </div>
        <div class="card-body">
            <ol>
                <li>For a person or family, filter for the desired person and bring up the Person/Family View for that record. For a group, click on "List Groups" under "Groups" in the drop-down menu and select the desired group.</li>
                <li>Under the "Assigned Properties" section, find the Property you wish to remove. Click the "Remove" link.</li>
                <li>On the next screen, confirm the removal.</li>
            </ol>
        </div>
    </div>

    <!-- Section: How do I add a brand-new Property? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I add a brand-new Property that I can assign to a Person, Family, or Group?
        </div>
        <div class="card-body">
            <ol>
                <li>From the top menu, select "List Person Properties" under "Properties" for a person, "List Family Properties" for a family, or "List Group Properties" for a group.</li>
                <li>On the resulting screen, select "Add a New [Person/Family/Group] Property."</li>
                <li>Complete the form. If you'd like the Property to support a Value, enter a prompt (e.g., "Enter the hospital name and room number"). Leaving the prompt blank will disallow storing a Value with the Property.</li>
            </ol>
        </div>
    </div>

    <!-- Section: What is a Property Type? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is a Property Type?
        </div>
        <div class="card-body">
            <p>A Property Type is a method of organizing Properties into groups. A Property must be associated with a Property Type. For example, a Property Type of "Physical Status" might contain the Properties "Disabled," "Homebound," and "Hospitalized."</p>
        </div>
    </div>

    <!-- Section: How do I add a new Property Type? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I add a new Property Type?
        </div>
        <div class="card-body">
            <ol>
                <li>From the top menu, select "Property Types" under "Properties."</li>
                <li>On the resulting screen, select "Add a New Property Type."</li>
                <li>Complete the form and press "Save."</li>
            </ol>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
