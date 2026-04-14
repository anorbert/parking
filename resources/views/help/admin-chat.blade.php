@extends('layouts.admin')

@section('title', 'Help Chat - ParkFlow')

@push('styles')
<style>
  .hc-wrap {
    max-width: 700px; margin: 0 auto;
  }

  .hc-header {
    margin-bottom: 20px;
  }

  .hc-header h1 {
    font-size: 22px; font-weight: 700;
    color: #1F2937; margin: 0 0 4px;
  }

  .hc-header p {
    font-size: 13px; color: #6B7280; margin: 0;
  }

  .hc-conv-list {
    list-style: none; padding: 0; margin: 0;
  }

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
    box-shadow: 0 2px 8px rgba(58,158,212,0.1);
    color: inherit; text-decoration: none;
  }

  .hc-conv-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: #E0E7FF; color: #3730A3;
    font-weight: 700; font-size: 14px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .hc-conv-info { flex: 1; min-width: 0; }

  .hc-conv-name {
    font-size: 14px; font-weight: 600;
    color: #1F2937;
    display: flex; align-items: center; gap: 6px;
  }

  .hc-conv-role {
    font-size: 10px; font-weight: 600;
    padding: 1px 6px; border-radius: 4px;
    text-transform: uppercase; letter-spacing: 0.5px;
  }
  .hc-conv-role.admin { background: #DBEAFE; color: #1D4ED8; }
  .hc-conv-role.cashier { background: #D1FAE5; color: #059669; }

  .hc-conv-preview {
    font-size: 12px; color: #6B7280;
    white-space: nowrap; overflow: hidden;
    text-overflow: ellipsis; margin-top: 2px;
  }

  .hc-conv-meta {
    text-align: right; flex-shrink: 0;
  }

  .hc-conv-time {
    font-size: 10px; color: #9CA3AF;
  }

  .hc-conv-badge {
    display: inline-block;
    min-width: 18px; height: 18px;
    background: #EF4444; color: #fff;
    font-size: 10px; font-weight: 700;
    border-radius: 9px; padding: 0 5px;
    line-height: 18px; text-align: center;
    margin-top: 4px;
  }

  .hc-empty-state {
    text-align: center;
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 50px 30px;
    color: #9CA3AF;
    font-size: 13px;
  }

  .hc-empty-icon { font-size: 40px; margin-bottom: 10px; }
</style>
@endpush

@section('content')
<div class="hc-wrap">

  <div class="hc-header">
    <h1>💬 Help Chat</h1>
    <p>Support conversations with company admins and cashiers.</p>
  </div>

  @if($conversations->isEmpty())
    <div class="hc-empty-state">
      <div class="hc-empty-icon">📭</div>
      <div>No support conversations yet.<br>Messages from users will appear here.</div>
    </div>
  @else
    <ul class="hc-conv-list">
      @foreach($conversations as $conv)
        <a href="{{ route('help.chat.conversation', $conv->id) }}" class="hc-conv-item">
          <div class="hc-conv-avatar">{{ strtoupper(substr($conv->name, 0, 2)) }}</div>
          <div class="hc-conv-info">
            <div class="hc-conv-name">
              {{ $conv->name }}
              @if($conv->role_id == 2)
                <span class="hc-conv-role admin">Admin</span>
              @else
                <span class="hc-conv-role cashier">Cashier</span>
              @endif
            </div>
            <div class="hc-conv-preview">
              @if($conv->last_message)
                {{ $conv->last_message->sender_id === $user->id ? 'You: ' : '' }}{{ Str::limit($conv->last_message->message, 60) }}
              @endif
            </div>
          </div>
          <div class="hc-conv-meta">
            @if($conv->last_message)
              <div class="hc-conv-time">{{ $conv->last_message->created_at->format('M d, H:i') }}</div>
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
@endsection
