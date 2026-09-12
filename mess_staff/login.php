<?php


declare(strict_types=1);
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    $user = currentUser();
    if (($user['role'] ?? '') === 'mess_staff') {
        header('Location: ' . BASE_URL . '/mess_staff/dashboard.php');
        exit;
    }

    $_SESSION = [];
    if (session_id()) session_destroy();
}

$error = '';
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verifyCSRFToken($csrfToken)) {
        $error = 'Security validation failed. Please refresh the page and try again.';
    } elseif (empty($email) || empty($password)) {
        $error = 'Please enter both your email address and password.';
    } else {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'mess_staff' LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                $error = 'Your Mess Staff account is currently inactive.';
            } else {
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = 'mess_staff';
                $_SESSION['user_phone'] = $user['phone'];
                $_SESSION['user_status'] = $user['status'];
                $_SESSION['user_profile_image'] = $user['profile_image'];

                logActivity((int)$user['id'], 'Mess Staff Login', "Logged in successfully from {$_SERVER['REMOTE_ADDR']}");
                header('Location: ' . BASE_URL . '/mess_staff/dashboard.php');
                exit;
            }
        } else {
            $error = 'Invalid Mess Staff email address or password.';
        }
    }
}

$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mess Staff Sign In | Hostel & Mess Management System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500;600&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forms.css">
  <script>window.BASE_URL = '<?= BASE_URL ?>';</script>
  <style>
    body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at 10% 20%, rgba(13, 148, 136, 0.08) 0%, rgba(15, 23, 42, 0.05) 90%), #f8fafc; padding: 1.5rem; }
    .auth-card { width: 100%; max-width: 460px; background: #fff; border-radius: var(--radius-xl); border: 1px solid var(--border-color); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); padding: 2.5rem; }
    .auth-header { text-align: center; margin-bottom: 2rem; }
    .auth-logo { width: 48px; height: 48px; background: var(--primary); border-radius: var(--radius-md); display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; box-shadow: 0 4px 14px var(--primary-glow); }
    .demo-credentials-box { margin-top: 1.75rem; padding: 1rem; background: #f8fafc; border-radius: var(--radius-md); border: 1px solid var(--border-color); }
  </style>
</head>
<body>
  <div class="auth-card">
    <div class="auth-header">
      <div class="auth-logo">M</div>
      <h2 style="font-size: 1.6rem; margin-bottom: 0.25rem;">Mess Staff Login</h2>
      <p style="color: var(--text-muted); font-size: 0.88rem;">Sign in to your Mess Staff dashboard</p>
    </div>

    <?php if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/login.php" data-validate="true">
      <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
      <div class="form-group">
        <label class="form-label">Email Address <span class="required">*</span></label>
        <input type="email" id="emailInput" name="email" class="form-control" required placeholder="mess@hostel.com" value="<?= e($_POST['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Password <span class="required">*</span></label>
        <input type="password" id="passwordInput" name="password" class="form-control" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 1.5rem;">Sign In</button>
    </form>

    <div class="demo-credentials-box">
      <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Mess Staff Demo</div>
      <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.5rem;">Email: mess@hostel.com &nbsp; | &nbsp; Password: password123</div>
    </div>
  </div>
</body>
</html>
