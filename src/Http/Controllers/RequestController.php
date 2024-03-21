<?php

namespace Biigle\Modules\Kpis\Http\Controllers;

use Biigle\Modules\Kpis\Http\Requests\StoreRequest;
use Biigle\Modules\Kpis\Requests;
use Biigle\Http\Controllers\Controller;

class RequestController extends Controller
{
    /**
     * Stores action and visit counts
     *
     * @param StoreRequest $request containing the json object
     *
    */
    public function store(StoreRequest $request)
    {
        $res = json_decode($request->get('value'), true);
        Requests::save($res['actions'], $res['visits']);
        return;
    }
}
