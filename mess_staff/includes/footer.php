<?php


declare(strict_types=1);
require_once __DIR__ . '/functions.php';

$flash = getFlash();
?>
      </div> <!-- End page-content -->
    </main> <!-- End main-wrapper -->
  </div> <!-- End dashboard-layout -->

  <!-- Toast notifications container -->
  <div class="toast-container" id="toastContainer"></div>

  <!-- Global Scripts -->
  <script src="<?= BASE_URL ?>/assets/js/main.js"></script>
  <script src="<?= BASE_URL ?>/assets/js/validation.js"></script>
  <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
  <script src="<?= BASE_URL ?>/assets/js/meals.js"></script>
  <script src="<?= BASE_URL ?>/assets/js/chat.js"></script>

  <?php if ($flash): ?>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        showToast('<?= $flash['type'] ?>', '<?= addslashes($flash['message']) ?>');
      });
    </script>
  <?php endif; ?>
</body>
</html>
