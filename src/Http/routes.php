<?php

// $router->get('quotes', [
//    'middleware' => 'auth',
//    'as'   => 'quotes',
//    'uses' => 'QuotesController@index',
// ]);



$router->group([
    // 'namespace' => 'Api',
    'prefix' => 'api/v1/kpis',
    'middleware' => ['auth.kpis'],
], function ($router) {
    $router->post('actions', [
        'uses' => 'RequestController@store'
    ]);
});