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

$menuStmt = $pdo->prepare("SELECT * FROM weekly_menus WHERE hostel_id = ? ORDER BY id DESC LIMIT 1");
$menuStmt->execute([$hostelId]);
$weeklyMenu = $menuStmt->fetch();

if (!$weeklyMenu) {
    $insM = $pdo->prepare("INSERT INTO weekly_menus (hostel_id, week_start_date, status, created_by, created_at) VALUES (?, CURDATE(), 'draft', ?, NOW())");
    $insM->execute([$hostelId, $user['id']]);
    $menuId = (int)$pdo->lastInsertId();
    $weeklyMenu = ['id' => $menuId, 'status' => 'draft', 'week_start_date' => date('Y-m-d')];
} else {
    $menuId = (int)$weeklyMenu['id'];
}

$itemsStmt = $pdo->prepare("SELECT * FROM menu_items WHERE weekly_menu_id = ?");
$itemsStmt->execute([$menuId]);
$allItems = $itemsStmt->fetchAll();

$itemsMap = [];
foreach ($allItems as $item) {
    $itemsMap[$item['day']][$item['meal_type']] = $item['menu_description'];
}

$days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$mealTypes = ['Breakfast', 'Lunch', 'Dinner'];

$page_title = 'Weekly Menu';
$page_heading = 'Weekly Dining Schedule';
$page_subheading = e($assignedHostel['name']) . ' - Plan and publish nutritious meals for the week';

require_once __DIR__ . '/../includes/header.php';
?>
<div class="dashboard-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="page-content">
      <div class="table-toolbar">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <span style="font-weight: 600;">Status:</span>
          <span class="badge badge-<?= $weeklyMenu['status'] === 'published' ? 'vacant' : 'warning' ?>" style="font-size: 0.85rem;">
            <?= ucfirst(e($weeklyMenu['status'])) ?>
          </span>
        </div>

        <div style="display: flex; gap: 0.75rem;">
          <button class="btn btn-secondary" onclick="openModal('editItemModal')">
            ✏️ Edit Meal Item
          </button>

          <?php if ($weeklyMenu['status'] !== 'published'): ?>
            <form method="POST" action="<?= BASE_URL ?>/actions/menu.php" style="display: inline;">
              <input type="hidden" name="action" value="publish">
              <input type="hidden" name="weekly_menu_id" value="<?= $menuId ?>">
              <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
              <button type="submit" class="btn btn-primary">Publish to Boarders</button>
            </form>
          <?php endif; ?>
        </div>
      </div>

      <!-- 7-Day Menu Grid -->
      <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        <?php foreach ($days as $day): ?>
          <div class="card">
            <div class="card-header" style="margin-bottom: 0.85rem; padding-bottom: 0.5rem;">
              <h3 class="card-title" style="color: var(--primary);"><?= $day ?></h3>
              <button class="btn btn-secondary btn-sm" onclick='openDayEditor("<?= $day ?>")'>
                Quick Edit <?= $day ?>
              </button>
            </div>

            <div class="facilities-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem;">
              <?php foreach ($mealTypes as $meal): ?>
                <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem;">
                  <span class="badge badge-info" style="font-size: 0.75rem; margin-bottom: 0.5rem;"><?= $meal ?></span>
                  <p style="font-size: 0.9rem; color: var(--text-dark); min-height: 48px; line-height: 1.45;">
                    <?= e($itemsMap[$day][$meal] ?? 'No meal defined yet') ?>
                  </p>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div> <!-- End page-content -->

    <!-- Edit Meal Item Modal -->
    <div class="modal-backdrop" id="editItemModal">
      <div class="modal-dialog">
        <div class="modal-header">
          <h3>Edit Meal Description</h3>
          <button class="modal-close" onclick="closeModal('editItemModal')">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/actions/menu.php" data-validate="true">
          <input type="hidden" name="action" value="save_item">
          <input type="hidden" name="weekly_menu_id" value="<?= $menuId ?>">
          <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

          <div class="modal-body">
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Day of Week <span class="required">*</span></label>
                <select name="day" id="edit_day" class="form-control" required>
                  <?php foreach ($days as $d): ?>
                    <option value="<?= $d ?>"><?= $d ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label">Meal Type <span class="required">*</span></label>
                <select name="meal_type" id="edit_meal" class="form-control" required>
                  <option value="Breakfast">Breakfast</option>
                  <option value="Lunch">Lunch</option>
                  <option value="Dinner">Dinner</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Meal Description & Dishes <span class="required">*</span></label>
              <textarea name="menu_description" id="edit_desc" class="form-control" rows="3" required placeholder="e.g. Steamed Katari Rice, Rui Fish Curry with Potato, Mixed Dal, Salad"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('editItemModal')">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Menu Item</button>
          </div>
        </form>
      </div>
    </div>

  <script>
    function openDayEditor(day) {
      document.getElementById('edit_day').value = day;
      openModal('editItemModal');
    }
  </script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
