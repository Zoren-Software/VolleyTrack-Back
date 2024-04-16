<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        
        return [
            'tenantId' => [
                'required',
                'string',
                'unique:mysql.tenants,id'
            ],
            'email' => [
                'required',
                'email',
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tenantId.required' => trans('TenantCreate.tenantId.required'),
            'tenantId.unique' => trans('TenantCreate.tenantId.unique'),
            'tenantId.string' => trans('TenantCreate.tenantId.string'),
            'email.required' => trans('TenantCreate.email.required'),
            'email.email' => trans('TenantCreate.email.email')
        ];
    }
}
