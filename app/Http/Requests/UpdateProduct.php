<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateProduct
 * @package App\Http\Requests
 */
class UpdateProduct extends FormRequest
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
        $id = $this->route('id');
        return [
            'title' => [
                'required',
                'string',
                'regex:/^[\w\s\-\.]+$/',
                'min:3',
                'max:64',
                Rule::unique('products')->where('id', '<>', $id),
            ],
            'description' => 'required|string|regex:/^[\w\s\-\.]+$/|min:3|max:191',
            'price' => 'required|numeric',
            'category_id' => 'required|numeric|exists:categories,id'
        ];
    }
}
