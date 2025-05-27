<?php

namespace App\Observers;

use App\Models\User;
use Laravel\Nova\Notifications\NovaNotification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $notification = NovaNotification::make()
            ->message('تم تسجيل مستخدم جديد: ' . $user->name)
            ->icon('user')
            ->type('success')
            ->action('عرض المستخدم', "/resources/users/{$user->id}");

        // إرسال الإشعار إلى جميع المسؤولين (Admins)
        \App\Models\Admin::all()->each(function ($admin) use ($notification) {
            $admin->notify($notification);
        });
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $notification = NovaNotification::make()
            ->message('تم تحديث بيانات المستخدم: ' . $user->name)
            ->icon('refresh')
            ->type('info')
            ->action('عرض المستخدم', "/resources/users/{$user->id}");

        // إرسال الإشعار إلى جميع المسؤولين (Admins)
        \App\Models\Admin::all()->each(function ($admin) use ($notification) {
            $admin->notify($notification);
        });
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
