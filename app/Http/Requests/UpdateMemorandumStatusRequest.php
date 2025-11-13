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
        $statuses = implode(',', Memorandum::statuses());

        return [
            'status' => ['required', 'in:' . $statuses],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
