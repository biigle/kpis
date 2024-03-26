<?php

namespace Biigle\Modules\Kpis\Tests\Http\Controllers;

use ApiTestCase;

class RequestControllerTest extends ApiTestCase
{
    protected $route = "api/v1/kpis";

    public function testStore()
    {
        $this->withHeader('Authorization', env('KPI_TOKEN'))
        ->postJson($this->route, [
            'value' => '{"visits": 94556, "actions": 21602}'
            ])->assertStatus(200);
    }

    public function testStoreMissingToken()
    {
        // missing token
        $this->json('post', $this->route, [
            'value' => '{"visits": 94556, "actions": 21602}'
            ])->assertStatus(401);
    }

    public function testStoreWrongToken()
    {
        // wrong token
        $this->withHeader('Authorization', 'Bearer 123')
        ->json('post', $this->route, [
            'value' => '{"visits": 94556, "actions": 21602}'
            ])->assertStatus(403);
    }
    public function testStoreInvalidData()
    {
        // invalid data
        $this->withHeader('Authorization', env('KPI_TOKEN'))
        ->postJson($this->route, [
            'value' => 'abc'
            ])->assertStatus(422);
    }
}
