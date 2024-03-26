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
        $yesterday = Carbon::yesterday()->toDateString();

        UserTest::create(['login_at' => $yesterday]);
        UserTest::create(['login_at' => $yesterday]);

        $this->artisan('kpis:count-user')->assertExitCode(0);

        $users = DB::table('kpis_users')->where('date', '=', $yesterday)->pluck('value');

        $this->assertCount(1, $users);
        $this->assertEquals(2, $users[0]);

    }

    public function testDifferentLogInDates()
    {

        $yesterday = Carbon::yesterday()->toDateString();

        UserTest::create(['login_at' => $yesterday]);
        UserTest::create(['login_at' => Carbon::now()->subDays(2)->toDateString()]);

        $this->artisan('kpis:count-user')->assertExitCode(0);

        $users = DB::table('kpis_users')->where('date', '=', $yesterday)->pluck('value');

        $this->assertCount(1, $users);
        $this->assertEquals(1, $users[0]);
    }
}
