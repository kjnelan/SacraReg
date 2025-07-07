<?php
    $sPageTitle = "Finances";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: What Financial Tracking is provided by ChurchInfo? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What Financial Tracking is provided by ChurchInfo?
        </div>
        <div class="card-body">
            <p>ChurchInfo tracks the following financial information:</p>
            <ul>
                <li><strong>Pledge:</strong> A promise to donate a specific total amount.</li>
                <li><strong>Deposit Slip:</strong> Print a batch of donations on a standard bank deposit form for the bank.</li>
                <li><strong>Payment:</strong> A donation by cash, check, credit card, or bank draft.</li>
                <li><strong>Reminder Statements:</strong> Print letters to remind Families of their pledge and report payment progress for the current fiscal year.</li>
                <li><strong>Tax Statements:</strong> Print letters acknowledging donations over the calendar year for tax purposes.</li>
            </ul>
        </div>
    </div>

    <!-- Section: How do I enter a pledge? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I enter a pledge?
        </div>
        <div class="card-body">
            <p>Pledges can be added in two ways:</p>
            <ul>
                <li><strong>From the Family View:</strong> In the Family view, a link for "Add a new pledge" will be near the bottom. Enter the information and click "Save."</li>
                <li><strong>Batch Entry:</strong> If you click "Save and Add" rather than "Save," the Pledge Editor will reset for another entry. Select the next family and continue until all pledges are entered.</li>
            </ul>
        </div>
    </div>

    <!-- Section: How do I deposit donations? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I deposit donations?
        </div>
        <div class="card-body">
            <p>When a batch of cash and check donations is received, they are entered into ChurchInfo to credit the donating families for tax purposes.</p>
            <ul>
                <li><strong>Make a new deposit slip:</strong> Select "New Deposit Slip (checks and cash)" from the "Deposit" menu.</li>
                <li><strong>Enter the deposits:</strong> Enter the deposit details.</li>
                <li><strong>Print the deposit slip:</strong> Select "Edit Deposit Slip" from the "Deposit" menu, and click "Generate PDF" to print on a standard bank deposit form.</li>
                <li><strong>Close the deposit:</strong> Select "Close deposit slip" once the deposit is packaged for the bank.</li>
            </ul>
            <p>Automatic credit card and bank draft deposits are supported with Vanco or Authorize.NET accounts.</p>
            <p>The automatic payment vendor is set in <strong>Admin -> Edit General Settings</strong>. Valid settings include Vanco and AuthorizeNet. An account must be established with the vendor and credentials entered into ChurchInfo.</p>
            <ul>
                <li><strong>Configure automatic payments:</strong> For each family, click "Add a new automatic payment" in the Family view.</li>
                <li><strong>Fill in payment information:</strong> Enter details, including specific fields for credit card or bank draft transactions.</li>
                <li><strong>Make a new deposit slip:</strong> Select "New Deposit Slip (credit card)" or "New Deposit Slip (bank draft)" from the "Deposit" menu.</li>
                <li><strong>Load authorized payments:</strong> Press "Load Authorized Transactions" to create payment records. The next payment date will be updated based on the interval.</li>
                <li><strong>Process payments:</strong> Press "Run Transactions" to execute payments using the ECHO transaction server. Review the "Cleared" column for the transaction status.</li>
                <li><strong>Fix failed payments:</strong> Press "Details" for failed transactions, correct any issues, and re-submit.</li>
                <li><strong>Close the deposit slip:</strong> Select "Close deposit slip" and press "Save" when finished.</li>
            </ul>
        </div>
    </div>

    <!-- Section: How do I enter a payment? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I enter a payment?
        </div>
        <div class="card-body">
            <p>Payments can be added in two ways:</p>
            <ul>
                <li><strong>From the Family View:</strong> In the Family view, a link for "Add a new payment" will be near the bottom. Enter the information and click "Save."</li>
                <li><strong>Batch Entry:</strong> If you click "Save and Add," the Payment Editor will reset for another entry. Select the next family and continue until all payments are entered.</li>
            </ul>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
