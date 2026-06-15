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
        $lastMonth = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->toImmutable();

        UserTest::create(['created_at' => $lastMonth]);
        UserTest::create(['created_at' => $lastMonth]);

        $this->artisan('kpis:count-user')->assertExitCode(0);

        $endOfMonth = $lastMonth->endOfMonth();
        $users = DB::table('kpis_users')->where('date', '=', $endOfMonth)->pluck('value');

        $this->assertCount(1, $users);
        $this->assertSame(2, $users[0]);
    }

    public function testExcludesCurrentMonthUsers()
    {
        $lastMonth = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->toImmutable();

        UserTest::create(['created_at' => $lastMonth]);
        UserTest::create(['created_at' => Carbon::now()]);

        $this->artisan('kpis:count-user')->assertExitCode(0);

        $endOfMonth = $lastMonth->endOfMonth();
        $users = DB::table('kpis_users')->where('date', '=', $endOfMonth)->pluck('value');

        $this->assertCount(1, $users);
        $this->assertSame(1, $users[0]);
    }
}
