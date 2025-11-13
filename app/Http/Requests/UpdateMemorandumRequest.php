<?php

namespace App\Http\Requests;

use App\Enums\MemorandumStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMemorandumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $statuses = implode(',', array_map(fn (MemorandumStatus $status) => $status->value, MemorandumStatus::cases()));

        return [
            'subject' => ['sometimes', 'required', 'string', 'max:255'],
            'body' => ['sometimes', 'required', 'string'],
            'employee_id' => ['sometimes', 'nullable', 'exists:employees,id'],
            'issued_at' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', 'required', 'in:' . $statuses],
            'acknowledged_at' => ['sometimes', 'nullable', 'date'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:500'],
        ];
    }
}
