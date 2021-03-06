<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaperUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'test_time'=>'required',
            'duration'=>'required',
            'status'=>'required',
            'started_at'=>'required',
            'ended_at'=>'required',
        ];
    }
}
