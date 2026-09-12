<?php


declare(strict_types=1);
require_once __DIR__ . '/functions.php';

$currentUser = currentUser();
?>
<header class="topbar">
  <div class="topbar-left">
    <button class="btn-sidebar-toggle" aria-label="Toggle Navigation">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="3" y1="12" x2="21" y2="12"></line>
        <line x1="3" y1="6" x2="21" y2="6"></line>
        <line x1="3" y1="18" x2="21" y2="18"></line>
      </svg>
    </button>
    <div class="page-headline">
      <h1><?= e($page_heading ?? 'Dashboard') ?></h1>
      <p><?= e($page_subheading ?? 'Hostel Seat & Mess Management') ?></p>
    </div>
  </div>

  <div class="topbar-right">
    <div class="topbar-clock" id="liveClock">
      <!-- Live clock populated by JS -->
      <span>Loading time...</span>
    </div>

    <?php if ($currentUser): ?>
      <div class="user-pill" style="display: flex; align-items: center; gap: 0.75rem;">
        <div class="user-avatar">
          <?= strtoupper(substr($currentUser['name'], 0, 1)) ?>
        </div>
        <div style="display: flex; flex-direction: column; line-height: 1.2;">
          <span style="font-weight: 600; font-size: 0.88rem; color: var(--text-dark);"><?= e($currentUser['name']) ?></span>
          <span style="font-size: 0.72rem; color: var(--text-muted); text-transform: capitalize;"><?= e(str_replace('_', ' ', $currentUser['role'])) ?></span>
        </div>
        <a href="<?= BASE_URL ?>/logout.php" class="btn btn-secondary btn-sm" title="Log Out" style="margin-left: 0.5rem; padding: 0.35rem 0.6rem;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          <span style="display: none;">Logout</span>
        </a>
      </div>
    <?php endif; ?>
  </div>
</header>
