<?php

namespace Biigle\Modules\Kpis\Http\Controllers\Api;

use Biigle\Modules\Kpis\Http\Requests\StoreRequest;
use Biigle\Modules\Kpis\Requests;
use Biigle\Http\Controllers\Controller;

class RequestController extends Controller
{
    /**
     * Saves action/visit count from cron job
     * 
     * @apiDefine kpi-tpken KPI Token
     * The request must provide the token configured in the KPI module.
     *
     * @api {post} /kpis Save actions/visits KPIs
     * @apiPermission kpi-token
     * @apiDescription The submitted values are stored for the previous day.
     * @apiParam {Number} visits The number of GET requests to `/` (without bots).
     * @apiParam {Number} actions The number of PUT/POST/DELETE requests (without bots and heartbeat).
     *
     * @apiParamExample {JSON} Request example (JSON):
     * {
     *     "visits": 10,
     *     "actions": 20
     * }
     *
     *
     * @param StoreRequest $request
     */
    public function store(StoreRequest $request)
    {
        $visits = $request->input('visits');
        $actions = $request->input('actions');
        Requests::save($visits, $actions);
    }
}
