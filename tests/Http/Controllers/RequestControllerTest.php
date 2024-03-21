<?php

namespace Biigle\Modules\Kpis\Tests\Http\Controllers;

use ApiTestCase;

class RequestControllerTest extends ApiTestCase
{
    public function testStore()
    {

        $route = "api/v1/kpis";

        // missing token
        $this->json('post', $route, [
            'value' => '{"visits": 94556, "actions": 21602}'
            ])->assertStatus(401);

        // wrong token
        $this->withHeader('Authorization', 'Bearer 123')
        ->json('post', $route, [
            'value' => '{"visits": 94556, "actions": 21602}'
            ])->assertStatus(403);

        // invalid data
        $this->withHeader('Authorization', env('KPI_TOKEN'))
        ->postJson($route, [
            'value' => 'abc'
            ])->assertStatus(422);

        $this->withHeader('Authorization', env('KPI_TOKEN'))
        ->postJson($route, [
            'value' => '{"visits": 94556, "actions": 21602}'
            ])->assertStatus(200);

        // max value string length
        $this->withHeader('Authorization', env('KPI_TOKEN'))
        ->postJson($route, [
            'value' => '{"visits": '.PHP_INT_MAX .', "actions": '.PHP_INT_MAX.'}'
            ])->assertStatus(200);

    }

}
