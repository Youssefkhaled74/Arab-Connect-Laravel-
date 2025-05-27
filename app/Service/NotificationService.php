<?php
namespace App\Service;

use App\Models\Notification;
use Carbon\Carbon;

class NotificationService
{
    public static function sendNotification($userId, $branchId, $title, $message)
    {
        // Create a new notification record in the database
        Notification::create([
            'user_id' => $userId, // The owner of the branch
            'branch_id' => $branchId, // The branch the notification belongs to
            'title' => $title, // The title of the notification
            'message' => $message, // The actual message content
            'sent_at' => Carbon::now(), // Record the time the notification was sent
        ]);
    }
}