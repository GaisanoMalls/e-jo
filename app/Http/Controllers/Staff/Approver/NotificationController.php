<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    // Notifications
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications have been marked as read.');
    }

    public function readNotification($notificationId)
    {
        auth()->user()->notifications()->find($notificationId)->markAsRead();
    }

    public function clearNotifications()
    {
        auth()->user()->notifications()->delete();
        return back()->with('success', 'Notifications have been cleared.');
    }
}