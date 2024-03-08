<?php

namespace Biigle\Tests\Modules\Kpis\Console\Commands;

use Biigle\Tests\UserTest;
use TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CountUserTest extends TestCase
{
    public function testHandle()
    {
        $today = Carbon::today()->toDateString();

        UserTest::create(['login_at' => $today]);
        UserTest::create(['login_at' => $today]);

        $this->artisan('kpis:count-user')->assertExitCode(0);

        $users = DB::table('kpis_users')->where('date', '=', $today)->pluck('value')[0];

        $this->assertEquals(2, $users);

    }

    public function testDifferentLogInDates(){

        $today = Carbon::today()->toDateString();

        UserTest::create(['login_at' => $today]);
        UserTest::create(['login_at' => Carbon::yesterday()->toDateString()]);

        $this->artisan('kpis:count-user')->assertExitCode(0);

        $users = DB::table('kpis_users')->where('date', '=', $today)->pluck('value')[0];

        $this->assertEquals(1, $users);
    }
}
