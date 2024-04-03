<?php

namespace Biigle\Modules\Kpis;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class User
{
    public static function getUser($year, $month)
    {
        $first = Carbon::createFromDate($year, $month, 1);
        $last = $first->copy()->endOfMonth();
        return DB::table('kpis_users')->whereBetween('date', [$first->toDateString(), $last->toDateString()])->sum('value');
    }
    public static function getUniqueUser($year, $month)
    {
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
        $res =  DB::table('kpis_unique_users')->where('date', '=', $date)->sum('value');
        return $res;
    }
}
