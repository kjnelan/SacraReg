<?php
    $sPageTitle = "Geographic Support";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Section: How does ChurchInfo know exactly where Families live? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How does ChurchInfo know exactly where Families live?
        </div>
        <div class="card-body">
            <p>ChurchInfo stores the latitude and longitude with each Family. These coordinates may be entered manually on the Family edit page, or looked up based on the address. In the United States, this information is found automatically using the Internet service rpc.geocoder.us.</p>
            <p>If you know of a similar service for other countries, please let us know!</p>
        </div>
    </div>

    <!-- Section: How do I find Families that live close to each other? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I find Families that live close to each other?
        </div>
        <div class="card-body">
            <p>Select "Family Geographic Utilities" from the People/Families menu, then choose a Family from the list. Press "Show Neighbors," and this page will update with the nearest neighbor families listed at the bottom.</p>
            <p>The "Maximum number of neighbors" and "Maximum distance" fields are used to limit the number of neighbor families displayed.</p>
        </div>
    </div>

    <!-- Section: How do I see where Families live on a map? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            How do I see where Families live on a map?
        </div>
        <div class="card-body">
            <p>The easiest way is to select "Family Map" from the People/Families menu. This map is generated using the Google mapping service. For this feature to work, the Google Map key must be set specifically for your website URL. This setting is located near the bottom of the General Settings page under the Admin menu.</p>
            <p>To obtain your unique key from Google, visit <a href="http://maps.google.com/apis/maps/signup.html" target="_blank">Google Maps API Key Signup</a>.</p>
        </div>
    </div>

    <!-- Section: Are other types of maps available? -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            Are other types of maps available?
        </div>
        <div class="card-body">
            <p>The Family Geographic Utilities page can also generate annotation files for the <a href="http://www.gpsvisualizer.com/map" target="_blank">GPS Visualizer</a> website or the Delorme Street Atlas USA map program. To create an annotation file, select the desired format and press "Make Data File."</p>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
