<?php


declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn() || !hasRole('mess_staff')) {
    setFlash('error', 'Unauthorized.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$user = currentUser();
$pdo = getDBConnection();
$action = $_POST['action'] ?? '';
$csrfToken = $_POST['csrf_token'] ?? '';

if (!verifyCSRFToken($csrfToken)) {
    setFlash('error', 'Security token mismatch.');
    header('Location: ' . BASE_URL . '/mess_staff/weekly_menu.php');
    exit;
}

if ($action === 'save_item') {
    $menuId = (int)($_POST['weekly_menu_id'] ?? 0);
    $day = $_POST['day'] ?? '';
    $mealType = $_POST['meal_type'] ?? '';
    $description = trim($_POST['menu_description'] ?? '');

    $validDays = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    $validMeals = ['Breakfast', 'Lunch', 'Dinner'];

    if ($menuId <= 0 || !in_array($day, $validDays, true) || !in_array($mealType, $validMeals, true) || empty($description)) {
        setFlash('error', 'Please fill all menu item details.');
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO menu_items (weekly_menu_id, day, meal_type, menu_description)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE menu_description = VALUES(menu_description)
        ");
        $stmt->execute([$menuId, $day, $mealType, $description]);
        setFlash('success', "Updated {$day} {$mealType} menu.");
    }
} elseif ($action === 'publish') {
    $menuId = (int)($_POST['weekly_menu_id'] ?? 0);
    if ($menuId > 0) {
        $stmt = $pdo->prepare("UPDATE weekly_menus SET status = 'published' WHERE id = ?");
        $stmt->execute([$menuId]);
        logActivity((int)$user['id'], 'Published Weekly Menu', "Published weekly menu ID {$menuId}");
        setFlash('success', 'Weekly Menu is now published and visible to all boarders.');
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/mess_staff/weekly_menu.php')));
exit;
