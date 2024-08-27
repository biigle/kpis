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

        $this->assertSame(50, $actions[0]);
        $this->assertSame(100, $visits[0]);
    }

    public function testSaveBigInt(){
        $maxBigInt = 9223372036854775807;

        Requests::save($maxBigInt, $maxBigInt);

        $date = Carbon::yesterday()->toDateString();

        $actions = DB::table('kpis_actions')->where('date', '=', $date)->pluck('value');
        $visits = DB::table('kpis_visits')->where('date', '=', $date)->pluck('value');

        $this->assertCount(1, $actions);
        $this->assertCount(1, $visits);

        $this->assertSame($maxBigInt, $actions[0]);
        $this->assertSame($maxBigInt, $visits[0]);
    }

    public function testGetActions()
    {
        $date = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->lastOfMonth();

        DB::table('kpis_actions')->insert(['date' => $date, 'value' => 10]);

        $count = Requests::getActions($date->year, $date->month);

        $this->assertSame('10', $count);
    }

    public function testGetActionsOverflow()
    {
        Carbon::setTestNow(Carbon::parse('2024-07-31T05:45:23Z'));
        $this->testGetActions();
    }

    public function testGetVisits()
    {
        $date = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->lastOfMonth();

        DB::table('kpis_visits')->insert(['date' => $date, 'value' => 10]);

        $count = Requests::getVisits($date->year, $date->month);

        $this->assertSame('10', $count);
    }

    public function testGetVisitsOverflow()
    {
        Carbon::setTestNow(Carbon::parse('2024-07-31T05:45:23Z'));
        $this->testGetVisits();
    }
}
