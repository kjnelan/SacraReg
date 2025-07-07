<?php
    $sPageTitle = "Reports and Queries";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: What are Reports/Queries? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What are Reports/Queries?
        </div>
        <div class="card-body">
            <p>Reports are built-in reports that provide specific information about users. Queries are pre-built searches on the database that return results for individuals or families.</p>
        </div>
    </div>

    <!-- Section: What is a Free-Text Query? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is a Free-Text Query?
        </div>
        <div class="card-body">
            <p>A Free-Text Query allows you to run any query on the database. Since ChurchInfo is based on MySQL, anyone with knowledge of SQL can run free-text queries.</p>
        </div>
    </div>

    <!-- Section: What is a Cart-Enabled Query? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is a Cart-Enabled Query?
        </div>
        <div class="card-body">
            <p>A Cart-Enabled Query allows the results of the query to be added directly to the cart for further processing.</p>
        </div>
    </div>

    <!-- Section: How do I use Cart-Enabled Queries? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I use Cart-Enabled Queries?
        </div>
        <div class="card-body">
            <p>Once a Cart-Enabled Query has been run, simply click the button labeled <strong>"Add Results to Cart"</strong> to add the results to your cart.</p>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
