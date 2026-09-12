<?php


declare(strict_types=1);
$required_role = 'mess_staff';
require_once __DIR__ . '/../includes/role_check.php';

$user = currentUser();
$pdo = getDBConnection();

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$userData = $stmt->fetch();

$page_title = 'Mess Staff Profile';
$page_heading = 'Mess Staff Profile';
$page_subheading = 'Update contact information and account credentials';

require_once __DIR__ . '/../includes/header.php';
?>
<div class="dashboard-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="page-content">
      <div class="dashboard-grid-2">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Profile Information</h3>
          </div>
          <form method="POST" action="<?= BASE_URL ?>/actions/auth.php" data-validate="true">
            <input type="hidden" name="action" value="update_profile">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="form-group">
              <label class="form-label">Full Name <span class="required">*</span></label>
              <input type="text" name="name" class="form-control" required value="<?= e($userData['name']) ?>">
            </div>

            <div class="form-group">
              <label class="form-label">Email Address (System Login)</label>
              <input type="email" class="form-control" readonly value="<?= e($userData['email']) ?>">
            </div>

            <div class="form-group">
              <label class="form-label">Phone Number <span class="required">*</span></label>
              <input type="tel" name="phone" class="form-control" required value="<?= e($userData['phone']) ?>">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Save Profile Changes</button>
          </form>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Change Password</h3>
          </div>
          <form method="POST" action="<?= BASE_URL ?>/actions/auth.php" data-validate="true">
            <input type="hidden" name="action" value="change_password">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="form-group">
              <label class="form-label">Current Password <span class="required">*</span></label>
              <input type="password" name="current_password" class="form-control" required placeholder="••••••••">
            </div>

            <div class="form-group">
              <label class="form-label">New Password <span class="required">*</span></label>
              <input type="password" name="new_password" class="form-control" required placeholder="Minimum 6 characters">
            </div>

            <div class="form-group">
              <label class="form-label">Confirm Password <span class="required">*</span></label>
              <input type="password" name="confirm_password" class="form-control" required placeholder="Re-type new password">
            </div>

            <button type="submit" class="btn btn-secondary" style="margin-top: 1rem;">Update Password</button>
          </form>
        </div>
      </div>
    </div> <!-- End page-content -->
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
