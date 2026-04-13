<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->take(30)->get();
        $unreadCount = auth()->user()->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'icon' => $n->data['icon'] ?? 'fa-bell',
                    'color' => $n->data['color'] ?? 'blue',
                    'title' => $n->data['title'] ?? '',
                    'body' => $n->data['body'] ?? '',
                    'time' => $n->created_at->diffForHumans(),
                    'read' => $n->read_at !== null,
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}
