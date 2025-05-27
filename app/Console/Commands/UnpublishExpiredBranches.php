<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Branch;
use Carbon\Carbon;

class UnpublishExpiredBranches extends Command
{
    protected $signature = 'branches:unpublish';
    protected $description = 'Unpublish branches after 6 months';

    public function handle()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $branches = Branch::where('is_published', 1)
            ->where('expire_at', '<=', $sixMonthsAgo)
            ->update(['is_published' => 0]);

        $this->info("Expired branches unpublished successfully.");
    }
}
