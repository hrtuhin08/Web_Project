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
    setFlash('error', 'Security verification failed.');
    header('Location: ' . BASE_URL . '/mess_staff/expenses.php');
    exit;
}

if ($action === 'create') {
    $hostelId = (int)($_POST['hostel_id'] ?? 0);
    $expenseDate = $_POST['expense_date'] ?? date('Y-m-d');
    $itemName = trim($_POST['item_name'] ?? '');
    $category = $_POST['category'] ?? 'Other';
    $quantity = trim($_POST['quantity'] ?? '');
    $amount = (float)($_POST['amount'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');

    $categories = ['Rice', 'Vegetables', 'Fish', 'Meat', 'Oil', 'Spices', 'Gas', 'Other'];

    if ($hostelId <= 0 || empty($itemName) || !in_array($category, $categories, true) || $amount <= 0) {
        setFlash('error', 'Please fill all expense details with valid amount.');
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO bazar_expenses (hostel_id, expense_date, item_name, category, quantity, amount, notes, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$hostelId, $expenseDate, $itemName, $category, $quantity, $amount, $notes, $user['id']]);
        logActivity((int)$user['id'], 'Added Bazar Expense', "Added {$amount} for {$itemName} ({$category})");
        setFlash('success', 'Bazar expense entry recorded successfully.');
    }
} elseif ($action === 'delete') {
    $expenseId = (int)($_POST['expense_id'] ?? 0);
    if ($expenseId > 0) {
        $pdo->prepare("DELETE FROM bazar_expenses WHERE id = ?")->execute([$expenseId]);
        logActivity((int)$user['id'], 'Deleted Bazar Expense', "Deleted expense ID {$expenseId}");
        setFlash('success', 'Expense entry deleted.');
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/mess_staff/expenses.php')));
exit;
