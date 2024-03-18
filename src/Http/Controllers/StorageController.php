<?php

namespace Biigle\Modules\Kpis\Http\Controllers;

use Biigle\Modules\Kpis\Storage;
use Biigle\Http\Controllers\Controller;

class StorageController extends Controller
{
    public function getStorageUsage(int $year, int $month){
        return Storage::getStorageUsage($year, $month);
    }
}
