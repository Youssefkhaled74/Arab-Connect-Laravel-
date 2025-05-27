<?php
namespace App\Nova\Filters;

use Laravel\Nova\Filters\DateFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DateRangeFilter extends DateFilter
{
    /**
     * اسم الفلتر
     */
    public function name()
    {
        return __('Filter by Date Range');
    }

    /**
     * تطبيق الفلتر على الاستعلام
     */
    public function apply(Request $request, $query, $value)
    {
        if (!isset($value['from']) || !isset($value['to'])) {
            return $query;
        }

        $from = Carbon::parse($value['from'])->startOfDay();
        $to = Carbon::parse($value['to'])->endOfDay();

        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * إظهار حقول التاريخ (من وإلى)
     */
    public function options(Request $request)
    {
        return [
            'from' => __('From Date'),
            'to' => __('To Date'),
        ];
    }
}