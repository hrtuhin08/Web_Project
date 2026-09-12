<?php


declare(strict_types=1);
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    $user = currentUser();
    logActivity((int)$user['id'], 'User Logout', 'User logged out of session.');
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

if (session_id()) {
    session_destroy();
}

initSession();
setFlash('success', 'You have been successfully signed out.');
header('Location: ' . BASE_URL . '/login.php');
exit;
