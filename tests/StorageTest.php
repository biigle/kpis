<?php

namespace Biigle\Tests\Modules\Kpis;

use TestCase;
use Carbon\Carbon;
use Biigle\Modules\Kpis\Storage;
use Illuminate\Support\Facades\DB;

class StorageTest extends TestCase
{
    public function testGetStorage()
    {

        $date = Carbon::now()->subMonth()->lastOfMonth();

        $noFiles = Storage::getStorageUsage($date->year, $date->month);

        DB::table('kpis_storage_usage')->insert(['date' => $date->toDateString(), 'value' => 100]);

        $size = Storage::getStorageUsage($date->year, $date->month);

        $this->assertEquals(0, $noFiles);
        $this->assertEquals(100, $size);
    }
}
