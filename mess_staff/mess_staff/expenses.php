<?php


declare(strict_types=1);
$required_role = 'mess_staff';
require_once __DIR__ . '/../includes/role_check.php';

$user = currentUser();
$pdo = getDBConnection();

$hStmt = $pdo->prepare("SELECT h.* FROM mess_staff_assignments ma JOIN hostels h ON ma.hostel_id = h.id WHERE ma.mess_staff_id = ? LIMIT 1");
$hStmt->execute([$user['id']]);
$assignedHostel = $hStmt->fetch() ?: $pdo->query("SELECT * FROM hostels ORDER BY id ASC LIMIT 1")->fetch();
$hostelId = (int)$assignedHostel['id'];

$currentMonth = date('Y-m');
$today = date('Y-m-d');

$todayStmt = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM bazar_expenses WHERE hostel_id = ? AND expense_date = ?");
$todayStmt->execute([$hostelId, $today]);
$todayTotal = (float)$todayStmt->fetchColumn();

$monthStmt = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM bazar_expenses WHERE hostel_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?");
$monthStmt->execute([$hostelId, $currentMonth]);
$monthTotal = (float)$monthStmt->fetchColumn();

$mealsStmt = $pdo->prepare("
    SELECT 
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
    WHERE bl.hostel_id = ? AND DATE_FORMAT(mp.meal_date, '%Y-%m') = ?
");
$mealsStmt->execute([$hostelId, $currentMonth]);
$totalMealsConsumed = (int)($mealsStmt->fetchColumn() ?: 0);

$perMealCost = ($totalMealsConsumed > 0 && $monthTotal > 0) 
    ? round($monthTotal / $totalMealsConsumed, 2) 
    : 48.00;

$dayOfMonth = max(1, (int)date('j'));
$avgDailyExpense = round($monthTotal / $dayOfMonth, 2);

$expensesStmt = $pdo->prepare("
    SELECT * FROM bazar_expenses 
    WHERE hostel_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?
    ORDER BY expense_date DESC, id DESC
");
$expensesStmt->execute([$hostelId, $currentMonth]);
$expenses = $expensesStmt->fetchAll();

$page_title = 'Bazar Expenses';
$page_heading = 'Kitchen Bazar Expenses & Meal Rate';
$page_subheading = e($assignedHostel['name']) . ' - Expense audit and automatic per-meal rate derivation';

require_once __DIR__ . '/../includes/header.php';
?>
<div class="dashboard-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="page-content">
      <div class="table-toolbar">
        <div>
          <h3 style="font-size: 1.15rem; font-weight: 700;">Expense Ledger & Per-Meal Rate</h3>
          <p style="font-size: 0.82rem; color: var(--text-muted);">Formula: PER MEAL COST = TOTAL EXPENSE / TOTAL CONSUMED MEALS</p>
        </div>
        <button class="btn btn-primary" onclick="openModal('addExpenseModal')">+ Record Bazar Purchase</button>
      </div>

      <!-- Financial Formula Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Today's Bazar Expense</div>
            <div class="stat-value font-mono"><?= formatCurrency($todayTotal) ?></div>
            <div class="stat-sub"><span><?= formatDate($today) ?></span></div>
          </div>
          <div class="stat-icon icon-amber">🛒</div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Monthly Bazar Total</div>
            <div class="stat-value font-mono" style="color: var(--danger);"><?= formatCurrency($monthTotal) ?></div>
            <div class="stat-sub"><span><?= formatDate($currentMonth . '-01', 'F Y') ?></span></div>
          </div>
          <div class="stat-icon icon-rose">💵</div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Total Meals Consumed</div>
            <div class="stat-value font-mono"><?= $totalMealsConsumed ?></div>
            <div class="stat-sub"><span>Breakfast + Lunch + Dinner</span></div>
          </div>
          <div class="stat-icon icon-blue">🍽️</div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Dynamic Per-Meal Cost</div>
            <div class="stat-value font-mono" style="color: var(--primary);"><?= formatCurrency($perMealCost) ?></div>
            <div class="stat-sub"><span>Avg Daily: <?= formatCurrency($avgDailyExpense) ?></span></div>
          </div>
          <div class="stat-icon icon-green">⚡</div>
        </div>
      </div>

      <!-- Expenses Ledger Table -->
      <div class="card">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Item Purchased</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Amount (BDT)</th>
                <th>Notes / Market</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($expenses)): ?>
                <?php foreach ($expenses as $exp): ?>
                  <tr>
                    <td class="font-mono"><?= formatDate($exp['expense_date']) ?></td>
                    <td><strong><?= e($exp['item_name']) ?></strong></td>
                    <td><span class="badge badge-info"><?= e($exp['category']) ?></span></td>
                    <td class="font-mono"><?= e($exp['quantity']) ?></td>
                    <td class="font-mono" style="font-weight: 700; color: var(--text-dark);">
                      <?= formatCurrency($exp['amount']) ?>
                    </td>
                    <td style="font-size: 0.82rem; color: var(--text-muted);"><?= e($exp['notes'] ?: '—') ?></td>
                    <td>
                      <form method="POST" action="<?= BASE_URL ?>/actions/expense.php" onsubmit="return confirm('Delete this expense entry?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="expense_id" value="<?= $exp['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="7" style="text-align: center; padding: 2rem;">No bazar expenses recorded for this month.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div> <!-- End page-content -->

    <!-- Add Expense Modal -->
    <div class="modal-backdrop" id="addExpenseModal">
      <div class="modal-dialog">
        <div class="modal-header">
          <h3>Record Bazar Expense</h3>
          <button class="modal-close" onclick="closeModal('addExpenseModal')">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/actions/expense.php" data-validate="true">
          <input type="hidden" name="action" value="create">
          <input type="hidden" name="hostel_id" value="<?= $hostelId ?>">
          <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

          <div class="modal-body">
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Expense Date <span class="required">*</span></label>
                <input type="date" name="expense_date" class="form-control" required value="<?= date('Y-m-d') ?>">
              </div>

              <div class="form-group">
                <label class="form-label">Category <span class="required">*</span></label>
                <select name="category" class="form-control" required>
                  <option value="Rice">Rice</option>
                  <option value="Vegetables">Vegetables</option>
                  <option value="Fish">Fish</option>
                  <option value="Meat">Meat (Chicken/Beef/Mutton)</option>
                  <option value="Oil">Oil</option>
                  <option value="Spices">Spices</option>
                  <option value="Gas">Cooking Gas</option>
                  <option value="Other">Other Supply</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Item Description <span class="required">*</span></label>
                <input type="text" name="item_name" class="form-control" required placeholder="e.g. Broiler Chicken, Miniket Rice">
              </div>

              <div class="form-group">
                <label class="form-label">Quantity Purchased <span class="required">*</span></label>
                <input type="text" name="quantity" class="form-control" required placeholder="e.g. 25 kg, 2 cylinders">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Total Amount (BDT) <span class="required">*</span></label>
              <input type="number" step="0.5" name="amount" class="form-control" required placeholder="e.g. 4800.00">
            </div>

            <div class="form-group">
              <label class="form-label">Notes & Market Details</label>
              <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Karwan Bazar wholesale receipt #1204"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('addExpenseModal')">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Expense</button>
          </div>
        </form>
      </div>
    </div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
