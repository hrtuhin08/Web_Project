<?php


declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$user = currentUser();
$pdo = getDBConnection();
$action = $_POST['action'] ?? '';
$csrfToken = $_POST['csrf_token'] ?? '';

if (!verifyCSRFToken($csrfToken)) {
    setFlash('error', 'Security token invalid. Please try again.');
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/')));
    exit;
}

if ($action === 'update_profile') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($name) || empty($phone)) {
        setFlash('error', 'Name and phone cannot be empty.');
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $user['id']]);
        $_SESSION['user_name'] = $name;
        $_SESSION['user_phone'] = $phone;

        logActivity((int)$user['id'], 'Profile Updated', 'Updated profile information.');
        setFlash('success', 'Profile updated successfully.');
    }
} elseif ($action === 'change_password') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user['id']]);
    $currentHash = $stmt->fetchColumn();

    if (!password_verify($currentPassword, $currentHash)) {
        setFlash('error', 'Your current password was incorrect.');
    } elseif ($newPassword !== $confirmPassword) {
        setFlash('error', 'New password and confirmation do not match.');
    } elseif (strlen($newPassword) < 6) {
        setFlash('error', 'New password must be at least 6 characters.');
    } else {
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $upStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $upStmt->execute([$newHash, $user['id']]);

        logActivity((int)$user['id'], 'Password Changed', 'User changed their password.');
        setFlash('success', 'Password successfully changed.');
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/')));
exit;
