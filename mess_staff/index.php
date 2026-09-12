<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (!isLoggedIn()) {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'mess_staff' AND status = 'active' ORDER BY id ASC LIMIT 1");
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = 'mess_staff';
        $_SESSION['user_phone'] = $user['phone'];
        $_SESSION['user_status'] = $user['status'];
        $_SESSION['user_profile_image'] = $user['profile_image'];
    }
}

header('Location: ' . BASE_URL . '/mess_staff/dashboard.php');
exit;
