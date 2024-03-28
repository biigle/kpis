<?php

namespace Biigle\Modules\Kpis\Http\Controllers\Views;

use Carbon\Carbon;
use Biigle\Modules\Kpis\User;
use Biigle\Modules\Kpis\Storage;
use Biigle\Modules\Kpis\Requests;
use Biigle\Http\Controllers\Views\Controller;

class KpiController extends Controller
{
    /**
     * Shows the kpis page.
     *
     * @param int $idx month index
     * @return \Illuminate\Http\Response
     */
    public function show($idx = 6)
    {
        if($idx == 0 || $idx > 6) {
            abort(404);
        }

        $date = Carbon::now()->subMonths(6 - $idx);
        $year = $date->year;
        $month = $date->month;

        $actions = Requests::getActions($year, $month);
        $visits = Requests::getVisits($year, $month);
        $uuser = User::getUniqueUser($year, $month);
        $user = User::getUser($year, $month);
        $storage = Storage::getStorageUsage($year, $month);

        $monthOverview = [];
        for($i = 6;$i >= 1;$i--) {
            $monthOverview[] = Carbon::now()->subMonths($i)->format('M');
        }

        return view('kpis::show', [
            'actions' => $actions,
            'visits' => $visits,
            'uuserNbr' => $uuser,
            'userNbr' => $user,
            'storage' => $storage,
            'monthOverview' => $monthOverview,
            'idx' => $idx,
        ]);

    }
}
