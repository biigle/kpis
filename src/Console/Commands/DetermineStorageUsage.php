<?php

namespace Biigle\Modules\Kpis\Console\Commands;

use Biigle\User;
use Biigle\Image;
use Biigle\Video;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DetermineStorageUsage extends Command
{
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
    protected $description = 'Determines file attribute size';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $size = $this->determineSize();

        DB::transaction(function () use ($size) {
            DB::table('kpis_storage_usage')->insert(['date' => Carbon::today()->toDateString(), 'value' => $size]);
        });
    }

    private function determineSize()
    {
        $imageStorageUsage = Image::all()->reduce(function($res, $image){
            $res += $image->attrs ? count($image->attrs) : 0;
            return $res;
        }, 0);

        $videoStorageUsage = Video::all()->reduce(function($res, $video){
            $res += $video->attrs ? count($video->attrs) : 0;
            return $res;
        }, 0);

        return $imageStorageUsage + $videoStorageUsage;
    }
}
