@extends($user->role_id == 2 ? 'layouts.admin' : 'layouts.user')

@section('title', 'Help Chat - ParkFlow')

@push('styles')
<style>
  .hc-wrap {
    max-width: 700px; margin: 0 auto;
    display: flex; flex-direction: column;
    height: calc(100vh - 130px);
  }

  .hc-header {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 12px 12px 0 0;
    padding: 16px 20px;
    display: flex; align-items: center; gap: 12px;
  }

  .hc-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, #F5A800, #FFD166);
    color: #0D0F11; font-weight: 800; font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .hc-name { font-size: 14px; font-weight: 700; color: #1F2937; }
  .hc-role { font-size: 11px; color: #6B7280; }

  .hc-status {
    margin-left: auto;
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; color: #6B7280;
  }

  .hc-status-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #4ADE80;
  }

  .hc-messages {
    flex: 1;
    background: #F9FAFB;
    border-left: 1px solid #E5E7EB;
    border-right: 1px solid #E5E7EB;
    padding: 20px;
    overflow-y: auto;
    display: flex; flex-direction: column; gap: 10px;
  }

  .hc-empty {
    text-align: center;
    color: #9CA3AF; font-size: 13px;
    margin: auto;
  }

  .hc-empty-icon { font-size: 36px; margin-bottom: 8px; }

  .hc-msg {
    max-width: 75%;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 13px; line-height: 1.5;
    word-wrap: break-word;
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

  .hc-msg-time {
    font-size: 10px; opacity: 0.7;
    margin-top: 3px;
  }

  .hc-msg.mine .hc-msg-time { text-align: right; }

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

  .hc-date-sep {
    text-align: center;
    font-size: 10px; color: #9CA3AF;
    padding: 6px 0;
    font-weight: 600;
  }
</style>
@endpush

@section('content')
<div class="hc-wrap">

  <div class="hc-header">
    <div class="hc-avatar">{{ $admin ? strtoupper(substr($admin->name, 0, 2)) : 'SA' }}</div>
    <div>
      <div class="hc-name">{{ $admin->name ?? 'Support Admin' }}</div>
      <div class="hc-role">Platform Administrator</div>
    </div>
    <div class="hc-status">
      <div class="hc-status-dot"></div>
      Support
    </div>
  </div>

  <div class="hc-messages" id="hc-messages">
    @if($messages->isEmpty())
      <div class="hc-empty">
        <div class="hc-empty-icon">💬</div>
        <div>No messages yet.<br>Send a message to start a conversation with the admin.</div>
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
          <div class="hc-msg-time">{{ $msg->created_at->format('H:i') }}</div>
        </div>
      @endforeach
    @endif
  </div>

  @if($admin)
  <div class="hc-input-area">
    <textarea class="hc-input" id="hc-input" placeholder="Type your message..." rows="1"></textarea>
    <button class="hc-send" id="hc-send" disabled>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      Send
    </button>
  </div>
  @else
  <div class="hc-input-area" style="justify-content: center; padding: 20px;">
    <span style="color: #9CA3AF; font-size: 13px;">No administrator is configured to receive messages.</span>
  </div>
  @endif

</div>
@endsection

@push('scripts')
<script>
(function() {
  const messagesEl = document.getElementById('hc-messages');
  const inputEl = document.getElementById('hc-input');
  const sendBtn = document.getElementById('hc-send');
  const receiverId = {{ $admin->id ?? 'null' }};
  const csrfToken = '{{ csrf_token() }}';
  let lastMsgCount = {{ $messages->count() }};

  if (!receiverId) return;

  // Auto-scroll to bottom
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
    const msg = inputEl.value.trim();
    if (!msg) return;

    sendBtn.disabled = true;

    // Optimistic UI
    const emptyEl = messagesEl.querySelector('.hc-empty');
    if (emptyEl) emptyEl.remove();

    const div = document.createElement('div');
    div.className = 'hc-msg mine';
    div.innerHTML = escapeHtml(msg) + '<div class="hc-msg-time">now</div>';
    messagesEl.appendChild(div);
    scrollBottom();

    inputEl.value = '';
    inputEl.style.height = 'auto';

    fetch('{{ route("help.chat.send") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ message: msg, receiver_id: receiverId })
    }).then(r => r.json()).then(() => {
      lastMsgCount++;
    }).catch(() => {
      div.style.opacity = '0.5';
    });
  });

  // Poll for new messages
  setInterval(function() {
    fetch('{{ route("help.chat.messages") }}')
      .then(r => r.json())
      .then(msgs => {
        if (msgs.length > lastMsgCount) {
          // New messages arrived
          const newMsgs = msgs.slice(lastMsgCount);
          newMsgs.forEach(m => {
            if (!m.is_mine) {
              const div = document.createElement('div');
              div.className = 'hc-msg theirs';
              div.innerHTML = m.message + '<div class="hc-msg-time">' + m.time + '</div>';
              messagesEl.appendChild(div);
            }
          });
          lastMsgCount = msgs.length;
          scrollBottom();
        }
      });
  }, 5000);

  function escapeHtml(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
  }
})();
</script>
@endpush
