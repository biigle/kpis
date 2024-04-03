<?php

namespace Biigle\Modules\Kpis;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Requests
{
    public static function save($visits, $actions)
    {
        DB::transaction(function () use ($visits, $actions) {
            $today = Carbon::today()->toDateString();
            DB::table('kpis_actions')->insert(['date' => $today, 'value' => $actions]);
            DB::table('kpis_visits')->insert(['date' => $today, 'value' => $visits]);
        });
    }

    public static function getActions($year, $month)
    {
        $start = Carbon::createFromDate($year, $month, 1)->toDateString();
        $end = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
        $res = DB::table('kpis_actions')->whereBetween('date', [$start, $end])->sum('value');
        return $res;
    }
    public static function getVisits($year, $month)
    {
        $start = Carbon::createFromDate($year, $month, 1)->toDateString();
        $end = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
        $res = DB::table('kpis_visits')->whereBetween('date', [$start, $end])->sum('value');
        return $res;
    }
}
