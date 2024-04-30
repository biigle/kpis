<?php

namespace Biigle\Tests\Modules\Kpis\Console\Commands;

use TestCase;
use Carbon\Carbon;
use Biigle\Tests\ImageTest;
use Biigle\Tests\VideoTest;
use Illuminate\Support\Facades\DB;

class DetermineStorageUsageTest extends TestCase
{
    public function testHandle()
    {
        $oneGB = 1000000000;

        ImageTest::create([
            'attrs' => [
                'size' => $oneGB - 1, // test rounding
            ]
        ]);
        VideoTest::create([
            'attrs' => [
                'size' => $oneGB,
            ]
        ]);

        $this->artisan('kpis:determine-storage-usage')->assertExitCode(0);

        $date = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->endOfMonth();
        $users = DB::table('kpis_storage_usage')
            ->where('date', '=', $date)
            ->pluck('value');

        $this->assertCount(1, $users);
        $this->assertEquals(2, $users[0]);

    }

    public function testEmptyAttributeArrays()
    {

        ImageTest::create(['attrs' => []]);
        ImageTest::create([]);
        VideoTest::create(['attrs' => []]);
        VideoTest::create([]);

        $this->artisan('kpis:determine-storage-usage')->assertExitCode(0);

        $date = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->endOfMonth();
        $users = DB::table('kpis_storage_usage')
            ->where('date', '=', $date)
            ->pluck('value');

        $this->assertCount(1, $users);
        $this->assertEquals(0, $users[0]);
    }
}
