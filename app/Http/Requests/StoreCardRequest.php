<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
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
            'title' => 'required|min:4|max:200',
            'description' => 'required|min:4|max:2000',
            'checklist' => 'required|array|max:10',
            'checklist.*.completed' => 'required|boolean',
            'checklist.*.text' => 'required|min:5|max:300',
        ];
    }
}
