<?php
    $sPageTitle = "Cart";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: What is the Cart? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is the Cart?
        </div>
        <div class="card-body">
            <p>The Cart is a temporary holding space for People records. You can add People to the Cart, then process these records all at once by generating labels or dumping the contents of the cart into a group.</p>
            <p>You may add an unlimited number of People to the Cart. Adding someone to the Cart does nothing to their record—they are just temporarily assigned to the Cart. You can add someone to the Cart, then remove them without processing, and their record will remain unchanged.</p>
            <p>The Cart is user- and session-specific. Each user has their own Cart, and it lasts only until they log off—Carts do not span sessions.</p>
        </div>
    </div>

    <!-- Section: How can I see what's in my Cart? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How can I see what's in my Cart?
        </div>
        <div class="card-body">
            <p>On the top menu, second row, right side, a real-time counter will show how many records you have in the Cart. This counter will go up or down as you add or remove records.</p>
            <p>To see the actual records in your Cart, click on <strong>"List Cart Items"</strong> under the Cart menu. This will display all records currently in the Cart.</p>
        </div>
    </div>

    <!-- Section: How do I add a person to the Cart? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I add a person to the Cart?
        </div>
        <div class="card-body">
            <p>There are several ways to add someone to the Cart:</p>
            <h5>To add an individual person:</h5>
            <ol>
                <li>From the top menu, click "View All Persons" under the "People/Families" menu or enter a name in the filter box and press Enter.</li>
                <li>When the filtered results are displayed, click the "Add to Cart" link on the far right of the Person record.</li>
                <li>If this Person is not already in the Cart, they will be added.</li>
            </ol>
            <p>Alternatively, view the desired Person record, and within that record, click the "Add to Cart" link to add them.</p>

            <h5>To add the results of a report:</h5>
            <ol>
                <li>Run the desired report.</li>
                <li>If the report is Cart-enabled, you will find an "Add Results to Cart" button at the bottom. Clicking this will add all the report results to the Cart.</li>
            </ol>

            <h5>To add all people assigned to a Group:</h5>
            <ol>
                <li>From the top menu, click "Empty Cart to Group" under the "Cart" menu.</li>
                <li>Select an existing Group or create a New Group. If you create a new Group, you can empty the Cart to it.</li>
            </ol>
        </div>
    </div>

    <!-- Section: How do I remove a person from the Cart? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I remove a person from the Cart?
        </div>
        <div class="card-body">
            <ol>
                <li>From the top menu, click "List Cart Items" under the "Cart" menu.</li>
                <li>On the resulting screen, each person in the Cart will be listed with a "Remove" link. Click this link to remove the person.</li>
            </ol>
            <p><strong>Note:</strong> To empty the Cart completely, click "Empty Cart" at the bottom of the page. This removes all People from the Cart without moving them anywhere. Do not confuse this with "Empty Cart to Group."</p>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
