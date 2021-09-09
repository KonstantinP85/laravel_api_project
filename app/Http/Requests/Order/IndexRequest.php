<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'page' => 'nullable|numeric|min:1',
            'limit' => 'nullable|numeric|min:1',
            'status' => 'nullable|alpha',
            'product_id' => 'nullable|numeric|min:1',
            'client_id' => 'nullable|numeric|min:1',
        ];
    }
}
