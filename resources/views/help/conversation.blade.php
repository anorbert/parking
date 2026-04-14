@extends($layout)

@section('title', 'Chat with ' . $otherUser->name . ' - ParkFlow')

@push('styles')
<style>
  .hc-wrap {
    max-width: 700px; margin: 0 auto;
    display: flex; flex-direction: column;
    height: calc(100vh - 130px);
  }

  /* ── HEADER ── */
  .hc-header {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 12px 12px 0 0;
    padding: 14px 20px;
    display: flex; align-items: center; gap: 12px;
  }

  .hc-back {
    display: flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 8px;
    background: #F3F4F6; color: #4B5563;
    text-decoration: none; transition: background 0.2s;
  }
  .hc-back:hover { background: #E5E7EB; color: #1F2937; }

  .hc-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    font-weight: 700; font-size: 13px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .hc-avatar.sa  { background: linear-gradient(135deg, #F5A800, #FFD166); color: #0D0F11; }
  .hc-avatar.ca  { background: #DBEAFE; color: #1D4ED8; }
  .hc-avatar.cs  { background: #D1FAE5; color: #059669; }
  .hc-avatar.att { background: #EDE9FE; color: #6D28D9; }

  .hc-user-info { flex: 1; min-width: 0; }
  .hc-name { font-size: 14px; font-weight: 700; color: #1F2937; }
  .hc-role-line {
    font-size: 11px; color: #6B7280;
    display: flex; align-items: center; gap: 6px;
  }

  .hc-role-dot {
    display: inline-block; width: 6px; height: 6px;
    border-radius: 50%; background: #4ADE80;
  }

  .hc-role-badge {
    font-size: 9px; font-weight: 700; padding: 1px 6px;
    border-radius: 4px; text-transform: uppercase;
  }
  .hc-role-badge.sa       { background: #FEF3C7; color: #92400E; }
  .hc-role-badge.admin    { background: #DBEAFE; color: #1D4ED8; }
  .hc-role-badge.cashier  { background: #D1FAE5; color: #059669; }
  .hc-role-badge.attendant{ background: #EDE9FE; color: #6D28D9; }

  /* ── MESSAGES ── */
  .hc-messages {
    flex: 1;
    background: #F9FAFB;
    border-left: 1px solid #E5E7EB;
    border-right: 1px solid #E5E7EB;
    padding: 20px;
    overflow-y: auto;
    display: flex; flex-direction: column; gap: 10px;
  }

  .hc-msg {
    max-width: 75%;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 13px; line-height: 1.5;
    word-wrap: break-word;
    position: relative;
  }

  .hc-msg.mine {
    align-self: flex-end;
    background: #3A9ED4; color: #fff;
    border-bottom-right-radius: 4px;
  }

  .hc-msg.theirs {
    align-self: flex-start;
    background: #fff; color: #1F2937;
    border: 1px solid #E5E7EB;
    border-bottom-left-radius: 4px;
  }

  .hc-msg-footer {
    display: flex; align-items: center; gap: 4px;
    margin-top: 3px;
  }

  .hc-msg-time {
    font-size: 10px; opacity: 0.7;
  }

  .hc-msg.mine .hc-msg-footer { justify-content: flex-end; }

  /* Read receipt checkmarks */
  .hc-read-check {
    font-size: 10px; opacity: 0.7;
  }
  .hc-read-check.read { opacity: 1; color: #BEE3F8; }

  .hc-date-sep {
    text-align: center;
    font-size: 10px; color: #9CA3AF;
    padding: 6px 0; font-weight: 600;
  }

  .hc-empty {
    text-align: center;
    color: #9CA3AF; font-size: 13px;
    margin: auto;
  }
  .hc-empty-icon { font-size: 36px; margin-bottom: 8px; }

  /* ── INPUT ── */
  .hc-input-area {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 0 0 12px 12px;
    padding: 14px 16px;
    display: flex; gap: 10px;
    align-items: flex-end;
  }

  .hc-input {
    flex: 1; border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 13px; font-family: inherit;
    resize: none; outline: none;
    min-height: 40px; max-height: 100px;
    background: #F9FAFB;
    transition: border-color 0.2s;
  }
  .hc-input:focus { border-color: #3A9ED4; background: #fff; }

  .hc-send {
    padding: 10px 18px; border: none;
    border-radius: 8px;
    background: linear-gradient(135deg, #F5A800, #FFD166);
    color: #0D0F11; font-weight: 700;
    font-size: 13px; cursor: pointer;
    display: flex; align-items: center; gap: 5px;
    transition: opacity 0.2s;
    flex-shrink: 0;
  }
  .hc-send:hover { opacity: 0.9; }
  .hc-send:disabled { opacity: 0.5; cursor: not-allowed; }

  /* ── TYPING INDICATOR ── */
  .hc-typing {
    display: none; align-self: flex-start;
    padding: 8px 14px;
    background: #fff; border: 1px solid #E5E7EB;
    border-radius: 12px; border-bottom-left-radius: 4px;
    font-size: 12px; color: #9CA3AF;
  }
  .hc-typing.show { display: flex; align-items: center; gap: 6px; }
  .hc-typing-dots span {
    display: inline-block; width: 5px; height: 5px;
    background: #9CA3AF; border-radius: 50%;
    animation: hcBounce 1.4s ease-in-out infinite both;
  }
  .hc-typing-dots span:nth-child(1) { animation-delay: -0.32s; }
  .hc-typing-dots span:nth-child(2) { animation-delay: -0.16s; }
  @keyframes hcBounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
  }
</style>
@endpush

@section('content')
@php
  $avatarClass = match($otherUser->role_id) {
      1 => 'sa', 2 => 'ca', 3 => 'cs', 4 => 'att', default => 'cs'
  };
  $roleLabel = match($otherUser->role_id) {
      1 => 'Support', 2 => 'Admin', 3 => 'Cashier', 4 => 'Attendant', default => 'User'
  };
  $roleBadgeClass = match($otherUser->role_id) {
      1 => 'sa', 2 => 'admin', 3 => 'cashier', 4 => 'attendant', default => 'cashier'
  };
@endphp

<div class="hc-wrap">

  <div class="hc-header">
    <a href="{{ route('help.chat') }}" class="hc-back">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    </a>
    <div class="hc-avatar {{ $avatarClass }}">{{ strtoupper(substr($otherUser->name, 0, 2)) }}</div>
    <div class="hc-user-info">
      <div class="hc-name">{{ $otherUser->name }}</div>
      <div class="hc-role-line">
        <span class="hc-role-badge {{ $roleBadgeClass }}">{{ $roleLabel }}</span>
        @if($otherUser->company)
          · {{ $otherUser->company->name }}
        @endif
      </div>
    </div>
  </div>

  <div class="hc-messages" id="hc-messages">
    @if($messages->isEmpty())
      <div class="hc-empty">
        <div class="hc-empty-icon">💬</div>
        <div>No messages yet.<br>Send a message to start chatting with {{ $otherUser->name }}.</div>
      </div>
    @else
      @php $lastDate = null; @endphp
      @foreach($messages as $msg)
        @if($msg->created_at->format('Y-m-d') !== $lastDate)
          @php $lastDate = $msg->created_at->format('Y-m-d'); @endphp
          <div class="hc-date-sep">{{ $msg->created_at->format('M d, Y') }}</div>
        @endif
        <div class="hc-msg {{ $msg->sender_id === $user->id ? 'mine' : 'theirs' }}">
          {{ $msg->message }}
          <div class="hc-msg-footer">
            <span class="hc-msg-time">{{ $msg->created_at->format('H:i') }}</span>
            @if($msg->sender_id === $user->id)
              <span class="hc-read-check {{ $msg->is_read ? 'read' : '' }}">
                @if($msg->is_read) ✓✓ @else ✓ @endif
              </span>
            @endif
          </div>
        </div>
      @endforeach
    @endif
    <div class="hc-typing" id="hc-typing">
      <div class="hc-typing-dots"><span></span><span></span><span></span></div>
      <span>typing...</span>
    </div>
  </div>

  <div class="hc-input-area">
    <textarea class="hc-input" id="hc-input" placeholder="Type a message to {{ $otherUser->name }}..." rows="1"></textarea>
    <button class="hc-send" id="hc-send" disabled>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      Send
    </button>
  </div>

</div>
@endsection

@push('scripts')
<script>
(function() {
  var messagesEl = document.getElementById('hc-messages');
  var inputEl = document.getElementById('hc-input');
  var sendBtn = document.getElementById('hc-send');
  var receiverId = {{ $otherUser->id }};
  var csrfToken = '{{ csrf_token() }}';
  var lastMsgCount = {{ $messages->count() }};

  function scrollBottom() {
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }
  scrollBottom();

  // Enable/disable send
  inputEl.addEventListener('input', function() {
    sendBtn.disabled = !this.value.trim();
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
  });

  // Send on Enter (Shift+Enter for newline)
  inputEl.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      if (this.value.trim()) sendBtn.click();
    }
  });

  // Send message
  sendBtn.addEventListener('click', function() {
    var msg = inputEl.value.trim();
    if (!msg) return;

    sendBtn.disabled = true;

    // Remove empty state
    var emptyEl = messagesEl.querySelector('.hc-empty');
    if (emptyEl) emptyEl.remove();

    // Optimistic UI
    var div = document.createElement('div');
    div.className = 'hc-msg mine';
    div.innerHTML = escapeHtml(msg) +
      '<div class="hc-msg-footer">' +
        '<span class="hc-msg-time">now</span>' +
        '<span class="hc-read-check">✓</span>' +
      '</div>';
    messagesEl.insertBefore(div, document.getElementById('hc-typing'));
    scrollBottom();

    inputEl.value = '';
    inputEl.style.height = 'auto';

    fetch('{{ route("help.chat.send") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ message: msg, receiver_id: receiverId })
    }).then(function(r) { return r.json(); }).then(function() {
      lastMsgCount++;
    }).catch(function() {
      div.style.opacity = '0.5';
    });
  });

  // Poll for new messages
  setInterval(function() {
    fetch('{{ route("help.chat.messages") }}?user_id=' + receiverId)
      .then(function(r) { return r.json(); })
      .then(function(msgs) {
        if (msgs.length > lastMsgCount) {
          var newMsgs = msgs.slice(lastMsgCount);
          newMsgs.forEach(function(m) {
            if (!m.is_mine) {
              var div = document.createElement('div');
              div.className = 'hc-msg theirs';
              div.innerHTML = escapeHtml(m.message) +
                '<div class="hc-msg-footer">' +
                  '<span class="hc-msg-time">' + m.time + '</span>' +
                '</div>';
              messagesEl.insertBefore(div, document.getElementById('hc-typing'));
            }
          });
          lastMsgCount = msgs.length;
          scrollBottom();
        }

        // Update read receipts on our sent messages
        var myMsgs = msgs.filter(function(m) { return m.is_mine; });
        var checkEls = messagesEl.querySelectorAll('.hc-msg.mine .hc-read-check');
        myMsgs.forEach(function(m, i) {
          if (checkEls[i] && m.is_read) {
            checkEls[i].className = 'hc-read-check read';
            checkEls[i].innerHTML = '✓✓';
          }
        });
      });
  }, 3000);

  function escapeHtml(str) {
    var d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
  }
})();
</script>
@endpush
