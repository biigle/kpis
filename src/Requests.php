<?php

namespace Biigle\Modules\Kpis;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Requests
{
    public static function save($visits, $actions)
    {
        DB::transaction(function () use ($visits, $actions) {
            $yesterday = Carbon::yesterday();
            DB::table('kpis_actions')->insert(['date' => $yesterday, 'value' => $actions]);
            DB::table('kpis_visits')->insert(['date' => $yesterday, 'value' => $visits]);
        });
    }

    public static function getActions($year, $month)
    {
        $start = Carbon::create($year, $month)->startOfMonth();
        $end = $start->copy()->addMonth();
        $res = DB::table('kpis_actions')->whereBetween('date', [$start, $end])->sum('value');

        return $res;
    }
    public static function getVisits($year, $month)
    {
        $start = Carbon::create($year, $month)->startOfMonth();
        $end = $start->copy()->addMonth();
        $res = DB::table('kpis_visits')->whereBetween('date', [$start, $end])->sum('value');

        return $res;
    }
}
