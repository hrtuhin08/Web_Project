

document.addEventListener('DOMContentLoaded', () => {
  const messagesContainer = document.getElementById('chatMessagesContainer');
  const chatForm = document.getElementById('chatForm');
  const messageInput = document.getElementById('chatMessageInput');
  const activeConversationId = document.getElementById('activeConversationId')?.value;
  const currentUserId = document.getElementById('currentUserId')?.value;

  if (!messagesContainer || !activeConversationId) {
    return;
  }

  scrollToBottom();

  if (chatForm) {
    chatForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const text = messageInput.value.trim();
      if (!text) return;

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      messageInput.value = '';

      try {
        const baseUrl = window.BASE_URL || '';
        const res = await fetch(`${baseUrl}/actions/message.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            action: 'send_message',
            conversation_id: activeConversationId,
            message: text,
            csrf_token: csrfToken
          })
        });

        const data = await res.json();
        if (data.success && data.data) {
          appendMessage(data.data);
          scrollToBottom();
        } else {
          showToast('error', data.message || 'Failed to deliver message.');
        }
      } catch (err) {
        console.error('Chat send error:', err);
      }
    });
  }

  let lastMessageId = getLastMessageId();
  setInterval(async () => {
    try {
      const baseUrl = window.BASE_URL || '';
      const res = await fetch(`${baseUrl}/actions/message.php?action=get_updates&conversation_id=${activeConversationId}&last_id=${lastMessageId}`);
      if (res.ok) {
        const data = await res.json();
        if (data.success && data.data && data.data.length > 0) {
          data.data.forEach(msg => {
            appendMessage(msg);
            lastMessageId = Math.max(lastMessageId, msg.id);
          });
          scrollToBottom();
        }
      }
    } catch (err) {
    }
  }, 3000);

  function getLastMessageId() {
    const bubbles = document.querySelectorAll('.chat-bubble[data-msg-id]');
    if (bubbles.length === 0) return 0;
    const last = bubbles[bubbles.length - 1];
    return parseInt(last.dataset.msgId, 10) || 0;
  }

  function appendMessage(msg) {
    if (document.querySelector(`.chat-bubble[data-msg-id="${msg.id}"]`)) return;

    const isSent = parseInt(msg.sender_id, 10) === parseInt(currentUserId, 10);
    const bubble = document.createElement('div');
    bubble.className = `chat-bubble ${isSent ? 'sent' : 'received'}`;
    bubble.dataset.msgId = msg.id;

    bubble.innerHTML = `
      <div class="bubble-text">${escapeHtml(msg.message)}</div>
      <div class="bubble-meta">${msg.formatted_time || 'Just now'}</div>
    `;

    messagesContainer.appendChild(bubble);
  }

  function scrollToBottom() {
    if (messagesContainer) {
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
});
