<?php

namespace App\Http\Requests;

use App\Enums\MemorandumStatus;
use Illuminate\Foundation\Http\FormRequest;

class StoreMemorandumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $statuses = implode(',', array_map(fn (MemorandumStatus $status) => $status->value, MemorandumStatus::cases()));

        return [
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'issued_at' => ['nullable', 'date'],
            'status' => ['nullable', 'in:' . $statuses],
            'acknowledged_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
