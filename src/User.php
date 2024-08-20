<?php

namespace Biigle\Modules\Kpis;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class User
{
    public static function getUser($year, $month)
    {
        $first = Carbon::create($year, $month)->startOfMonth();
        $last = $first->copy()->endOfMonth();

        return DB::table('kpis_users')
            ->whereBetween('date', [$first, $last])
            ->sum('value');
    }

    public static function getUniqueUser($year, $month)
    {
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        $res =  DB::table('kpis_unique_users')->where('date', '=', $date)->sum('value');

        return $res;
    }
}
