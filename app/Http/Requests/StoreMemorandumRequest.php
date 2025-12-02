<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMemorandumRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:255'],
            'cedula' => ['nullable', 'string', 'max:255'],
            'cargo' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
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
            'estado' => ['nullable', Rule::in(['pending', 'en_proceso', 'finalizado'])],
            'prioridad' => ['nullable', Rule::in(['urgente', 'alta', 'media', 'baja'])],
            'vence_en' => ['nullable', 'date'],
        ];
    }
}
