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
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
        $res =  count(DB::table('kpis_actions')->where('date', '=', $date)->pluck('value')) != 0 ?
        DB::table('kpis_actions')->where('date', '=', $date)->pluck('value')[0] : 0;
        return $res;
    }
    public static function getVisits($year, $month)
    {
        $date = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
        $res =  count(DB::table('kpis_visits')->where('date', '=', $date)->pluck('value')) != 0 ?
        DB::table('kpis_visits')->where('date', '=', $date)->pluck('value')[0] : 0;
        return $res;
    }
}
