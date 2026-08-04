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
        // The end is exclusive. Otherwise the first day of the next month would be
        // counted twice.
        $end = $start->copy()->addMonth();
        $res = DB::table('kpis_actions')
            ->where('date', '>=', $start)
            ->where('date', '<', $end)
            ->sum('value');

        return intval($res);
    }
    public static function getVisits($year, $month)
    {
        $start = Carbon::create($year, $month)->startOfMonth();
        // The end is exclusive. Otherwise the first day of the next month would be
        // counted twice.
        $end = $start->copy()->addMonth();
        $res = DB::table('kpis_visits')
            ->where('date', '>=', $start)
            ->where('date', '<', $end)
            ->sum('value');

        return intval($res);
    }
}
