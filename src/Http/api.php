<?php

use Biigle\Modules\Kpis\Http\Middleware\EnsureTokenIsValid;

$router->post('api/v1/kpis', [
    'uses' => 'RequestController@store'
])->middleware(EnsureTokenIsValid::class);