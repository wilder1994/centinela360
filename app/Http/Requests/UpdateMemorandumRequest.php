<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemorandumRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->user()?->company_id;

        return [
            'puesto' => ['required', 'string', 'max:255'],
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::exists('memorandum_subjects', 'name')->where('company_id', $companyId),
            ],
            'body' => ['nullable', 'string'],
            'assigned_to' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('company_id', $companyId),
            ],
            'approved_by' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('company_id', $companyId),
            ],
            'estado' => ['required', Rule::in(['pending', 'en_proceso', 'finalizado'])],
            'prioridad' => ['required', Rule::in(['urgente', 'alta', 'media', 'baja'])],
            'vence_en' => ['nullable', 'date'],
        ];
    }
}
