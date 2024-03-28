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
        $visits = $visits = $request->input('visits');
        $actions = $visits = $request->input('actions');
        Requests::save($actions, $visits);
    }
}
