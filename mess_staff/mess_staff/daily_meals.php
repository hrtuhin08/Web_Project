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

$targetDate = trim($_GET['date'] ?? date('Y-m-d'));

$boardersStmt = $pdo->prepare("
    SELECT 
        b.id as boarder_id,
        b.student_id,
        u.name as boarder_name,
        r.room_number,
        bd.bed_number,
        COALESCE(mp.breakfast, 1) as breakfast,
        COALESCE(mp.lunch, 1) as lunch,
        COALESCE(mp.dinner, 1) as dinner
    FROM seat_allocations sa
    JOIN boarders b ON sa.boarder_id = b.id
    JOIN users u ON b.user_id = u.id
    JOIN beds bd ON sa.bed_id = bd.id
    JOIN rooms r ON bd.room_id = r.id
    JOIN floors f ON r.floor_id = f.id
    JOIN blocks bl ON f.block_id = bl.id
    LEFT JOIN meal_preferences mp ON mp.boarder_id = b.id AND mp.meal_date = :mdate
    WHERE bl.hostel_id = :hid AND sa.status = 'active'
    ORDER BY r.room_number ASC, bd.bed_number ASC
");
$boardersStmt->execute(['hid' => $hostelId, 'mdate' => $targetDate]);
$boarderMeals = $boardersStmt->fetchAll();

$breakfastTotal = 0;
$lunchTotal = 0;
$dinnerTotal = 0;

foreach ($boarderMeals as $bm) {
    if ((int)$bm['breakfast'] === 1) $breakfastTotal++;
    if ((int)$bm['lunch'] === 1) $lunchTotal++;
    if ((int)$bm['dinner'] === 1) $dinnerTotal++;
}
$grandTotalMeals = $breakfastTotal + $lunchTotal + $dinnerTotal;

$page_title = 'Daily Meals Attendance';
$page_heading = 'Daily Meals Production Tally';
$page_subheading = e($assignedHostel['name']) . ' - Resident meal preferences for kitchen catering';

require_once __DIR__ . '/../includes/header.php';
?>
<div class="dashboard-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="page-content">
      <div class="table-toolbar">
        <form method="GET" action="<?= BASE_URL ?>/mess_staff/daily_meals.php" style="display: flex; gap: 0.75rem; align-items: center;">
          <label class="form-label" style="margin: 0;">Date:</label>
          <input type="date" name="date" class="form-control" style="width: auto;" value="<?= e($targetDate) ?>" onchange="this.form.submit()">
          <button type="submit" class="btn btn-secondary">Load Tally</button>
        </form>

        <button onclick="window.print()" class="btn btn-outline">
          🖨️ Print Kitchen Slip
        </button>
      </div>

      <!-- Tally Counter Summary Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Breakfast Preparation</div>
            <div class="stat-value font-mono"><?= $breakfastTotal ?></div>
            <div class="stat-sub"><span>Plates required</span></div>
          </div>
          <div class="stat-icon icon-amber">🍳</div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Lunch Preparation</div>
            <div class="stat-value font-mono"><?= $lunchTotal ?></div>
            <div class="stat-sub"><span>Plates required</span></div>
          </div>
          <div class="stat-icon icon-teal">🍛</div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Dinner Preparation</div>
            <div class="stat-value font-mono"><?= $dinnerTotal ?></div>
            <div class="stat-sub"><span>Plates required</span></div>
          </div>
          <div class="stat-icon icon-blue">🍲</div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <div class="stat-label">Total Daily Meals</div>
            <div class="stat-value font-mono" style="color: var(--primary);"><?= $grandTotalMeals ?></div>
            <div class="stat-sub"><span>Total plates on <?= formatDate($targetDate) ?></span></div>
          </div>
          <div class="stat-icon icon-green">📊</div>
        </div>
      </div>

      <!-- Resident Meals Attendance Table -->
      <div class="card">
        <div class="card-header">
          <div>
            <h3 class="card-title">Resident Meal Manifest</h3>
            <p class="card-subtitle">Date: <?= formatDate($targetDate, 'l, F j, Y') ?></p>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Resident Boarder</th>
                <th>Student ID</th>
                <th>Room & Bed</th>
                <th>Breakfast</th>
                <th>Lunch</th>
                <th>Dinner</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($boarderMeals)): ?>
                <?php foreach ($boarderMeals as $bm): ?>
                  <tr>
                    <td><strong><?= e($bm['boarder_name']) ?></strong></td>
                    <td class="font-mono"><?= e($bm['student_id']) ?></td>
                    <td><?= e($bm['room_number']) ?> &rsaquo; <?= e($bm['bed_number']) ?></td>
                    <td>
                      <span class="badge badge-<?= (int)$bm['breakfast'] === 1 ? 'vacant' : 'occupied' ?>">
                        <?= (int)$bm['breakfast'] === 1 ? 'ON' : 'OFF' ?>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-<?= (int)$bm['lunch'] === 1 ? 'vacant' : 'occupied' ?>">
                        <?= (int)$bm['lunch'] === 1 ? 'ON' : 'OFF' ?>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-<?= (int)$bm['dinner'] === 1 ? 'vacant' : 'occupied' ?>">
                        <?= (int)$bm['dinner'] === 1 ? 'ON' : 'OFF' ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" style="text-align: center; padding: 2rem;">No boarder meal records for this date.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div> <!-- End page-content -->
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
