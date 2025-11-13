<?php

namespace App\Http\Requests;

use App\Models\Memorandum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMemorandumStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:' . implode(',', Memorandum::STATUSES)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
