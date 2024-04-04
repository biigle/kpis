<?php

namespace Biigle\Tests\Modules\Kpis;

use TestCase;
use Carbon\Carbon;
use Biigle\Modules\Kpis\Requests;
use Illuminate\Support\Facades\DB;

class RequestsTest extends TestCase
{
    public function testSave()
    {
        Requests::save(100, 50);

        $date = Carbon::yesterday()->toDateString();

        $actions = DB::table('kpis_actions')->where('date', '=', $date)->pluck('value');
        $visits = DB::table('kpis_visits')->where('date', '=', $date)->pluck('value');

        $this->assertCount(1, $actions);
        $this->assertCount(1, $visits);

        $this->assertEquals(50, $actions[0]);
        $this->assertEquals(100, $visits[0]);
    }

    public function testSaveBigInt(){
        $maxBigInt = "9223372036854775807";

        Requests::save($maxBigInt, $maxBigInt);

        $date = Carbon::yesterday()->toDateString();

        $actions = DB::table('kpis_actions')->where('date', '=', $date)->pluck('value');
        $visits = DB::table('kpis_visits')->where('date', '=', $date)->pluck('value');

        $this->assertCount(1, $actions);
        $this->assertCount(1, $visits);

        $this->assertEquals($maxBigInt, $actions[0]);
        $this->assertEquals($maxBigInt, $visits[0]);
    }

    public function testGetActions()
    {
        $date = Carbon::now()->subMonth()->lastOfMonth();

        DB::table('kpis_actions')->insert(['date' => $date, 'value' => 10]);

        $count = Requests::getActions($date->year, $date->month);

        $this->assertEquals(10, $count);
    }

    public function testGetVisits()
    {
        $date = Carbon::now()->subMonth()->lastOfMonth();

        DB::table('kpis_visits')->insert(['date' => $date, 'value' => 10]);

        $count = Requests::getVisits($date->year, $date->month);

        $this->assertEquals(10, $count);
    }
}
