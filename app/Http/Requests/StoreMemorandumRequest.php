<?php

namespace App\Http\Requests;

use App\Models\Memorandum;
use Illuminate\Foundation\Http\FormRequest;

class StoreMemorandumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'responsible_id' => ['required', 'exists:users,id'],
            'status' => ['nullable', 'in:' . implode(',', Memorandum::STATUSES)],
        ];
    }
}
