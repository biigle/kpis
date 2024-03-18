<?php

// $router->get('quotes', [
//    'middleware' => 'auth',
//    'as'   => 'quotes',
//    'uses' => 'QuotesController@index',
// ]);

$router->group([
    'prefix' => 'api/v1/kpis',
    'middleware' => ['auth'],
], function ($router) {
    $router->get('user/{year}/{month}', [
        'uses' => 'UserController@getUser'
    ]);
    $router->get('uuser/{year}/{month}', [
        'uses' => 'UserController@getUniqueUser'
    ]);
    $router->get('storage/{year}/{month}', [
        'uses' => 'StorageController@getStorageUsage'
    ]);
});