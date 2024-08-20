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

        $this->assertSame(0, $noUserCounted);
        $this->assertSame('20', $count);
    }

    public function testGetUniqueUser(){
        $date = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->endOfMonth();

        $noUserCounted = User::getUniqueUser($date->year, $date->month);

        DB::table('kpis_unique_users')->insert(['date' => $date, 'value' => 10]);

        $count = User::getUniqueUser($date->year, $date->month);

        $this->assertSame(0, $noUserCounted);
        $this->assertSame('10', $count);
    }
}

