<?php
    $sPageTitle = "Events";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: What is an Event? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is an Event?
        </div>
        <div class="card-body">
            <p>An <b>Event</b> is an occasion that may, or may not, be hosted at your location. It could be a Worship Service, Sunday School, fundraiser, picnic, etc. By using the <b>Event</b> module, you can generate reports on attendance, track who didn't attend, and view any guests.</p>
            <p>Events are created using a template called an <b>Event Type</b>.</p>
        </div>
    </div>

    <!-- Section: What is an Event Type? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is an Event Type?
        </div>
        <div class="card-body">
            <p>An <b>Event Type</b> is a template defining a default pattern used to create a particular kind of event. It includes an Event Name, a Recurrence Pattern, the default Start Time, and a list of Attendance Counts tracked by this <b>Event Type</b>.</p>
        </div>
    </div>

    <!-- Section: How do I see what Events are available? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I see what Events are available?
        </div>
        <div class="card-body">
            <p>In the <b>Events</b> tab of the menu, click on "<b>List Church Event</b>". This will display all recorded events listed by month for the current year. The page shows the Event Name, Description, recorded Attendance counts, Start Time, and a button to display the number of attendees.</p>
        </div>
    </div>

    <!-- Section: How do I add a New Event? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I add a New Event?
        </div>
        <div class="card-body">
            <p>In the <b>Events</b> tab of the menu, click on "<b>Add Church Event</b>". Select the type of event, and click "Create=>Event". You will be presented with a pre-filled event form based on the Event Type. Fill in the remaining fields and click "Save".</p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Event Type</b></td>
                        <td>REQ.</td>
                        <td>Prefilled using the Event Type information.</td>
                    </tr>
                    <tr>
                        <td><b>Event Title</b></td>
                        <td>REQ.</td>
                        <td>Enter a Title for your Event (255 characters or less).</td>
                    </tr>
                    <tr>
                        <td><b>Event Description</b></td>
                        <td>REQ.</td>
                        <td>Enter a Short Description for your Event (255 characters or less).</td>
                    </tr>
                    <tr>
                        <td><b>Start Date</b></td>
                        <td>REQ.</td>
                        <td>Pre-filled using the Recurrence Pattern of the Event Type. The prefilled values may be edited.</td>
                    </tr>
                    <tr>
                        <td><b>Start Time</b></td>
                        <td>REQ.</td>
                        <td>Set using the Event Type and may be changed.</td>
                    </tr>
                    <tr>
                        <td><b>End Date</b></td>
                        <td>OPT.</td>
                        <td>Optional end date for your Event.</td>
                    </tr>
                    <tr>
                        <td><b>End Time</b></td>
                        <td>OPT.</td>
                        <td>Optional end time for your Event.</td>
                    </tr>
                    <tr>
                        <td><b>Attendance Counts</b></td>
                        <td>OPT.</td>
                        <td>Enter attendance count fields based on the Event Type.</td>
                    </tr>
                    <tr>
                        <td><b>Event Sermon</b></td>
                        <td>OPT.</td>
                        <td>Optional field for entering the text of your sermon.</td>
                    </tr>
                    <tr>
                        <td><b>Event Status</b></td>
                        <td>REQ.</td>
                        <td>Set whether the Event is active or not.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section: How to keep track of Attendance -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How to keep track of Attendance?
        </div>
        <div class="card-body">
            <p>There are two ways of tracking attendance: By Attendance Counts or By Attendees.</p>
            <p><strong>By Counts:</strong> Enter the desired count value in the Attendance Count fields.</p>
            <p><strong>By Attendees:</strong> Add attendees to the "<b>Cart</b>", and then select "<b>Empty Cart to Event</b>" from the Cart menu.</p>
        </div>
    </div>

    <!-- Section: How do I retrieve Attendance Reports? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I retrieve Attendance Reports?
        </div>
        <div class="card-body">
            <p>TBD</p>
        </div>
    </div>

    <!-- Section for Admin Users: How to add an Event Type -->
    <?php if ($_SESSION['bAdmin']) { ?>
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I add an Event Type to the list?
        </div>
        <div class="card-body">
            <p>In the <b>Events</b> tab of the menu, click "<b>List Event Types</b>". Click "Add Event Type" to create a new type. Event Types cannot be edited, but they can be deleted.</p>
        </div>
    </div>
    <?php } ?>

    <!-- Section for non-Admin users: Contact admin for adding Event Name -->
    <?php if (!$_SESSION['bAdmin']) { ?>
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            I don't see the Event listed in the dropdown box, how do I add an Event Name to the list?
        </div>
        <div class="card-body">
            <p>Please contact the site administrator to have your Event Name added.</p>
        </div>
    </div>
    <?php } ?>
</div>

<?php
    require "Include/Footer.php";
?>
