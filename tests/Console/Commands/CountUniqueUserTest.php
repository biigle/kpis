<?php

namespace Biigle\Tests\Modules\Kpis\Console\Commands;

use TestCase;
use Carbon\Carbon;
use Biigle\Tests\UserTest;
use Illuminate\Support\Facades\DB;

class CountUniqueUserTest extends TestCase
{
    public function testHandle()
    {
        UserTest::create(['login_at' => Carbon::now()->subMonth()->firstOfMonth()]);
        UserTest::create(['login_at' => Carbon::now()->subMonth()]);
        UserTest::create(['login_at' => Carbon::now()->subMonth()->endOfMonth()]);

        $this->artisan('kpis:count-unique-user')->assertExitCode(0);

        $uniqueUsers = DB::table('kpis_unique_users')
                        ->where('date', '=', Carbon::now()->subMonth()->endOfMonth())->pluck('value');

        $this->assertCount(1, $uniqueUsers);
        $this->assertEquals(3, $uniqueUsers[0]);


    }

    public function testLoginWasNotLastMonth()
    {
        UserTest::create(['login_at' => Carbon::now()->subMonth()->firstOfMonth()]);
        UserTest::create(['login_at' => Carbon::now()->subMonths(2)->endOfMonth()]);

        $this->artisan('kpis:count-unique-user')->assertExitCode(0);

        $uniqueUsers = DB::table('kpis_unique_users')
                        ->where('date', '=', Carbon::now()->subMonth()->endOfMonth())->pluck('value');

        $this->assertCount(1, $uniqueUsers);
        $this->assertEquals(1, $uniqueUsers[0]);
    }
}
