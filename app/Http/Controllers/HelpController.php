<?php

namespace App\Http\Controllers;

use App\Models\HelpMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    /**
     * Show the "How to Use" guide page based on user role.
     */
    public function guide()
    {
        $user = Auth::user();
        return view('help.guide', compact('user'));
    }

    /**
     * Show the help chat page.
     * - SuperAdmin sees list of all conversations
     * - Other users see their chat with the SuperAdmin
     */
    public function chat()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            // Get all unique users who have sent or received messages
            $conversations = User::where('id', '!=', $user->id)
                ->whereIn('id', function ($q) use ($user) {
                    $q->select('sender_id')
                      ->from('help_messages')
                      ->where('receiver_id', $user->id)
                      ->union(
                          HelpMessage::select('receiver_id')
                              ->where('sender_id', $user->id)
                      );
                })
                ->with('role')
                ->get()
                ->map(function ($u) use ($user) {
                    $u->unread_count = HelpMessage::where('sender_id', $u->id)
                        ->where('receiver_id', $user->id)
                        ->where('is_read', false)
                        ->count();
                    $u->last_message = HelpMessage::where(function ($q) use ($u, $user) {
                            $q->where('sender_id', $u->id)->where('receiver_id', $user->id);
                        })
                        ->orWhere(function ($q) use ($u, $user) {
                            $q->where('sender_id', $user->id)->where('receiver_id', $u->id);
                        })
                        ->latest()
                        ->first();
                    return $u;
                })
                ->sortByDesc(fn ($u) => $u->last_message?->created_at);

            return view('help.admin-chat', compact('user', 'conversations'));
        }

        // Regular users: chat with the SuperAdmin
        $admin = User::where('role_id', 1)->first();
        $messages = collect();

        if ($admin) {
            $messages = HelpMessage::where(function ($q) use ($user, $admin) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $admin->id);
                })
                ->orWhere(function ($q) use ($user, $admin) {
                    $q->where('sender_id', $admin->id)->where('receiver_id', $user->id);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark messages from admin as read
            HelpMessage::where('sender_id', $admin->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('help.chat', compact('user', 'admin', 'messages'));
    }

    /**
     * Send a message.
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'receiver_id' => 'required|exists:users,id',
        ]);

        HelpMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get messages for a conversation (AJAX polling).
     */
    public function messages(Request $request)
    {
        $user = Auth::user();
        $otherId = $request->query('user_id');

        if (!$otherId) {
            // Default to SuperAdmin for regular users
            $admin = User::where('role_id', 1)->first();
            $otherId = $admin?->id;
        }

        if (!$otherId) {
            return response()->json([]);
        }

        $messages = HelpMessage::where(function ($q) use ($user, $otherId) {
                $q->where('sender_id', $user->id)->where('receiver_id', $otherId);
            })
            ->orWhere(function ($q) use ($user, $otherId) {
                $q->where('sender_id', $otherId)->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'message' => e($m->message),
                'is_mine' => $m->sender_id === $user->id,
                'sender_name' => $m->sender->name ?? 'Unknown',
                'time' => $m->created_at->format('H:i'),
                'date' => $m->created_at->format('M d'),
            ]);

        // Mark as read
        HelpMessage::where('sender_id', $otherId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    /**
     * SuperAdmin: view conversation with a specific user.
     */
    public function conversation($userId)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            abort(403);
        }

        $otherUser = User::with('role')->findOrFail($userId);

        $messages = HelpMessage::where(function ($q) use ($user, $userId) {
                $q->where('sender_id', $user->id)->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($user, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        HelpMessage::where('sender_id', $userId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('help.admin-conversation', compact('user', 'otherUser', 'messages'));
    }

    /**
     * Get unread help message count for the current user.
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => HelpMessage::where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count()
        ]);
    }
}
