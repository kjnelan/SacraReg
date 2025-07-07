<?php
    $sPageTitle = "About ChurchInfo";
    require "Include/Header.php";
?>

<div class="container mt-4">
    <!-- Basic Information Section -->
    <div class="card mb-4">
        <div class="card-header" style="background-color: #6c757d; color: white;">
            Basic Information
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <tr>
                    <th scope="row" style="width: 25%;">Version:</th>
                    <td><?php echo $_SESSION['sChurchInfoPHPVersion']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Release Date:</th>
                    <td><?php echo $_SESSION['sChurchInfoPHPDate']; ?></td>
                </tr>
                <tr>
                    <th scope="row">License:</th>
                    <td>GPL (Free, Open Source)</td>
                </tr>
                <tr>
                    <th scope="row">Homepage:</th>
                    <td><a href="http://www.churchdb.org">http://www.churchdb.org</a></td>
                </tr>
                <tr>
                    <th scope="row">Help Forums:</th>
                    <td><a href="http://sourceforge.net/forum/?group_id=117341">http://sourceforge.net/forum/?group_id=117341</a></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ChurchInfo Development Section -->
    <div class="card mb-4">
        <div class="card-header" style="background-color: #6c757d; color: white;">
            Who developed ChurchInfo and Why?
        </div>
        <div class="card-body">
            <p>ChurchInfo is based on InfoCentral, bringing this high-quality free software to organizations that need features not found in the original version. InfoCentral was developed by a team of volunteers, in their spare time, for the purpose of providing churches and other non-profit organizations with high-quality free software.</p>
            <p>It is our dream that one day, churches and NPO's won't have to spend a dime on software. Coupled with free operating systems like <a href="http://www.linux.org">Linux</a> and free office software like <a href="http://www.openoffice.org">OpenOffice</a>, this dream is quickly becoming a reality. ChurchInfo is distinguished from InfoCentral by its feature set and the intention to continue on a stable, evolutionary path indefinitely. If you'd like to find out more or want to help out, please visit our <a href="http://www.churchdb.org">homepage</a>.</p>
            <p>The current ChurchInfo project leader is Michael Wilt.</p>
        </div>
    </div>

    <!-- Fork Information Section -->
    <div class="card mb-4">
        <div class="card-header" style="background-color: #6c757d; color: white;">
            Fork Information
        </div>
        <div class="card-body">
            <p>This version of ChurchInfo has been forked and modified by Rev. Fr. Kenneth J Nelan. The changes are intended to address specific sacramental needs of the Anglo/Catholic (and other Christian) traditions.. The fork introduces new features, optimizations, and updates to keep ChurchInfo relevant and effective for modern use.</p>
            <p>If you're interested in this fork or want to collaborate, feel free to contact Fr. Kenn Nelan @ <a href="mailto:kenn.nelan@sacwan.org">kenn.nelan@sacwan.org</a>.</p>
        </div>
    </div>
</div>

<?php
    require "Include/Footer.php";
?>
