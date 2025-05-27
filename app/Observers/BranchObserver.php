<?php

namespace App\Observers;

use App\Models\Branch;
use Laravel\Nova\Notifications\NovaNotification;

class BranchObserver
{
    /**
     * Handle the Branch "created" event.
     */
    public function created(Branch $branch): void
    {
        $notification = NovaNotification::make()
            ->message('تم إضافة فرع جديد: ' . $branch->name)
            ->icon('plus-circle')
            ->type('success')
            ->action('عرض الفرع', "/resources/branches/{$branch->id}");

        // إرسال الإشعار إلى جميع المشرفين
        \App\Models\Admin::all()->each(function ($admin) use ($notification) {
            $admin->notify($notification);
        });
    }

    /**
     * Handle the Branch "updated" event.
     */
    public function updated(Branch $branch): void
    {
        $notification = NovaNotification::make()
            ->message('تم تحديث بيانات الفرع: ' . $branch->name)
            ->icon('refresh')
            ->type('info')
            ->action('عرض الفرع', "/resources/branches/{$branch->id}");

        // إرسال الإشعار إلى جميع المشرفين
        \App\Models\Admin::all()->each(function ($admin) use ($notification) {
            $admin->notify($notification);
        });
    }

    /**
     * Handle the Branch "deleted" event.
     */
    public function deleted(Branch $branch): void
    {
        //
    }

    /**
     * Handle the Branch "restored" event.
     */
    public function restored(Branch $branch): void
    {
        //
    }

    /**
     * Handle the Branch "force deleted" event.
     */
    public function forceDeleted(Branch $branch): void
    {
        //
    }
}
