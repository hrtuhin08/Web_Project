<?php


declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    sendJsonResponse(false, 'Unauthorized. Please login.', [], 401);
}

$user = currentUser();
if (!$user || $user['role'] !== 'mess_staff') {
    sendJsonResponse(false, 'Mess Staff access required.', [], 403);
}
$pdo = getDBConnection();
$action = $_REQUEST['action'] ?? '';

if ($action === 'send_message') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrfToken)) {
        sendJsonResponse(false, 'CSRF verification failed.', [], 403);
    }

    $conversationId = (int)($_POST['conversation_id'] ?? 0);
    $text = trim($_POST['message'] ?? '');

    if ($conversationId <= 0 || empty($text)) {
        sendJsonResponse(false, 'Invalid conversation or empty message.', [], 400);
    }

    $cStmt = $pdo->prepare("SELECT id, user_one_id, user_two_id FROM conversations WHERE id = ?");
    $cStmt->execute([$conversationId]);
    $conv = $cStmt->fetch();

    if (!$conv || ($conv['user_one_id'] != $user['id'] && $conv['user_two_id'] != $user['id'])) {
        sendJsonResponse(false, 'Unauthorized conversation access.', [], 403);
    }

    try {
        $ins = $pdo->prepare("
            INSERT INTO messages (conversation_id, sender_id, message, is_read, created_at)
            VALUES (?, ?, ?, 0, NOW())
        ");
        $ins->execute([$conversationId, $user['id'], $text]);
        $messageId = (int)$pdo->lastInsertId();

        $pdo->prepare("UPDATE conversations SET updated_at = NOW() WHERE id = ?")->execute([$conversationId]);

        sendJsonResponse(true, 'Message delivered.', [
            'id' => $messageId,
            'sender_id' => $user['id'],
            'message' => $text,
            'formatted_time' => 'Just now'
        ]);
    } catch (Exception $e) {
        sendJsonResponse(false, 'Failed to send message: ' . $e->getMessage(), [], 500);
    }

} elseif ($action === 'get_updates') {
    $conversationId = (int)($_GET['conversation_id'] ?? 0);
    $lastId = (int)($_GET['last_id'] ?? 0);

    if ($conversationId <= 0) {
        sendJsonResponse(false, 'Invalid conversation parameter.', [], 400);
    }

    $stmt = $pdo->prepare("
        SELECT id, sender_id, message, created_at 
        FROM messages 
        WHERE conversation_id = ? AND id > ?
        ORDER BY id ASC
    ");
    $stmt->execute([$conversationId, $lastId]);
    $messages = $stmt->fetchAll();

    $markStmt = $pdo->prepare("
        UPDATE messages 
        SET is_read = 1 
        WHERE conversation_id = ? AND sender_id != ? AND is_read = 0
    ");
    $markStmt->execute([$conversationId, $user['id']]);

    $formatted = array_map(function($m) {
        return [
            'id' => (int)$m['id'],
            'sender_id' => (int)$m['sender_id'],
            'message' => $m['message'],
            'formatted_time' => timeAgo($m['created_at'])
        ];
    }, $messages);

    sendJsonResponse(true, 'Updates retrieved.', $formatted);

} elseif ($action === 'start_or_get_conversation') {
    $targetUserId = (int)($_REQUEST['target_user_id'] ?? 0);
    if ($targetUserId <= 0) {
        setFlash('error', 'Target user not found.');
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }

    $userOne = min((int)$user['id'], $targetUserId);
    $userTwo = max((int)$user['id'], $targetUserId);

    $find = $pdo->prepare("SELECT id FROM conversations WHERE user_one_id = ? AND user_two_id = ?");
    $find->execute([$userOne, $userTwo]);
    $convId = $find->fetchColumn();

    if (!$convId) {
        $ins = $pdo->prepare("INSERT INTO conversations (user_one_id, user_two_id, updated_at) VALUES (?, ?, NOW())");
        $ins->execute([$userOne, $userTwo]);
        $convId = (int)$pdo->lastInsertId();
    }

    $redirectBase = BASE_URL . '/mess_staff/chat.php';
    header("Location: {$redirectBase}?conversation_id={$convId}");
    exit;
}

sendJsonResponse(false, 'Unknown chat action.', [], 400);
