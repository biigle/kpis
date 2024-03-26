<?php

$router->post('api/v1/kpis', [
    'uses' => 'RequestController@store'
])->middleware('auth.kpis');