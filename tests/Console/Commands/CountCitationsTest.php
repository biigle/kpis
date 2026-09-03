<?php

namespace Biigle\Tests\Modules\Kpis\Console\Commands;

use TestCase;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;

class CountCitationsTest extends TestCase
{
    public function testHandle()
    {
        config(['kpis.citations' => [
            'FAKEQUERY' => [
                'fakepaperid000000000000000000000000001',
                'fakepaperid000000000000000000000000002',
            ],
        ]]);

        Http::fake([
            'api.semanticscholar.org/*' => Http::response([
                'total' => 3,
                'token' => null,
                'data' => [
                    ['paperId' => 'fakepaperid000000000000000000000000001', 'citationCount' => 10],
                    ['paperId' => 'fakepaperid000000000000000000000000002', 'citationCount' => 7],
                    ['paperId' => 'fakepaperid000000000000000000000000003', 'citationCount' => 100],
                ],
            ], 200),
        ]);

        $this->artisan('kpis:count-citations')->assertExitCode(0);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.semanticscholar.org/graph/v1/paper/search/bulk?query=FAKEQUERY&fields=citationCount';
        });

        $date = Carbon::now()
            ->settings(['monthOverflow' => false])
            ->subMonth()
            ->endOfMonth()
            ->toDateString();

        $citations = DB::table('kpis_citations')->where('date', '=', $date)->pluck('value');

        $this->assertCount(1, $citations);
        // fakepaperid...003 is not in the configured work IDs, so its count is excluded.
        $this->assertSame(17, $citations[0]);
    }

    public function testHandleFailedRequest()
    {
        config(['kpis.citations' => [
            'FAKEQUERY' => [
                'fakepaperid000000000000000000000000001',
            ],
        ]]);

        Http::fake([
            'api.semanticscholar.org/*' => Http::response([], 500),
        ]);

        // Skip the retry delays of the HTTP client.
        Sleep::fake();

        try {
            $this->artisan('kpis:count-citations')->run();
            $this->fail('Expected a RequestException to be thrown.');
        } catch (RequestException $e) {
            // expected
        } finally {
            Sleep::fake(false);
        }

        $this->assertSame(0, DB::table('kpis_citations')->count());
    }

    public function testHandleMissingPaperId()
    {
        config(['kpis.citations' => [
            'FAKEQUERY' => [
                'fakepaperid000000000000000000000000001',
                'fakepaperid000000000000000000000000002',
            ],
        ]]);

        Http::fake([
            'api.semanticscholar.org/*' => Http::response([
                'total' => 1,
                'token' => null,
                'data' => [
                    ['paperId' => 'fakepaperid000000000000000000000000001', 'citationCount' => 10],
                ],
            ], 200),
        ]);

        try {
            $this->artisan('kpis:count-citations')->run();
            $this->fail('Expected a RuntimeException to be thrown.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('fakepaperid000000000000000000000000002', $e->getMessage());
        }

        $this->assertSame(0, DB::table('kpis_citations')->count());
    }
}
