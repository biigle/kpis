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
        $yesterday = Carbon::now()->subDay();

        UserTest::create(['login_at' => $yesterday]);
        UserTest::create(['login_at' => $yesterday]);

        $this->artisan('kpis:count-user')->assertExitCode(0);

        $users = DB::table('kpis_users')->where('date', '=', $yesterday->toDateString())->pluck('value');

        $this->assertCount(1, $users);
        $this->assertEquals(2, $users[0]);

    }

    public function testDifferentLogInDates()
    {

        $yesterday = Carbon::now()->subDay();

        UserTest::create(['login_at' => $yesterday]);
        UserTest::create(['login_at' => Carbon::now()->subDays(2)]);

        $this->artisan('kpis:count-user')->assertExitCode(0);

        $users = DB::table('kpis_users')->where('date', '=', $yesterday->toDateString())->pluck('value');

        $this->assertCount(1, $users);
        $this->assertEquals(1, $users[0]);
    }
}
