@extends($layout)

@section('title', 'Chat - ParkFlow')

@push('styles')
<style>
  .hc-wrap {
    max-width: 750px; margin: 0 auto;
  }

  /* ── HEADER ── */
  .hc-page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px;
  }
  .hc-page-header h1 {
    font-size: 22px; font-weight: 700; color: #1F2937; margin: 0 0 2px;
  }
  .hc-page-header p {
    font-size: 12px; color: #6B7280; margin: 0;
  }

  .hc-new-chat-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border: none; border-radius: 8px;
    background: linear-gradient(135deg, #3A9ED4, #2B7CAF);
    color: #fff; font-weight: 600; font-size: 13px;
    cursor: pointer; transition: opacity 0.2s; text-decoration: none;
  }
  .hc-new-chat-btn:hover { opacity: 0.9; color: #fff; text-decoration: none; }

  /* ── SEARCH ── */
  .hc-search-box {
    position: relative; margin-bottom: 16px;
  }
  .hc-search-box input {
    width: 100%; padding: 10px 14px 10px 36px;
    border: 1px solid #E5E7EB; border-radius: 10px;
    font-size: 13px; background: #fff;
    outline: none; transition: border-color 0.2s;
    box-sizing: border-box;
  }
  .hc-search-box input:focus { border-color: #3A9ED4; }
  .hc-search-icon {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #9CA3AF;
  }

  /* ── CONVERSATION LIST ── */
  .hc-conv-list { list-style: none; padding: 0; margin: 0; }

  .hc-conv-item {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 8px;
    display: flex; align-items: center; gap: 12px;
    text-decoration: none; color: inherit;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .hc-conv-item:hover {
    border-color: #3A9ED4;
    box-shadow: 0 2px 8px rgba(58,158,212,0.08);
    color: inherit; text-decoration: none;
  }
  .hc-conv-item.has-unread {
    background: #F0F9FF;
    border-color: #BAE6FD;
  }

  .hc-conv-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    font-weight: 700; font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    position: relative;
  }
  .hc-conv-avatar.sa  { background: linear-gradient(135deg, #F5A800, #FFD166); color: #0D0F11; }
  .hc-conv-avatar.ca  { background: #DBEAFE; color: #1D4ED8; }
  .hc-conv-avatar.cs  { background: #D1FAE5; color: #059669; }
  .hc-conv-avatar.att { background: #EDE9FE; color: #6D28D9; }

  .hc-conv-info { flex: 1; min-width: 0; }

  .hc-conv-name {
    font-size: 14px; font-weight: 600; color: #1F2937;
    display: flex; align-items: center; gap: 6px;
  }

  .hc-role-tag {
    font-size: 9px; font-weight: 700; padding: 1px 6px;
    border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;
  }
  .hc-role-tag.sa       { background: #FEF3C7; color: #92400E; }
  .hc-role-tag.admin    { background: #DBEAFE; color: #1D4ED8; }
  .hc-role-tag.cashier  { background: #D1FAE5; color: #059669; }
  .hc-role-tag.attendant{ background: #EDE9FE; color: #6D28D9; }

  .hc-conv-company {
    font-size: 10px; color: #9CA3AF; margin-top: 1px;
  }

  .hc-conv-preview {
    font-size: 12px; color: #6B7280;
    white-space: nowrap; overflow: hidden;
    text-overflow: ellipsis; margin-top: 3px;
  }
  .hc-conv-item.has-unread .hc-conv-preview {
    color: #1F2937; font-weight: 600;
  }

  .hc-conv-meta { text-align: right; flex-shrink: 0; }

  .hc-conv-time { font-size: 10px; color: #9CA3AF; }

  .hc-conv-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 18px; height: 18px;
    background: #3A9ED4; color: #fff;
    font-size: 10px; font-weight: 700;
    border-radius: 9px; padding: 0 5px;
    margin-top: 4px;
  }

  /* ── EMPTY STATE ── */
  .hc-empty-state {
    text-align: center; background: #fff;
    border: 1px solid #E5E7EB; border-radius: 12px;
    padding: 50px 30px; color: #9CA3AF; font-size: 13px;
  }
  .hc-empty-icon { font-size: 40px; margin-bottom: 10px; }

  /* ── NEW CHAT MODAL ── */
  .hc-modal-overlay {
    display: none; position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.4); z-index: 9999;
    align-items: center; justify-content: center;
  }
  .hc-modal-overlay.open { display: flex; }

  .hc-modal {
    background: #fff; border-radius: 14px;
    width: 440px; max-width: 90vw; max-height: 80vh;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    display: flex; flex-direction: column;
    overflow: hidden;
  }

  .hc-modal-header {
    padding: 18px 20px; border-bottom: 1px solid #E5E7EB;
    display: flex; align-items: center; justify-content: space-between;
  }
  .hc-modal-header h3 {
    font-size: 16px; font-weight: 700; color: #1F2937; margin: 0;
  }
  .hc-modal-close {
    background: none; border: none; font-size: 20px;
    color: #9CA3AF; cursor: pointer; padding: 0;
    line-height: 1;
  }
  .hc-modal-close:hover { color: #1F2937; }

  .hc-modal-search {
    padding: 12px 16px; border-bottom: 1px solid #F3F4F6;
  }
  .hc-modal-search input {
    width: 100%; padding: 8px 12px; border: 1px solid #E5E7EB;
    border-radius: 8px; font-size: 13px; outline: none;
    box-sizing: border-box;
  }
  .hc-modal-search input:focus { border-color: #3A9ED4; }

  .hc-modal-body {
    flex: 1; overflow-y: auto; padding: 8px 0;
  }

  .hc-contact-group-label {
    font-size: 10px; font-weight: 700; color: #9CA3AF;
    text-transform: uppercase; letter-spacing: 0.5px;
    padding: 8px 20px 4px;
  }

  .hc-contact-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 20px; cursor: pointer;
    text-decoration: none; color: inherit;
    transition: background 0.15s;
  }
  .hc-contact-item:hover {
    background: #F3F4F6; color: inherit; text-decoration: none;
  }

  .hc-contact-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    font-weight: 700; font-size: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .hc-contact-name {
    font-size: 13px; font-weight: 600; color: #1F2937;
  }
  .hc-contact-role {
    font-size: 11px; color: #6B7280;
  }

  .hc-no-contacts {
    text-align: center; padding: 30px;
    color: #9CA3AF; font-size: 13px;
  }
</style>
@endpush

@section('content')
<div class="hc-wrap">

  <div class="hc-page-header">
    <div>
      <h1>💬 Messages</h1>
      <p>Chat with {{ $user->isSuperAdmin() ? 'all users' : 'support & your team' }}</p>
    </div>
    <button class="hc-new-chat-btn" id="hc-new-chat-btn">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      New Chat
    </button>
  </div>

  <div class="hc-search-box">
    <svg class="hc-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" id="hc-search" placeholder="Search conversations...">
  </div>

  @if($conversations->isEmpty() && $availableContacts->isEmpty())
    <div class="hc-empty-state">
      <div class="hc-empty-icon">📭</div>
      <div>No conversations yet.<br>Start a new chat to begin messaging.</div>
    </div>
  @elseif($conversations->isEmpty())
    <div class="hc-empty-state">
      <div class="hc-empty-icon">💬</div>
      <div>No conversations yet.<br>Click <strong>New Chat</strong> to start messaging.</div>
    </div>
  @else
    <ul class="hc-conv-list" id="hc-conv-list">
      @foreach($conversations as $conv)
        @php
          $avatarClass = match($conv->role_id) {
              1 => 'sa', 2 => 'ca', 3 => 'cs', 4 => 'att', default => 'cs'
          };
          $roleLabel = match($conv->role_id) {
              1 => 'Support', 2 => 'Admin', 3 => 'Cashier', 4 => 'Attendant', default => 'User'
          };
          $roleClass = match($conv->role_id) {
              1 => 'sa', 2 => 'admin', 3 => 'cashier', 4 => 'attendant', default => 'cashier'
          };
        @endphp
        <a href="{{ route('help.chat.conversation', $conv->id) }}"
           class="hc-conv-item {{ $conv->unread_count > 0 ? 'has-unread' : '' }}"
           data-name="{{ strtolower($conv->name) }}"
           data-role="{{ strtolower($roleLabel) }}">
          <div class="hc-conv-avatar {{ $avatarClass }}">{{ strtoupper(substr($conv->name, 0, 2)) }}</div>
          <div class="hc-conv-info">
            <div class="hc-conv-name">
              {{ $conv->name }}
              <span class="hc-role-tag {{ $roleClass }}">{{ $roleLabel }}</span>
            </div>
            @if($conv->company && $user->isSuperAdmin())
              <div class="hc-conv-company">{{ $conv->company->name }}</div>
            @endif
            <div class="hc-conv-preview">
              @if($conv->last_message)
                {{ $conv->last_message->sender_id === $user->id ? 'You: ' : '' }}{{ Str::limit($conv->last_message->message, 55) }}
              @endif
            </div>
          </div>
          <div class="hc-conv-meta">
            @if($conv->last_message)
              <div class="hc-conv-time">{{ $conv->last_message->created_at->diffForHumans(null, true, true) }}</div>
            @endif
            @if($conv->unread_count > 0)
              <div class="hc-conv-badge">{{ $conv->unread_count }}</div>
            @endif
          </div>
        </a>
      @endforeach
    </ul>
  @endif
</div>

{{-- ── New Chat Modal ── --}}
<div class="hc-modal-overlay" id="hc-modal">
  <div class="hc-modal">
    <div class="hc-modal-header">
      <h3>New Conversation</h3>
      <button class="hc-modal-close" id="hc-modal-close">&times;</button>
    </div>
    <div class="hc-modal-search">
      <input type="text" id="hc-contact-search" placeholder="Search contacts...">
    </div>
    <div class="hc-modal-body" id="hc-contact-list">

      @php
        $supportContacts = $availableContacts->where('role_id', 1);
        $teamContacts = $availableContacts->whereIn('role_id', [2, 3, 4]);
      @endphp

      @if($supportContacts->isNotEmpty())
        <div class="hc-contact-group-label">Support</div>
        @foreach($supportContacts as $c)
          <a href="{{ route('help.chat.conversation', $c->id) }}" class="hc-contact-item" data-name="{{ strtolower($c->name) }}">
            <div class="hc-contact-avatar" style="background:#FEF3C7; color:#92400E;">{{ strtoupper(substr($c->name, 0, 2)) }}</div>
            <div>
              <div class="hc-contact-name">{{ $c->name }}</div>
              <div class="hc-contact-role">Platform Administrator</div>
            </div>
          </a>
        @endforeach
      @endif

      @if($teamContacts->isNotEmpty())
        <div class="hc-contact-group-label">Team</div>
        @foreach($teamContacts as $c)
          @php
            $cAvatar = match($c->role_id) {
                2 => 'background:#DBEAFE;color:#1D4ED8;',
                3 => 'background:#D1FAE5;color:#059669;',
                4 => 'background:#EDE9FE;color:#6D28D9;',
                default => 'background:#F3F4F6;color:#6B7280;',
            };
          @endphp
          <a href="{{ route('help.chat.conversation', $c->id) }}" class="hc-contact-item" data-name="{{ strtolower($c->name) }}">
            <div class="hc-contact-avatar" style="{{ $cAvatar }}">{{ strtoupper(substr($c->name, 0, 2)) }}</div>
            <div>
              <div class="hc-contact-name">{{ $c->name }}</div>
              <div class="hc-contact-role">{{ $c->role->name ?? 'User' }}{{ $c->company ? ' · ' . $c->company->name : '' }}</div>
            </div>
          </a>
        @endforeach
      @endif

      @if($availableContacts->isEmpty())
        <div class="hc-no-contacts">All contacts already have conversations.</div>
      @endif

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
  // Search conversations
  var searchEl = document.getElementById('hc-search');
  if (searchEl) {
    searchEl.addEventListener('input', function() {
      var q = this.value.toLowerCase();
      document.querySelectorAll('.hc-conv-item').forEach(function(item) {
        var name = item.getAttribute('data-name') || '';
        var role = item.getAttribute('data-role') || '';
        item.style.display = (name.includes(q) || role.includes(q)) ? '' : 'none';
      });
    });
  }

  // New Chat modal
  var modal = document.getElementById('hc-modal');
  var openBtn = document.getElementById('hc-new-chat-btn');
  var closeBtn = document.getElementById('hc-modal-close');

  if (openBtn) {
    openBtn.addEventListener('click', function() { modal.classList.add('open'); });
  }
  if (closeBtn) {
    closeBtn.addEventListener('click', function() { modal.classList.remove('open'); });
  }
  if (modal) {
    modal.addEventListener('click', function(e) {
      if (e.target === modal) modal.classList.remove('open');
    });
  }

  // Search contacts in modal
  var contactSearch = document.getElementById('hc-contact-search');
  if (contactSearch) {
    contactSearch.addEventListener('input', function() {
      var q = this.value.toLowerCase();
      document.querySelectorAll('.hc-contact-item').forEach(function(item) {
        var name = item.getAttribute('data-name') || '';
        item.style.display = name.includes(q) ? '' : 'none';
      });
    });
  }
})();
</script>
@endpush
