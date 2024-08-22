<?php

namespace Biigle\Tests\Modules\Kpis;

use TestCase;
use Carbon\Carbon;
use Biigle\Modules\Kpis\Storage;
use Illuminate\Support\Facades\DB;
use Brick\Math\BigInteger;

class StorageTest extends TestCase
{
    public function testGetStorage()
    {

        $date = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->lastOfMonth();

        $noFiles = Storage::getStorageUsage($date->year, $date->month);

        DB::table('kpis_storage_usage')->insert(['date' => $date, 'value' => 100]);

        $size = Storage::getStorageUsage($date->year, $date->month);

        $this->assertEquals(BigInteger::of(0), $noFiles);
        $this->assertEquals(BigInteger::of(100), $size);
    }
}
