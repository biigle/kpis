<?php

namespace Biigle\Modules\Kpis\Http\Controllers;

use Illuminate\Http\Request;
use Biigle\Modules\Kpis\Requests;
use Biigle\Http\Controllers\Controller;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        $res = json_decode($request->get('value'), true, JSON_BIGINT_AS_STRING);
        Requests::save($res['actions'], $res['visits']);
        return;
    }
}
