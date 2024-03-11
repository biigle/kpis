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
        $today = Carbon::today()->toDateString();

        ImageTest::create([
            'attrs' => [
                'size' => 3189539,
                'mimetype' => 'image\/jpeg',
                'width' => 4024,
                'height' => 6048
            ]
        ]);
        VideoTest::create([
            'attrs' => [
                'size' => 3189539,
                'mimetype' => 'video/mp4',
                'width' => 4024,
                'height' => 6048
            ]
        ]);

        $this->artisan('kpis:determine-storage-usage')->assertExitCode(0);

        $users = DB::table('kpis_storage_usage')->where('date', '=', $today)->pluck('value')[0];

        $this->assertEquals(8, $users);

    }

    public function testEmptyAttributeArrays(){

        $today = Carbon::today()->toDateString();

        ImageTest::create(['attrs' => []]);
        ImageTest::create([]);
        VideoTest::create(['attrs' => []]);
        VideoTest::create([]);

        $this->artisan('kpis:determine-storage-usage')->assertExitCode(0);

        $users = DB::table('kpis_storage_usage')->where('date', '=', $today)->pluck('value')[0];

        $this->assertEquals(0, $users);
    }
}
