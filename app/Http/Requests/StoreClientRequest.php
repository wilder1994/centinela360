<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->user()?->company_id;

        return [
            'business_name' => ['required', 'string', 'max:255'],
            'nit' => [
                'required',
                'string',
                'max:50',
                Rule::unique('clients')->where('company_id', $companyId),
            ],
            'address' => ['required', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'service_count' => ['required', 'integer', 'min:1', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'representative_name' => ['required', 'string', 'max:255'],
            'quadrant' => ['required', 'string', 'max:50'],
            'service_types' => ['required', 'array'],
            'service_types.*' => ['required', 'string', 'max:100'],
            'service_schedules' => ['required', 'array'],
            'service_schedules.*' => ['required', 'string', 'max:100'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $serviceCount = (int) $this->input('service_count', 0);
            $types = $this->input('service_types', []);
            $schedules = $this->input('service_schedules', []);

            if (count($types) !== $serviceCount) {
                $validator->errors()->add('service_types', 'La cantidad de tipos de servicio debe coincidir con la cantidad declarada.');
            }

            if (count($schedules) !== $serviceCount) {
                $validator->errors()->add('service_schedules', 'La cantidad de horarios debe coincidir con la cantidad declarada.');
            }
        });
    }
}
