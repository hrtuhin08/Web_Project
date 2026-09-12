<?php


declare(strict_types=1);
require_once __DIR__ . '/functions.php';

$user = currentUser();
if (!$user) return;

$currentScript = $_SERVER['PHP_SELF'];
$unreadMessagesCount = getUnreadMessagesCount((int)$user['id']);

function isActiveNav(string $path, string $currentScript): string {
    return str_contains($currentScript, $path) ? 'active' : '';
}
?>
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
        <polyline points="9 22 9 12 15 12 15 22"></polyline>
      </svg>
    </div>
    <div class="brand-text">
      <h2>HOSTEL MGR</h2>
      <span>Seat & Mess System</span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <span class="nav-section-title">Mess & Dining</span>

    <a href="<?= BASE_URL ?>/mess_staff/dashboard.php" class="nav-link <?= isActiveNav('/mess_staff/dashboard.php', $currentScript) ?>">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
      <span>Dashboard</span>
    </a>

    <a href="<?= BASE_URL ?>/mess_staff/daily_meals.php" class="nav-link <?= isActiveNav('/mess_staff/daily_meals.php', $currentScript) ?>">
      <svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
      <span>Daily Meals Attendance</span>
    </a>

    <a href="<?= BASE_URL ?>/mess_staff/weekly_menu.php" class="nav-link <?= isActiveNav('/mess_staff/weekly_menu.php', $currentScript) ?>">
      <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
      <span>Weekly Menu</span>
    </a>

    <a href="<?= BASE_URL ?>/mess_staff/expenses.php" class="nav-link <?= isActiveNav('/mess_staff/expenses.php', $currentScript) ?>">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="6" x2="12" y2="18"></line><line x1="9" y1="10" x2="15" y2="10"></line></svg>
      <span>Bazar Expenses</span>
    </a>

    <a href="<?= BASE_URL ?>/mess_staff/chat.php" class="nav-link <?= isActiveNav('/mess_staff/chat.php', $currentScript) ?>">
      <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
      <span>Manager Chat</span>
      <?php if ($unreadMessagesCount > 0): ?>
        <span class="nav-badge"><?= $unreadMessagesCount ?></span>
      <?php endif; ?>
    </a>

    <span class="nav-section-title">Settings</span>
    <a href="<?= BASE_URL ?>/mess_staff/profile.php" class="nav-link <?= isActiveNav('/mess_staff/profile.php', $currentScript) ?>">
      <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
      <span>My Profile</span>
    </a>

    <a href="<?= BASE_URL ?>/logout.php" class="nav-link" style="color: #f87171;">
      <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
      <span>Sign Out</span>
    </a>
  </nav>

  <div class="sidebar-user">
    <div class="user-avatar">
      <?= strtoupper(substr($user['name'], 0, 1)) ?>
    </div>
    <div class="user-info">
      <div class="user-name"><?= e($user['name']) ?></div>
      <div class="user-role">Mess Staff</div>
    </div>
  </div>
</aside>
<div class="sidebar-backdrop"></div>
