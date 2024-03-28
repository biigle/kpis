<?php

namespace Biigle\Tests\Modules\Kpis\Http\Controllers;

use ApiTestCase;
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
    }
}
