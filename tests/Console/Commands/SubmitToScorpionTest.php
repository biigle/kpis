<?php

namespace Biigle\Tests\Modules\Kpis\Console\Commands;

use TestCase;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SubmitToScorpionTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function testHandle()
    {
        config(['kpis.scorpion' => [
            'token' => 'test-token',
            'service' => 'TESTSERVICE',
        ]]);

        // The command is scheduled on the second day of the month.
        Carbon::setTestNow(Carbon::parse('2026-03-02 00:00:00'));

        DB::table('kpis_actions')->insert(['date' => '2026-02-15', 'value' => 10]);
        DB::table('kpis_visits')->insert(['date' => '2026-02-15', 'value' => 20]);
        DB::table('kpis_unique_users')->insert(['date' => '2026-02-28', 'value' => 30]);
        DB::table('kpis_users')->insert(['date' => '2026-02-28', 'value' => 40]);
        DB::table('kpis_storage_usage')->insert(['date' => '2026-02-28', 'value' => 50]);
        DB::table('kpis_citations')->insert(['date' => '2026-02-28', 'value' => 60]);

        Http::fake([
            'scorpion.bi.denbi.de/*' => Http::response([], 200),
        ]);

        $this->artisan('kpis:submit-to-scorpion')->assertExitCode(0);

        Http::assertSent(function ($request) {
            $expected = [
                ['kpi' => 'Actions', 'date' => '2026-02', 'value' => 10],
                ['kpi' => 'Citations', 'date' => '2026-02', 'value' => 60],
                ['kpi' => 'Storage Usage', 'date' => '2026-02', 'value' => 50],
                ['kpi' => 'Unique Users', 'date' => '2026-02', 'value' => 30],
                ['kpi' => 'Users', 'date' => '2026-02', 'value' => 40],
                ['kpi' => 'Visits', 'date' => '2026-02', 'value' => 20],
            ];

            return $request->url() === 'https://scorpion.bi.denbi.de/nfdi/api/v1/measurements?service=TESTSERVICE'
                && $request->method() === 'POST'
                && $request->hasHeader('X-API-Key', 'test-token')
                // Strict comparison to ensure the values are submitted as numbers.
                && $request->data() === $expected;
        });
    }

    public function testHandleFailedSubmission()
    {
        config(['kpis.scorpion' => [
            'token' => 'test-token',
            'service' => 'TESTSERVICE',
        ]]);

        Http::fake([
            'scorpion.bi.denbi.de/*' => Http::response([], 500),
        ]);

        $this->expectException(RequestException::class);
        $this->artisan('kpis:submit-to-scorpion')->run();
    }

    public function testHandleMissingToken()
    {
        config(['kpis.scorpion' => [
            'token' => null,
            'service' => 'TESTSERVICE',
        ]]);

        Http::fake();

        $this->expectException(RuntimeException::class);

        try {
            $this->artisan('kpis:submit-to-scorpion')->run();
        } finally {
            Http::assertNothingSent();
        }
    }
}
