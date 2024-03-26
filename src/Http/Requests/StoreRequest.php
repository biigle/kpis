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
            'value' => 'required|string',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if ($validator->fails()) {
            return;
        }

        $validator->after(function ($validator) {
            try {
                $res = json_decode($this->request->get('value'), true);

                if(!$res['actions'] || !$res['visits']) {
                    $validator->errors()->add('value', 'Missing data.');
                }

                if(!is_int($res['actions']) || !is_int($res['visits'])) {
                    $validator->errors()->add('value', 'No valid data.');
                }
            } catch(Exception $ex) {
                $validator->errors()->add('value', 'Wrong format.');
            }


        });
    }
}
