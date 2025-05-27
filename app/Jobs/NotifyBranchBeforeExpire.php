<?php

namespace App\Jobs;

use App\Models\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\BranchExpireSoonNotification;

class NotifyBranchBeforeExpire implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $branches = Branch::whereDate('expire_at', now()->addMonth()->toDateString())->get();
        foreach ($branches as $branch) {
            $branch->owner->notify(new BranchExpireSoonNotification($branch));
        }
    }

}
