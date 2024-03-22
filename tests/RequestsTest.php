<?php

namespace Biigle\Modules\Tests;

use TestCase;
use Carbon\Carbon;
use Biigle\Modules\Kpis\Requests;
use Illuminate\Support\Facades\DB;

class RequestsTest extends TestCase
{
    public function testSave()
    {
        Requests::save(100, 50);

        $date = Carbon::now()->toDateString();

        $actions = DB::table('kpis_actions')->where('date', '=', $date)->pluck('value')[0];
        $visits = DB::table('kpis_visits')->where('date', '=', $date)->pluck('value')[0];

        $this->assertEquals(50, $actions);
        $this->assertEquals(100, $visits);
    }

    public function testGetActions(){
        $date = Carbon::now()->subMonth()->lastOfMonth();

        DB::table('kpis_actions')->insert(['date' => $date->toDateString(), 'value' => 10]);

        $count = Requests::getActions($date->year, $date->month);

        $this->assertEquals(10, $count);
    }
    public function testGetVisits(){
        $date = Carbon::now()->subMonth()->lastOfMonth();

        DB::table('kpis_visits')->insert(['date' => $date->toDateString(), 'value' => 10]);

        $count = Requests::getVisits($date->year, $date->month);

        $this->assertEquals(10, $count);
    }
}
