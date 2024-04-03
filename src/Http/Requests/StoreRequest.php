<?php

namespace Biigle\Modules\Kpis\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'actions' => 'required|integer',
            'visits' => 'required|integer'
        ];
    }
}
