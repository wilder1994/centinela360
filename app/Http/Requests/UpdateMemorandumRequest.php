<?php

namespace App\Http\Requests;

use App\Models\Memorandum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMemorandumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'body' => ['sometimes', 'required', 'string'],
            'responsible_id' => ['sometimes', 'required', 'exists:users,id'],
            'status' => ['sometimes', 'required', 'in:' . implode(',', Memorandum::STATUSES)],
        ];
    }
}
