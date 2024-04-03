<?php

namespace Biigle\Modules\Kpis;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Storage
{
    public static function getStorageUsage($year, $month)
    {
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
        $res = DB::table('kpis_storage_usage')->where('date', '=', $date)->sum('value');
        return $res;
    }
}
