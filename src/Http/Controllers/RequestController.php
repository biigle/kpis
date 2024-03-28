<?php

namespace Biigle\Modules\Kpis\Http\Controllers;

use Biigle\Modules\Kpis\Http\Requests\StoreRequest;
use Biigle\Modules\Kpis\Requests;
use Biigle\Http\Controllers\Controller;

class RequestController extends Controller
{
    /**
     * Saves action/visit count from cron job
     *
     * @api {post} /kpis
     * @apiPermission token-verifyed
     * @apiDescription Saves actions/visits of a serverlog file
     *
     * @apiParamExample {JSON} Request example (JSON):
     * {
     *    {
     *       "visits": 10,
     *       "actions": 20
     *    }
     * }
     *
     *
     * @param StoreRequest $request
     */
    public function store(StoreRequest $request)
    {
        $visits = $visits = $request->input('visits');
        $actions = $visits = $request->input('actions');
        Requests::save($actions, $visits);
    }
}
