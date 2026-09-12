<?php


declare(strict_types=1);
require_once __DIR__ . '/auth_check.php';

$user = currentUser();
if (!$user || $user['role'] !== 'mess_staff') {
    setFlash('error', 'Unauthorized access. Mess Staff account required.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}
