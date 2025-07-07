<footer class="bg-light text-center text-lg-start mt-auto">

  <!-- Copyright and Footer Bottom -->

  <div class="text-center p-3 bg-dark text-white">
        <h5 class="text-uppercase">ChurchInfo</h5>
        <p>
          A free and open-source church management system. Helping churches stay organized and grow their community.
        </p>
    © <?php echo date("Y"); ?> ChurchInfo. Version: <?php echo $_SESSION['sChurchInfoPHPVersion']; ?>. All rights reserved.<br>
    Licensed under the GNU General Public License.
  </div>
</footer>

<?php
// Turn OFF output buffering
ob_end_flush();

// Reset the Global Message
$_SESSION['sGlobalMessage'] = "";
?>
</body>
</html>
