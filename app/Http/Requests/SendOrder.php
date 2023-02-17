<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SendOrder
 * @package App\Http\Requests
 */
class SendOrder extends FormRequest
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
            'card_counts' => 'required|array',
            'user_name' => 'required|string|min:2|max:64',
            'user_email' => 'required|string|email|min:6|max:64',
            'user_comment' => 'nullable|string|max:191',
        ];
    }
}
