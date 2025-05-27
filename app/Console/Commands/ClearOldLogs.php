<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class ClearOldLogs extends Command
{
    /**
     * اسم الكوماند عند تشغيله.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * وصف الكوماند.
     *
     * @var string
     */
    protected $description = 'حذف السجلات القديمة التي مر عليها أكثر من شهر';

    /**
     * تنفيذ الكوماند.
     */
    public function handle()
    {
        $date = Carbon::now()->subMonth();

        Activity::where('created_at', '<', $date)->delete();

        $this->info('Deleted old logs successfully.');
    }
}
