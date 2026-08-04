<?php

namespace Biigle\Modules\Kpis;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Citations
{
    public static function getCitations($year, $month)
    {
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        $res = DB::table('kpis_citations')->where('date', '=', $date)->sum('value');

        return $res;
    }
}
