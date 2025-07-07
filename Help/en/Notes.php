<?php
    $sPageTitle = "Notes";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: What is a Note? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is a Note?
        </div>
        <div class="card-body">
            <p>A Note is a miscellaneous memo assigned to a Person or Family record. Any User with the Notes permission can add a Note, and as many Notes as desired can be added to a Person or Family record.</p>
            <p>Notes can be public, meaning every User can see them, or private, meaning only the User who authored the Note can read it on subsequent views of the Person or Family record. Notes can be deleted or edited as desired.</p>
        </div>
    </div>

    <!-- Section: How do I view the Notes for a Person record? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I view the Notes for a Person record?
        </div>
        <div class="card-body">
            <ol>
                <li>Filter for the desired Person and bring up the Person View for that record.</li>
                <li>At the bottom of the Person View, there will be a section called "Notes" which contains all the notes for that record, in reverse chronological order (the most recent note first).</li>
            </ol>
        </div>
    </div>

    <!-- Section: How do I add a Note? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I add a Note?
        </div>
        <div class="card-body">
            <ol>
                <li>Filter for the Person record to which you'd like to add the Note and bring up the Person View for that record.</li>
                <li>At the bottom of the Person View will be a section called "Notes." Click "Add a Note to this Record."</li>
                <li>On the resulting page, enter the text of the Note in the input box provided. You may enter as much or as little text as you like. If you would like the note to be private (meaning only you will be able to read it), check the box marked "Private."</li>
                <li>When finished, press "Save."</li>
            </ol>
        </div>
    </div>

    <!-- Section: How do I edit a Note? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I edit a Note?
        </div>
        <div class="card-body">
            <ol>
                <li>Filter for the Person record to which the desired Note is assigned.</li>
                <li>Find the desired Note in the "Notes" section and click "Edit this Note."</li>
                <li>On the resulting form, make any desired changes and press "Save" when finished.</li>
            </ol>
        </div>
    </div>

    <!-- Section: How do I make a private Note viewable by everyone? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I make a private Note viewable by everyone?
        </div>
        <div class="card-body">
            <p>Edit the Note and uncheck the "Private" checkbox.</p>
        </div>
    </div>

    <!-- Section: How do I delete a Note? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I delete a Note?
        </div>
        <div class="card-body">
            <ol>
                <li>Filter for the Person record to which the desired Note is assigned.</li>
                <li>Find the desired Note in the "Notes" section and click "Delete this Note."</li>
                <li>On the resulting screen, confirm the deletion.</li>
            </ol>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
