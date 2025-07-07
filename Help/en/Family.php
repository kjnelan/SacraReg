<?php
        $sPageTitle = "Families";
        require "Include/Header.php";
?>

<div class="container mt-4">
    <div class="Help_Section">
        <h2 class="Help_Header">What is a Family?</h2>
        <div class="card">
            <div class="card-body">
                <p>A Family is a group of Person records. Person records are grouped into Families for three reasons:</p>
                <ol>
                    <li>To represent the social constructs of the Family within the church.</li>
                    <li>To share information common to all members of the family -- things like address, phone number, email address, etc.</li>
                    <li>To support the church financially as a single unit ("pledge unit").</li>
                </ol>
                <p>Every Person should belong to a family.</p>
            </div>
        </div>
    </div>

    <div class="Help_Section mt-4">
        <h2 class="Help_Header">How do I add a new Family?</h2>
        <div class="card">
            <div class="card-body">
                <ol>
                    <li>From the top menu, select "Add New Family" (under "People/Families").</li>
                    <li>Complete the form. Note that you can insert up to ten family members directly from this form. Complete the individual lines for each person, but only enter the last name if it <i>differs</i> from the last name of the Family record. All people entered in this manner will create a new Person record that will be assigned to the designated Family record.</li>
                    <li>Press Save when the form is complete.</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="Help_Section mt-4">
        <h2 class="Help_Header">How do I view a family?</h2>
        <div class="card">
            <div class="card-body">
                <p>There are two ways to view a family:</p>
                <ol>
                    <li>Enter a name in the search field at the top of the page, click the button beside "Family" and press enter.</li>
                    <li>Click on "View All Families" (under "People/Families").</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="Help_Section mt-4">
        <h2 class="Help_Header">How do I change the available Family Roles?</h2>
        <div class="card">
            <div class="card-body">
                <ol>
                    <li>If you have permission, you should find a link called "Family Roles Manager" (under "People/Families").</li>
                    <li>If you want to add a new Family Role, type it into the blank field at the bottom of the page.</li>
                    <li>If you want to change a Family Role, type it into the field you wish to change. NOTE: Field changes will be lost if you do not "Save Changes" before using an up, down, delete, or 'add new' button!</li>
                    <li>If you want to re-arrange the order, click the "up" and "down" links to the left of the field you wish to re-order.</li>
                    <li>If you want to delete a Family Role, click the "delete" button to the right of the field you wish to delete.</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="Help_Section mt-4">
        <h2 class="Help_Header">How do I delete a Family?</h2>
        <div class="card">
            <div class="card-body">
                <ol>
                    <li>Filter for the desired family, and bring up the Family View.</li>
                    <li>Select "Delete this Record" (if this link doesn't appear, then either you don't have permissions to delete records, or the Family still has Person records assigned to it; you cannot delete a Family record until all Person records have been unassigned from it).</li>
                    <li>Confirm the deletion.</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="Help_Section mt-4">
        <h2 class="Help_Header">How do I assign a Property to a Family?</h2>
        <div class="card">
            <div class="card-body">
                <p>See the <a href="Help.php?page=Properties">Properties</a> help topic.</p>
            </div>
        </div>
    </div>

    <div class="Help_Section mt-4">
        <h2 class="Help_Header">How do I add a Note to a Family?</h2>
        <div class="card">
            <div class="card-body">
                <p>See the <a href="Help.php?page=Notes">Notes</a> help topic.</p>
            </div>
        </div>
    </div>

    <div class="Help_Section mt-4">
        <h2 class="Help_Header">What is the Classification feature?</h2>
        <div class="card">
            <div class="card-body">
                <p>See the <a href="Help.php?page=Class">Classification</a> help topic.</p>
            </div>
        </div>
    </div>
</div>

<?php
        require "Include/Footer.php";
?>
