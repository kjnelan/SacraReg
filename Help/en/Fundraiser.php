<?php
    $sPageTitle = "Fundraiser";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: What is the Fundraiser feature for? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What is the Fundraiser feature for?
        </div>
        <div class="card-body">
            <p>Fundraiser automation is used for events where members are buying and selling items and/or services to benefit the church. One example is a goods and services auction, where members donate items and services to be auctioned off. This feature is designed for events where most of the buyers and sellers are in the database.</p>
        </div>
    </div>

    <!-- Section: How is a fundraiser created? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How is a fundraiser created?
        </div>
        <div class="card-body">
            <p>Select <strong>Fundraiser -> Create New Fundraiser</strong>. Enter a date, title, and description, and press Save.</p>
        </div>
    </div>

    <!-- Section: How are donated items entered into the fundraiser? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How are donated items entered into the fundraiser?
        </div>
        <div class="card-body">
            <p>Once the fundraiser is saved, additional buttons appear. Press <strong>Add Donated Item</strong> to enter a new item. The fields are:</p>
            <ul>
                <li><strong>Item:</strong> Used to sort the items for easier rearrangement.</li>
                <li><strong>Multiple items: Sell to everyone:</strong> Enable this to sell multiple copies of the item, with the buyer charged based on the entered count.</li>
                <li><strong>Donor:</strong> A person in the database who donated the item.</li>
                <li><strong>Title:</strong> A short description of the item.</li>
                <li><strong>Estimated Price:</strong> A reference value for the item.</li>
                <li><strong>Material Value:</strong> The donation value, excluding labor.</li>
                <li><strong>Minimum Price:</strong> A reference minimum price to ensure the item doesn't sell too low.</li>
                <li><strong>Description:</strong> A longer description for the catalog and bid sheet.</li>
                <li><strong>Buyer:</strong> The person who purchased the item (filled in after the sale).</li>
                <li><strong>Final Price:</strong> The final price paid for the item.</li>
            </ul>
        </div>
    </div>

    <!-- Section: Why and how are buyers registered? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            Why and how are buyers registered?
        </div>
        <div class="card-body">
            <p>Buyers are registered so they can purchase multiple items and check out at the end to pay for everything at once. To enter buyers, select <strong>Fundraiser -> View Buyers</strong>, and press <strong>Add Buyer</strong>. Buyer numbers increment automatically, or you can type them in manually.</p>
        </div>
    </div>

    <!-- Section: How is a single purchase recorded? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How is a single purchase recorded?
        </div>
        <div class="card-body">
            <p>Select <strong>Fundraiser -> Edit Fundraiser</strong> to see the list of items. Click the link for the item to bring up the editor page, select the buyer, enter the price, and press Save.</p>
        </div>
    </div>

    <!-- Section: Is there a way to enter lots of purchases quickly? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            Is there a way to enter lots of purchases quickly?
        </div>
        <div class="card-body">
            <p>Select <strong>Fundraiser -> Edit Fundraiser</strong>, and then press <strong>Batch Winner Entry</strong> (upper-right). This page allows you to quickly enter up to 10 items. For each item, select the item, the winner, and enter the price. Press <strong>Enter Winners</strong> to save all entries at once.</p>
        </div>
    </div>

    <!-- Section: How are multiple purchase items recorded? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How are multiple purchase items recorded?
        </div>
        <div class="card-body">
            <p>Select <strong>Fundraiser -> View Buyers</strong>, and click the link for a buyer. You can enter the quantity for "Sell to Everyone" items on this page.</p>
        </div>
    </div>

    <!-- Section: How does someone check out and pay? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How does someone check out and pay?
        </div>
        <div class="card-body">
            <p>Select <strong>Fundraiser -> View Buyers</strong>, and click the link for a buyer. Verify the "Sell to Everyone" quantities, then press <strong>Generate Statement</strong> to create a PDF showing both donations and purchases. This statement includes the total to be paid at check-out, along with a payment stub.</p>
        </div>
    </div>

    <!-- Section: How to prepare a statement for a donor who did not attend? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What if someone donates but does not attend? How can a statement be prepared to show the donations?
        </div>
        <div class="card-body">
            <p>Once the fundraiser is over and all donations and purchases have been entered, select <strong>Fundraiser -> Add Donors to Buyer List</strong>. This creates a buyer record for anyone who donated items but was not already listed as a buyer. You can then generate statements for these donors, which may be useful for tax purposes.</p>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
