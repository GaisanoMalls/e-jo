<?php

namespace App\Http\Controllers\Staff\Approver;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        // Mark the notifications of the currently logged-in user as read.
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications have been marked as read.');
    }

    public function readNotification($notificationId)
    {
        // Mark the selected notification of the currently logged in user as read.
        auth()->user()->notifications()->find($notificationId)->markAsRead();
    }

    public function clearNotifications()
    {
        // Delete all notifications of the currently logged in user.
        auth()->user()->notifications()->delete();
        return back()->with('success', 'Notifications have been cleared.');
    }
}