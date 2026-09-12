<?php


declare(strict_types=1);

require_once __DIR__ . '/functions.php';

if (!isLoggedIn()) {
    setFlash('error', 'Please log in to access the system.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$user = currentUser();
if ($user && $user['status'] !== 'active') {
    setFlash('error', 'Your Mess Staff account is not active. Please contact the system administrator.');
    $_SESSION = [];
    if (session_id()) session_destroy();
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}
