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
    public function show($idx)
    {
        // If index is not in range, set it on index of last month
        $idx = $idx == 0 || $idx > 5 ? 5 : $idx;

        $date = Carbon::now()->subMonths(5 - $idx);
        $year = $date->year;
        $month = $date->month;

        $actions = Requests::getActions($year, $month);
        $visits = Requests::getVisits($year, $month);
        $uuser = User::getUniqueUser($year, $month);
        $user = User::getUser($year, $month);
        $storage = Storage::getStorageUsage($year, $month);

        $monthOverview = [];
        for($i = 5;$i >= 1;$i--) {
            $monthName = Carbon::now()->subMonths($i)->format('F');
            $monthOverview[] = substr($monthName, 0, 3);
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
