<?php

$router->get('admin/kpis/{idx}', [
   'middleware' => 'auth',
   'uses' => 'KpiController@show',
]);