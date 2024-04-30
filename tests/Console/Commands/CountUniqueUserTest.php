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
        $now = Carbon::now()->toImmutable()->settings(['monthOverflow' => false]);
        UserTest::create(['login_at' => $now->subMonth()->firstOfMonth()]);
        UserTest::create(['login_at' => $now->subMonth()]);
        UserTest::create(['login_at' => $now->subMonth()->endOfMonth()]);

        $this->artisan('kpis:count-unique-user')->assertExitCode(0);

        $uniqueUsers = DB::table('kpis_unique_users')
                        ->where('date', '=', $now->subMonth()->endOfMonth())->pluck('value');

        $this->assertCount(1, $uniqueUsers);
        $this->assertEquals(3, $uniqueUsers[0]);


    }

    public function testLoginWasNotLastMonth()
    {
        $now = Carbon::now()->toImmutable()->settings(['monthOverflow' => false]);
        $u1 = UserTest::create(['login_at' => $now->subMonth()->firstOfMonth()]);
        $u2 = UserTest::create(['login_at' => $now->subMonths(2)->endOfMonth()]);

        $this->artisan('kpis:count-unique-user')->assertExitCode(0);

        $uniqueUsers = DB::table('kpis_unique_users')
                        ->where('date', '=', $now->subMonth()->endOfMonth())->pluck('value');

        $this->assertCount(1, $uniqueUsers);
        $this->assertEquals(1, $uniqueUsers[0]);
    }
}
