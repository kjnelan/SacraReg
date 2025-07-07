<?php
    $sPageTitle = "Groups";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: What is a Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is a Group?
        </div>
        <div class="card-body">
            <p>A Group is a collection of People, each occupying Roles within the Group. Groups can represent organizational, educational, and social constructs within the church.</p>
            <p>For example, a Group may be "Friday Night Bible Study." Roles within this group may include Leader, Assistant Leader, and Member. If 16 people are assigned to this group, 13 may occupy the Role of Member, 2 as Assistant Leaders, and 1 as Leader.</p>
        </div>
    </div>

    <!-- Section: How do I add a new Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I add a new Group?
        </div>
        <div class="card-body">
            <ol>
                <li>From the top menu, click on "Add a New Group" under "Groups."</li>
                <li>Complete the form.</li>
                <li>Press Save.</li>
            </ol>
            <p>When a new Group is created, a Role of "Member" is automatically created as the Default Role. You may immediately change the name of this role if desired.</p>
        </div>
    </div>

    <!-- Section: How do I change the Name/Description/Type of a Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I change the Name/Description/Type of a Group?
        </div>
        <div class="card-body">
            <ol>
                <li>From the top menu, select "List Groups" under "Groups."</li>
                <li>Click on the desired Group.</li>
                <li>Click on "Edit this group."</li>
                <li>Under the "Group Editor" section, update the desired information and press "Save."</li>
            </ol>
        </div>
    </div>

    <!-- Section: How do I add a new Role to a Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I add a new Role to a Group?
        </div>
        <div class="card-body">
            <ol>
                <li>From the top menu, select "List Groups" under "Groups."</li>
                <li>Click on the desired Group.</li>
                <li>Click on "Edit this group."</li>
                <li>Under "Group Roles," type the name of the new Role and press "Add."</li>
            </ol>
        </div>
    </div>

    <!-- Section: How do I change a Role in a Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I change a Role in a Group?
        </div>
        <div class="card-body">
            <ol>
                <li>From the top menu, select "List Groups" under "Groups."</li>
                <li>Click on the desired Group.</li>
                <li>Click on "Edit this group."</li>
                <li>Under "Group Roles," edit the Role name and press "Save Changes."</li>
            </ol>
        </div>
    </div>

    <!-- Section: What is the Default Role? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is the Default Role?
        </div>
        <div class="card-body">
            <p>The Default Role is the "standard" Role for a Member of that Group. For example, in a class, the Default Role might be "Student" since most people in the class will be students.</p>
            <p>When adding new members to a Group, they are assigned the Default Role unless otherwise specified.</p>
        </div>
    </div>

    <!-- Section: How do I change the Default Role for a Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I change the Default Role for a Group?
        </div>
        <div class="card-body">
            <ol>
                <li>From the top menu, select "List Groups" under "Groups."</li>
                <li>Click on the desired Group.</li>
                <li>Click on "Edit this group."</li>
                <li>Under "Group Roles," click "Make Default" next to the desired Role.</li>
            </ol>
        </div>
    </div>

    <!-- Section: What is Group Type? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is Group Type?
        </div>
        <div class="card-body">
            <p>Group Types allow you to categorize your groups. For example, a group called "Gleaners Class" can be assigned the type "Sunday School," and a group called "Franklin House" can be assigned the type "Cell Group."</p>
        </div>
    </div>

    <!-- Section: How do I set a Group Type? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I set a Group Type?
        </div>
        <div class="card-body">
            <p>When creating a new group, you are given the option to set the Group Type.</p>
        </div>
    </div>

    <!-- Section: How do I change the available Group Types? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I change the available Group Types?
        </div>
        <div class="card-body">
            <p>From the top menu, click on "Edit Group Types" under "Groups."</p>
        </div>
    </div>

    <!-- Section: What are Group-Specific Properties? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What are Group-Specific Properties?
        </div>
        <div class="card-body">
            <p>Group-Specific Properties allow you to add custom fields that are not built into ChurchInfo. For example, you can add a Mentor to a person or an additional date, such as a confirmation date.</p>
        </div>
    </div>

    <!-- Section: How do I use Group-Specific Properties? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I use Group-Specific Properties?
        </div>
        <div class="card-body">
            <p>See the <a href="HelpCustom.php">Custom Fields</a> help topic.</p>
        </div>
    </div>

    <!-- Section: How do I add People to a Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I add People to a Group?
        </div>
        <div class="card-body">
            <ol>
                <li>Add the desired people to your Cart.</li>
                <li>From the top menu, select "Empty Cart to Group" under "Cart."</li>
                <li>Select the desired Group and press "Add to Group."</li>
            </ol>
            <p>People already in the Group will not be added again, and new people will be added with the Default Role.</p>
        </div>
    </div>

    <!-- Section: How do I change the role of a Person in a Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I change the role of a Person in a Group?
        </div>
        <div class="card-body">
            <ol>
                <li>From the left menu, select "List Groups."</li>
                <li>Click on the desired Group.</li>
                <li>Click on "View Members."</li>
                <li>Find the desired Member and click "Change Role."</li>
                <li>Select the new Role from the drop-down list and press "Update."</li>
            </ol>
        </div>
    </div>

    <!-- Section: What is "Add Group Members to Cart"? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is "Add Group Members to Cart"?
        </div>
        <div class="card-body">
            <p>Adding group members to the cart allows you to easily manage individuals in the cart. In future releases, the cart will support mailing lists and other features. See the <a href="HelpCart.php">Cart</a> help topic for more information.</p>
        </div>
    </div>

    <!-- Section: How do I assign a Property to a Group? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I assign a Property to a Group?
        </div>
        <div class="card-body">
            <p>See the <a href="Help.php?page=Properties">Properties</a> help topic.</p>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
