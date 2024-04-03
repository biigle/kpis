<?php

namespace Biigle\Tests\Modules\Kpis;

use TestCase;
use Carbon\Carbon;
use Biigle\Modules\Kpis\User;
use Illuminate\Support\Facades\DB;

class UserTest extends TestCase
{

    public function testGetUser(){

        $first = Carbon::now()->subMonth()->firstOfMonth();
        $last = Carbon::now()->subMonth()->endOfMonth();

        $noUserCounted = User::getUser($first->year, $first->month);

        DB::table('kpis_users')->insert(['date' => $first->toDateString(), 'value' => 10]);
        DB::table('kpis_users')->insert(['date' => $last->toDateString(), 'value' => 10]);

        $count = User::getUser($first->year, $first->month);

        $this->assertEquals(0, $noUserCounted);
        $this->assertEquals(20, $count);
    }

    public function testGetUniqueUser(){
        $date = Carbon::now()->subMonth()->endOfMonth();

        $noUserCounted = User::getUniqueUser($date->year, $date->month);

        DB::table('kpis_unique_users')->insert(['date' => $date->toDateString(), 'value' => 10]);

        $count = User::getUniqueUser($date->year, $date->month);

        $this->assertEquals(0, $noUserCounted);
        $this->assertEquals(10, $count);
    }
}

