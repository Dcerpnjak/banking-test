<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'source_account_id' => ['required', 'exists:accounts,id'],
            'target_account_id' => ['required', 'exists:accounts,id', 'different:source_account_id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
