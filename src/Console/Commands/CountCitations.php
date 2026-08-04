<?php

namespace Biigle\Modules\Kpis\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CountCitations extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'kpis:count-citations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Counts citations as of last month';

    /**
     * The Semantic Scholar API endpoint. The queries and paper IDs are read from the
     * config.
     *
     * @var string
     */
    protected $semanticScholarApiUrl = 'https://api.semanticscholar.org/graph/v1/paper/search/bulk';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $citationCount = 0;

        $date = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->endOfMonth();

        $queries = config('kpis.citations');

        try {
            foreach ($queries as $query => $paperIds) {
                $response = Http::throw()->get($this->semanticScholarApiUrl, [
                    'query' => $query,
                    'fields' => 'citationCount',
                ]);
                $body = $response->json();

                $foundIds = [];
                foreach ($body['data'] ?? [] as $paper) {
                    $paperId = $paper['paperId'] ?? null;
                    if (in_array($paperId, $paperIds)) {
                        $foundIds[] = $paperId;
                        $citationCount += $paper['citationCount'] ?? 0;
                    }
                }

                // A missing paper ID would silently lower the citation count, e.g. if the
                // ID changed or if the paper is not part of the first page of results.
                $missingIds = array_diff($paperIds, $foundIds);
                if (!empty($missingIds)) {
                    $ids = implode(', ', $missingIds);
                    $this->error("Could not find paper IDs for query '{$query}': {$ids}");

                    return self::FAILURE;
                }
            }
        } catch (RequestException|ConnectionException $e) {
            $this->error("Failed to fetch citation counts: {$e->getMessage()}");

            return self::FAILURE;
        }

        DB::table('kpis_citations')->insert(['date' => $date, 'value' => $citationCount]);
    }
}
