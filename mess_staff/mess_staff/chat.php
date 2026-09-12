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

$mgrStmt = $pdo->prepare("
    SELECT u.id as manager_id, u.name as manager_name, u.phone as manager_phone
    FROM manager_assignments ma
    JOIN users u ON ma.manager_id = u.id
    WHERE ma.hostel_id = ?
    LIMIT 1
");
$mgrStmt->execute([$hostelId]);
$manager = $mgrStmt->fetch();

if (!$manager) {
    $manager = ['manager_id' => 2, 'manager_name' => 'Hostel Manager', 'manager_phone' => '+8801711000002'];
}

$u1 = min((int)$user['id'], (int)$manager['manager_id']);
$u2 = max((int)$user['id'], (int)$manager['manager_id']);

$convStmt = $pdo->prepare("SELECT id FROM conversations WHERE user_one_id = ? AND user_two_id = ?");
$convStmt->execute([$u1, $u2]);
$convId = (int)$convStmt->fetchColumn();

if ($convId <= 0) {
    $pdo->prepare("INSERT INTO conversations (user_one_id, user_two_id, updated_at) VALUES (?, ?, NOW())")->execute([$u1, $u2]);
    $convId = (int)$pdo->lastInsertId();
}

$msgStmt = $pdo->prepare("SELECT * FROM messages WHERE conversation_id = ? ORDER BY id ASC");
$msgStmt->execute([$convId]);
$messages = $msgStmt->fetchAll();

$pdo->prepare("UPDATE messages SET is_read = 1 WHERE conversation_id = ? AND sender_id != ?")->execute([$convId, $user['id']]);

$page_title = 'Staff Chat';
$page_heading = 'Supervisor Communications';
$page_subheading = 'Real-time chat with ' . e($manager['manager_name']) . ' (Hostel Manager)';

require_once __DIR__ . '/../includes/header.php';
?>
<div class="dashboard-layout">
  <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="main-wrapper">
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="page-content">
      <div class="chat-app" style="max-width: 900px; margin: 0 auto;">
        <div class="chat-conversation-panel" style="width: 100%;">
          <div class="chat-header">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
              <div class="user-avatar" style="width: 38px; height: 38px;">
                <?= strtoupper(substr($manager['manager_name'], 0, 1)) ?>
              </div>
              <div>
                <h4 style="font-size: 0.95rem;"><?= e($manager['manager_name']) ?></h4>
                <span style="font-size: 0.75rem; color: var(--text-muted);">
                  Hostel Manager &bull; <?= e($manager['manager_phone']) ?>
                </span>
              </div>
            </div>
            <span class="badge badge-vacant">Online</span>
          </div>

          <div class="chat-messages-container" id="chatMessagesContainer">
            <?php foreach ($messages as $msg): ?>
              <?php $isSent = (int)$msg['sender_id'] === (int)$user['id']; ?>
              <div class="chat-bubble <?= $isSent ? 'sent' : 'received' ?>" data-msg-id="<?= $msg['id'] ?>">
                <div class="bubble-text"><?= e($msg['message']) ?></div>
                <div class="bubble-meta"><?= timeAgo($msg['created_at']) ?></div>
              </div>
            <?php endforeach; ?>
          </div>

          <input type="hidden" id="activeConversationId" value="<?= $convId ?>">
          <input type="hidden" id="currentUserId" value="<?= $user['id'] ?>">

          <form class="chat-input-bar" id="chatForm">
            <input type="text" id="chatMessageInput" placeholder="Message Manager regarding bazar, budget, or menu adjustments..." required autocomplete="off">
            <button type="submit" class="btn btn-primary" style="border-radius: var(--radius-full); padding: 0.65rem 1.25rem;">
              Send
            </button>
          </form>
        </div>
      </div>
    </div> <!-- End page-content -->
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
