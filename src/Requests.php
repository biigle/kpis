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
}
