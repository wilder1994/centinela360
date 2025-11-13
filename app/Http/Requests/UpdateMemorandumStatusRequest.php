<?php

namespace App\Http\Requests;

use App\Enums\MemorandumStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMemorandumStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $statuses = implode(',', array_map(fn (MemorandumStatus $status) => $status->value, MemorandumStatus::cases()));

        return [
            'status' => ['required', 'in:' . $statuses],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
