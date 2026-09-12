<?php


declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    sendJsonResponse(false, 'Unauthorized session. Please log in.', [], 401);
}

$user = currentUser();
if (!$user || $user['role'] !== 'mess_staff') {
    sendJsonResponse(false, 'Mess Staff access required.', [], 403);
}
$pdo = getDBConnection();
$action = $_POST['action'] ?? '';
$csrfToken = $_POST['csrf_token'] ?? '';

if (!verifyCSRFToken($csrfToken)) {
    sendJsonResponse(false, 'Security token expired. Please refresh the page.', [], 403);
}

if ($action === 'update_preference') {
    $boarderId = (int)($_POST['boarder_id'] ?? 0);
    $mealDate = trim($_POST['meal_date'] ?? '');
    $mealType = strtolower(trim($_POST['meal_type'] ?? '')); // breakfast, lunch, dinner
    $status = (int)($_POST['status'] ?? 1); // 1 = ON, 0 = OFF

    if (empty($mealDate) || !in_array($mealType, ['breakfast', 'lunch', 'dinner'], true)) {
        sendJsonResponse(false, 'Invalid meal date or meal type parameter.', [], 400);
    }

    if (isMealCutoffPassed($mealDate)) {
        sendJsonResponse(
            false, 
            'Meal changes for tomorrow close at 9:00 PM.', 
            ['cutoff_exceeded' => true, 'target_date' => $mealDate], 
            400
        );
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM meal_preferences WHERE boarder_id = ? AND meal_date = ?");
        $stmt->execute([$boarderId, $mealDate]);
        $existing = $stmt->fetch();

        if ($existing) {
            $updateSql = "UPDATE meal_preferences SET {$mealType} = ?, updated_at = NOW() WHERE id = ?";
            $upStmt = $pdo->prepare($updateSql);
            $upStmt->execute([$status, $existing['id']]);
        } else {
            $b = ($mealType === 'breakfast') ? $status : 1;
            $l = ($mealType === 'lunch') ? $status : 1;
            $d = ($mealType === 'dinner') ? $status : 1;

            $insStmt = $pdo->prepare("
                INSERT INTO meal_preferences (boarder_id, meal_date, breakfast, lunch, dinner, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $insStmt->execute([$boarderId, $mealDate, $b, $l, $d]);
        }

        $label = ucfirst($mealType);
        $stateText = $status === 1 ? 'ON' : 'OFF';
        sendJsonResponse(true, "{$label} meal toggled {$stateText} for " . formatDate($mealDate));
    } catch (Exception $e) {
        sendJsonResponse(false, 'Failed to update preference: ' . $e->getMessage(), [], 500);
    }
}

sendJsonResponse(false, 'Invalid action requested.', [], 400);
