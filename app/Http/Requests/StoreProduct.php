<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreProduct
 * @package App\Http\Requests
 */
class StoreProduct extends FormRequest
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
            'title' => 'required|string|regex:/^[\w\s\-\.]+$/|min:3|max:64|unique:products',
            'description' => 'required|string|regex:/^[\w\s\-\.]+$/|min:3|max:191',
            'price' => 'required|numeric',
            'category_id' => 'required|numeric|exists:categories,id'
        ];
    }
}
