<?php

namespace Biigle\Modules\Kpis\Console\Commands;

use Biigle\Modules\Kpis\Citations;
use Biigle\Modules\Kpis\Requests;
use Biigle\Modules\Kpis\Storage;
use Biigle\Modules\Kpis\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SubmitToScorpion extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'kpis:submit-to-scorpion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submits KPIs to Scorpion';

    /**
     * The Scorpion API endpoint to submit measurements to.
     *
     * @var string
     */
    protected $scorpionApiUrl = 'https://scorpion.bi.denbi.de/nfdi/api/v1/measurements';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $token = config('kpis.scorpion.token');
        if (empty($token)) {
            throw new RuntimeException('No Scorpion API token is configured.');
        }

        $date = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth();
        $year = $date->year;
        $month = $date->month;
        $dateString = $date->format('Y-m');

        $values = [
            'Actions' => Requests::getActions($year, $month),
            'Citations' => Citations::getCitations($year, $month),
            'Storage Usage' => Storage::getStorageUsage($year, $month),
            'Unique Users' => User::getUniqueUser($year, $month),
            'Users' => User::getUser($year, $month),
            'Visits' => Requests::getVisits($year, $month),
        ];

        $measurements = collect($values)
            ->map(fn ($value, $kpi) => ['kpi' => $kpi, 'date' => $dateString, 'value' => $value])
            ->values()
            ->all();

        Http::throw()
            ->withHeaders(['X-API-Key' => $token])
            ->withQueryParameters(['service' => config('kpis.scorpion.service')])
            ->post($this->scorpionApiUrl, $measurements);
    }
}
