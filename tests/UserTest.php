<?php

namespace Biigle\Tests\Modules\Kpis;

use TestCase;
use Carbon\Carbon;
use Biigle\Modules\Kpis\User;
use Illuminate\Support\Facades\DB;

class UserTest extends TestCase
{

    public function testGetUser()
    {
        $now = Carbon::now()->toImmutable()->settings(['monthOverflow' => false]);
        $first = $now->subMonth()->firstOfMonth();
        $last = $now->subMonth()->endOfMonth();

        $noUserCounted = User::getUser($first->year, $first->month);

        DB::table('kpis_users')->insert(['date' => $first, 'value' => 10]);
        DB::table('kpis_users')->insert(['date' => $last, 'value' => 10]);

        $count = User::getUser($first->year, $first->month);

        $this->assertEquals(0, $noUserCounted);
        $this->assertEquals(20, $count);
    }

    public function testGetUserOverflow()
    {
        Carbon::setTestNow(Carbon::parse('2024-07-31T05:45:23Z'));
        $this->testGetUser();
    }

    public function testGetUniqueUser(){
        $date = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->endOfMonth();

        $noUserCounted = User::getUniqueUser($date->year, $date->month);

        DB::table('kpis_unique_users')->insert(['date' => $date, 'value' => 10]);

        $count = User::getUniqueUser($date->year, $date->month);

        $this->assertEquals(0, $noUserCounted);
        $this->assertEquals(10, $count);
    }
}

