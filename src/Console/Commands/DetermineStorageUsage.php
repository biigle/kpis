<?php

namespace Biigle\Modules\Kpis\Console\Commands;

use Biigle\Image;
use Biigle\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DetermineStorageUsage extends Command
{
    /**
     * GB in byte.
     */
    const GB = 1000000000;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'kpis:determine-storage-usage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Determines file storage size in GB';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $size = $this->getSizeInGB();

        $date = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        DB::table('kpis_storage_usage')->insert(['date' => $date, 'value' => $size]);
    }

    private function getSizeInGB()
    {
        $imageStorageUsage = Image::sum(DB::raw("(attrs->>'size')::bigint"));
        $videoStorageUsage = Video::sum(DB::raw("(attrs->>'size')::bigint"));

        return ($imageStorageUsage + $videoStorageUsage) / self::GB;
    }
}
