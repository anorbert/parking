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
     * Get the list of users the current user is allowed to chat with.
     */
    private function getAllowedContacts(User $user)
    {
        if ($user->isSuperAdmin()) {
            // SuperAdmin can chat with everyone
            return User::where('id', '!=', $user->id)->with('role', 'company')->get();
        }

        // All non-SA users can chat with the SuperAdmin
        $query = User::where('id', '!=', $user->id)
            ->where(function ($q) use ($user) {
                // SuperAdmin (support)
                $q->where('role_id', 1);
                // Same company (team members)
                if ($user->company_id) {
                    $q->orWhere('company_id', $user->company_id);
                }
            })
            ->with('role', 'company');

        return $query->get();
    }

    /**
     * Check if the current user can chat with another user.
     */
    private function canChatWith(User $user, User $other): bool
    {
        if ($user->id === $other->id) return false;
        if ($user->isSuperAdmin()) return true;
        if ($other->isSuperAdmin()) return true;
        if ($user->company_id && $user->company_id === $other->company_id) return true;
        return false;
    }

    /**
     * Show conversation list for all roles.
     */
    public function chat()
    {
        $user = Auth::user();

        // Get all users this user has existing conversations with
        $conversationUserIds = HelpMessage::where('sender_id', $user->id)
            ->pluck('receiver_id')
            ->merge(
                HelpMessage::where('receiver_id', $user->id)->pluck('sender_id')
            )
            ->unique()
            ->values();

        $conversations = User::whereIn('id', $conversationUserIds)
            ->with('role', 'company')
            ->get()
            ->filter(fn ($u) => $this->canChatWith($user, $u))
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

        // Get available contacts (people not yet chatted with)
        $allContacts = $this->getAllowedContacts($user);
        $availableContacts = $allContacts->whereNotIn('id', $conversationUserIds)->values();

        // Choose layout based on role
        $layout = in_array($user->role_id, [1, 2]) ? 'layouts.admin' : 'layouts.user';

        return view('help.chat', compact('user', 'conversations', 'availableContacts', 'layout'));
    }

    /**
     * Send a message (with authorization check).
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        if (!$this->canChatWith($user, $receiver)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        HelpMessage::create([
            'sender_id' => $user->id,
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
            return response()->json([]);
        }

        $otherUser = User::find($otherId);
        if (!$otherUser || !$this->canChatWith($user, $otherUser)) {
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
                'is_read' => (bool) $m->is_read,
                'sender_name' => $m->sender->name ?? 'Unknown',
                'time' => $m->created_at->format('H:i'),
                'date' => $m->created_at->format('M d'),
            ]);

        // Mark incoming as read
        HelpMessage::where('sender_id', $otherId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    /**
     * View conversation with a specific user (all roles).
     */
    public function conversation($userId)
    {
        $user = Auth::user();
        $otherUser = User::with('role', 'company')->findOrFail($userId);

        if (!$this->canChatWith($user, $otherUser)) {
            abort(403);
        }

        $messages = HelpMessage::where(function ($q) use ($user, $userId) {
                $q->where('sender_id', $user->id)->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($user, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark incoming as read
        HelpMessage::where('sender_id', $userId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Choose layout based on role
        $layout = in_array($user->role_id, [1, 2]) ? 'layouts.admin' : 'layouts.user';

        return view('help.conversation', compact('user', 'otherUser', 'messages', 'layout'));
    }

    /**
     * AJAX: Get contacts available to start new chats.
     */
    public function contacts()
    {
        $user = Auth::user();
        $contacts = $this->getAllowedContacts($user);

        return response()->json(
            $contacts->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'initials' => strtoupper(substr($c->name, 0, 2)),
                'role' => $c->role->name ?? 'User',
                'role_id' => $c->role_id,
                'company' => $c->company->name ?? null,
            ])->values()
        );
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
