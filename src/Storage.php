<?php

namespace Biigle\Modules\Kpis;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Storage
{
    public static function getStorageUsage($year, $month)
    {
        $date = Carbon::createFromDate($year, $month, 1)->toDateString();
        return DB::table('kpis_storage_usage')->where('date', '=', $date)->pluck('value')[0];
    }
}
