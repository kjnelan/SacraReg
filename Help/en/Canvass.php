<?php
    $sPageTitle = "Canvass Support";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Canvass Support Overview -->
    <h1 class="mb-4">ChurchInfo Canvass Automation</h1>
    <p>ChurchInfo includes comprehensive support to facilitate an every-member canvass effort. The main control panel for canvass activity is called the Canvass Automation page. This page may be found by selecting <strong>Data/Reports -> Reports Menu</strong>, and then clicking the <u>Canvass Automation</u> link.</p>

    <!-- Steps for Canvass -->
    <h3 class="mt-4">Steps to Follow</h3>
    <ol class="list-group list-group-numbered">
        <li class="list-group-item">Create a group called <strong>“Canvassers”</strong>. Identify canvassers and collect them in this group.</li>
        <li class="list-group-item">Optionally create a group called <strong>“BraveCanvassers”</strong> for canvassers willing to call families that did not pledge last year.</li>
        <li class="list-group-item">Use the Canvass Automation page to enable or disable canvassing for all families. You can toggle the <strong>“Ok To Canvass”</strong> field in the family editor.</li>
        <li class="list-group-item">Assign <strong>BraveCanvassers</strong> to non-pledging families using the Canvass Automation page, if applicable.</li>
        <li class="list-group-item">Assign canvassers using the Canvass Automation page.</li>
        <li class="list-group-item">Adjust canvasser assignments in the family editor. The assignment field is near the <strong>“Ok To Canvass”</strong> field.</li>
        <li class="list-group-item">Edit the file <code>Reports/CanvassQuestions.txt</code> to include the questions for canvass conversations.</li>
        <li class="list-group-item">Generate briefing sheets for each family using the Canvass Automation page. These sheets are organized by canvasser for easy delivery.</li>
        <li class="list-group-item">Instruct canvassers to use the <strong>“Canvass Entry”</strong> link for each family. Canvassers must have the <strong>Canvasser</strong> permission enabled.</li>
        <li class="list-group-item">Use the reports at the bottom of the Canvass Automation page to track progress and summarize results.</li>
    </ol>
</div>

<?php
    require "Include/Footer.php";
?>
