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

$today = date('Y-m-d');
$currentMonth = date('Y-m');
$dayName = date('l'); // e.g. "Monday"

$mealCountsStmt = $pdo->prepare("
    SELECT 
        COALESCE(SUM(mp.breakfast), 0) as breakfast_count,
        COALESCE(SUM(mp.lunch), 0) as lunch_count,
        COALESCE(SUM(mp.dinner), 0) as dinner_count
    FROM meal_preferences mp
    JOIN boarders b ON mp.boarder_id = b.id
    JOIN seat_allocations sa ON sa.boarder_id = b.id AND sa.status = 'active'
    JOIN beds bd ON sa.bed_id = bd.id
    JOIN rooms r ON bd.room_id = r.id
    JOIN floors f ON r.floor_id = f.id
    JOIN blocks bl ON f.block_id = bl.id
    WHERE bl.hostel_id = ? AND mp.meal_date = ?
");
$mealCountsStmt->execute([$hostelId, $today]);
$todayCounts = $mealCountsStmt->fetch();

$bCount = (int)($todayCounts['breakfast_count'] ?? 0);
$lCount = (int)($todayCounts['lunch_count'] ?? 0);
$dCount = (int)($todayCounts['dinner_count'] ?? 0);
$todayTotalMeals = $bCount + $lCount + $dCount;

$expStmt = $pdo->prepare("SELECT COALESCE(SUM(amount), 0) FROM bazar_expenses WHERE hostel_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?");
$expStmt->execute([$hostelId, $currentMonth]);
$monthlyExpenses = (float)$expStmt->fetchColumn();

$perMealCost = calculatePerMealCost($hostelId, $currentMonth);

$menuStmt = $pdo->prepare("
    SELECT mi.*
    FROM menu_items mi
    JOIN weekly_menus wm ON mi.weekly_menu_id = wm.id
    WHERE wm.hostel_id = ? AND wm.status = 'published' AND mi.day = ?
    ORDER BY FIELD(mi.meal_type, 'Breakfast', 'Lunch', 'Dinner')
");
$menuStmt->execute([$hostelId, $dayName]);
$todayMenu = $menuStmt->fetchAll();

$page_title = 'Mess Dashboard';
$page_heading = 'Mess & Dining Management';
$page_subheading = e($assignedHostel['name']) . ' - Real-time meal headcounts and culinary inventory';

require_once __DIR__ . '/../includes/header.php';
?>
<div class="dashboard-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="page-content">
      <!-- High Level Meal Metrics -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Breakfast Orders</div>
            <div class="stat-value font-mono"><?= $bCount ?></div>
            <div class="stat-sub"><span>Morning preparation</span></div>
          </div>
          <div class="stat-icon icon-amber">🍳</div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Lunch Orders</div>
            <div class="stat-value font-mono"><?= $lCount ?></div>
            <div class="stat-sub"><span>Midday service</span></div>
          </div>
          <div class="stat-icon icon-teal">🍛</div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Dinner Orders</div>
            <div class="stat-value font-mono"><?= $dCount ?></div>
            <div class="stat-sub"><span>Evening service</span></div>
          </div>
          <div class="stat-icon icon-blue">🍲</div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Dynamic Meal Rate</div>
            <div class="stat-value font-mono" style="color: var(--primary);"><?= formatCurrency($perMealCost) ?></div>
            <div class="stat-sub"><span>Cost = Expense / Meals</span></div>
          </div>
          <div class="stat-icon icon-green">📊</div>
        </div>
      </div>

      <!-- Quick Action Buttons -->
      <div style="display: flex; gap: 1rem; margin-bottom: 1.75rem; flex-wrap: wrap;">
        <a href="<?= BASE_URL ?>/mess_staff/daily_meals.php" class="btn btn-primary">
          📋 View Full Daily Meal Tally
        </a>
        <a href="<?= BASE_URL ?>/mess_staff/weekly_menu.php" class="btn btn-secondary">
          📅 Update Weekly Menu
        </a>
        <a href="<?= BASE_URL ?>/mess_staff/expenses.php" class="btn btn-secondary">
          🛒 Record Bazar Expenses
        </a>
      </div>

      <!-- Today's Dining Menu Showcase -->
      <div class="card">
        <div class="card-header">
          <div>
            <h3 class="card-title">Today's Kitchen Menu (<?= $dayName ?> - <?= formatDate($today) ?>)</h3>
            <p class="card-subtitle">Published dining selections for breakfast, lunch, and dinner</p>
          </div>
          <a href="<?= BASE_URL ?>/mess_staff/weekly_menu.php" class="btn btn-outline btn-sm">Manage Schedule</a>
        </div>

        <div class="facilities-grid">
          <?php if (!empty($todayMenu)): ?>
            <?php foreach ($todayMenu as $tm): ?>
              <div class="facility-card" style="border-top: 3px solid var(--primary);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                  <span class="badge badge-info" style="font-size: 0.8rem;"><?= e($tm['meal_type']) ?></span>
                  <span class="font-mono" style="font-size: 0.8rem; font-weight: 700;">
                    <?= $tm['meal_type'] === 'Breakfast' ? $bCount : ($tm['meal_type'] === 'Lunch' ? $lCount : $dCount) ?> Plates
                  </span>
                </div>
                <p style="font-size: 0.95rem; color: var(--text-dark); line-height: 1.5;">
                  <?= e($tm['menu_description']) ?>
                </p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div style="grid-column: 1 / -1; padding: 2rem; text-align: center; color: var(--text-muted);">
              No published menu found for <?= $dayName ?>. <a href="<?= BASE_URL ?>/mess_staff/weekly_menu.php" style="color: var(--primary); font-weight: 600;">Configure Menu &rarr;</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div> <!-- End page-content -->
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
