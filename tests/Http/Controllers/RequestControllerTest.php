<?php

namespace Biigle\Tests\Modules\Kpis\Http\Controllers;

use ApiTestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class RequestControllerTest extends ApiTestCase
{
    protected $route;

    protected $testToken;

    public function setUp(): void
    {
        parent::setUp();
        $this->route = "api/v1/kpis";
        $this->testToken = "test-token";
        Config::set("kpis.token", $this->testToken);
    }

    public function testStore()
    {
        $this->withHeader('Authorization', "Bearer {$this->testToken}")
        ->postJson(
            $this->route,
            ['visits' => '94556', 'actions' => '21602']
        )->assertStatus(200);

        $date = Carbon::now()->toDateString();

        $actions = DB::table('kpis_actions')->where('date', '=', $date)->pluck('value')[0];
        $visits = DB::table('kpis_visits')->where('date', '=', $date)->pluck('value')[0];

        $this->assertEquals(94556, $visits);
        $this->assertEquals(21602, $actions);
    }

    public function testStoreMissingToken()
    {
        // missing token
        $this->json(
            'post',
            $this->route,
            ['visits' => '94556', 'actions' => '21602']
        )->assertStatus(401);
    }

    public function testStoreWrongToken()
    {
        // wrong token
        $this->withHeader('Authorization', 'Bearer 123')
        ->json(
            'post',
            $this->route,
            ['visits' => '94556', 'actions' => '21602']
        )->assertStatus(403);
    }
    public function testStoreInvalidData()
    {
        // invalid data
        $this->withHeader('Authorization', "Bearer {$this->testToken}")
        ->postJson(
            $this->route,
            ['visits' => 'abc', 'actions' => 'def']
        )->assertStatus(422);

        $this->withHeader('Authorization', "Bearer {$this->testToken}")
        ->postJson(
            $this->route,
            []
        )->assertStatus(422);

        $this->withHeader('Authorization', "Bearer {$this->testToken}")
        ->postJson(
            $this->route,
            ['abc']
        )->assertStatus(422);
    }
}
