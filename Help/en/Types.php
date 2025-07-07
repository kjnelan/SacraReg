<?php
    $sPageTitle = "Custom Types";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: Custom Field Types -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            What are the types for Custom Fields?
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center"><b>Name</b></th>
                            <th scope="col" class="text-center"><b>Description</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>True/False</b></td>
                            <td>Simple yes/no question</td>
                        </tr>
                        <tr>
                            <td><b>Date</b></td>
                            <td>Standard date in Year-Month-Day [YYYY-MM-DD] format</td>
                        </tr>
                        <tr>
                            <td><b>Text Field (50 Character)</b></td>
                            <td>A text field with a maximum length of 50 characters</td>
                        </tr>
                        <tr>
                            <td><b>Text Field (100 Character)</b></td>
                            <td>A text field with a maximum length of 100 characters</td>
                        </tr>
                        <tr>
                            <td><b>Text Field (Long)</b></td>
                            <td>A paragraph-length text field holding a maximum of 65,535 characters</td>
                        </tr>
                        <tr>
                            <td><b>Year</b></td>
                            <td>Standard 4-digit year. Allowable values are 1901 to 2155</td>
                        </tr>
                        <tr>
                            <td><b>Season</b></td>
                            <td>Select one of the 4 seasons</td>
                        </tr>
                        <tr>
                            <td><b>Number</b></td>
                            <td>A whole number (integer) between -2147483648 and 2147483647</td>
                        </tr>
                        <tr>
                            <td><b>Person From Group</b></td>
                            <td>Select a person from a specified group</td>
                        </tr>
                        <tr>
                            <td><b>Money</b></td>
                            <td>A number with 2 decimal places, maximum 999999999.99</td>
                        </tr>
                        <tr>
                            <td><b>Phone Number</b></td>
                            <td>Standard phone number. Will be auto-formatted based on person's country</td>
                        </tr>
                        <tr>
                            <td><b>Custom Drop-Down List</b></td>
                            <td>This lets you create a drop-down selection list of any values you want. You can edit this list after you add this type to a form</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
