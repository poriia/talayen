<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderSellRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|numeric|min:0.001',
            'price' => 'required|numeric|min:1',
        ];
    }
}
