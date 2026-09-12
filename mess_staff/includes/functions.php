<?php


declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

function initSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_name('HOSTEL_SESSID');
        session_set_cookie_params([
            'lifetime' => 86400 * 7,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }
}

initSession();


function e(?string $string): string {
    return htmlspecialchars((string)$string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}


function formatCurrency(float|int|string|null $amount): string {
    $val = (float)($amount ?? 0);
    return '৳ ' . number_format($val, 2);
}


function formatDate(?string $date, string $format = 'M d, Y'): string {
    if (!$date) return 'N/A';
    $time = strtotime($date);
    return $time ? date($format, $time) : 'N/A';
}


function timeAgo(string $datetime): string {
    $time = strtotime($datetime);
    if (!$time) return 'just now';
    $diff = time() - $time;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' mins ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    return date('M d, Y', $time);
}


function generateCSRFToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken(?string $token): bool {
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}


function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function currentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? 'User',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? '',
        'phone' => $_SESSION['user_phone'] ?? '',
        'status' => $_SESSION['user_status'] ?? 'active',
        'profile_image' => $_SESSION['user_profile_image'] ?? 'default-avatar.png'
    ];
}

function hasRole(string|array $roles): bool {
    if (!isLoggedIn()) return false;
    $currentRole = $_SESSION['user_role'] ?? '';
    if (is_array($roles)) {
        return in_array($currentRole, $roles, true);
    }
    return $currentRole === $roles;
}


function logActivity(?int $userId, string $action, string $details): void {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("INSERT INTO activities (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$userId, $action, $details]);
    } catch (Exception $e) {
        error_log("Activity logging error: " . $e->getMessage());
    }
}


function isMealCutoffPassed(string $targetDate): bool {
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));

    if ($targetDate <= $today) {
        return true;
    }

    if ($targetDate === $tomorrow) {
        $currentHour = (int)date('H');
        if ($currentHour >= 21) {
            return true;
        }
        return false;
    }

    return false;
}


function calculatePerMealCost(?int $hostelId = null, ?string $month = null): float {
    $pdo = getDBConnection();
    $targetMonth = $month ?: date('Y-m');

    $expenseSql = "SELECT COALESCE(SUM(amount), 0) as total_expense 
                   FROM bazar_expenses 
                   WHERE DATE_FORMAT(expense_date, '%Y-%m') = :month";
    $expenseParams = ['month' => $targetMonth];

    if ($hostelId) {
        $expenseSql .= " AND hostel_id = :hostel_id";
        $expenseParams['hostel_id'] = $hostelId;
    }

    $expStmt = $pdo->prepare($expenseSql);
    $expStmt->execute($expenseParams);
    $totalExpense = (float)($expStmt->fetchColumn() ?: 0);

    $mealsSql = "SELECT 
                    COALESCE(SUM(mp.breakfast), 0) + 
                    COALESCE(SUM(mp.lunch), 0) + 
                    COALESCE(SUM(mp.dinner), 0) as total_meals
                 FROM meal_preferences mp
                 JOIN boarders b ON mp.boarder_id = b.id
                 JOIN seat_allocations sa ON sa.boarder_id = b.id AND sa.status = 'active'
                 JOIN beds bd ON sa.bed_id = bd.id
                 JOIN rooms r ON bd.room_id = r.id
                 JOIN floors f ON r.floor_id = f.id
                 JOIN blocks bl ON f.block_id = bl.id
                 WHERE DATE_FORMAT(mp.meal_date, '%Y-%m') = :month";
    $mealsParams = ['month' => $targetMonth];

    if ($hostelId) {
        $mealsSql .= " AND bl.hostel_id = :hostel_id";
        $mealsParams['hostel_id'] = $hostelId;
    }

    $mealsStmt = $pdo->prepare($mealsSql);
    $mealsStmt->execute($mealsParams);
    $totalMeals = (int)($mealsStmt->fetchColumn() ?: 0);

    if ($totalMeals > 0 && $totalExpense > 0) {
        return round($totalExpense / $totalMeals, 2);
    }

    return 48.00;
}


function getUnreadMessagesCount(int $userId): int {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM messages m
            JOIN conversations c ON m.conversation_id = c.id
            WHERE (c.user_one_id = :uid OR c.user_two_id = :uid)
              AND m.sender_id != :uid
              AND m.is_read = 0
        ");
        $stmt->execute(['uid' => $userId]);
        return (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        return 0;
    }
}


function sendJsonResponse(bool $success, string $message = '', array $data = [], int $httpCode = 200): void {
    http_response_code($httpCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}


function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type, // success, error, warning, info
        'message' => $message
    ];
}


function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
