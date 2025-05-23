<?php

namespace App\Http\Requests;

use App\Http\Requests\Interfaces\ScribeInterface;
use App\Rules\ValidToken;
use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest implements ScribeInterface
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
            'token' => [
                'required',
                'string',
                new ValidToken,
            ],
            'tenantId' => [
                'required',
                'string',
                'unique:mysql.tenants,id',
            ],
            'email' => [
                'required',
                'email',
            ],
            'name' => [
                'required',
                'string',
            ],
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
            'token.required' => trans('TenantCreate.token.required'),
            'token.string' => trans('TenantCreate.token.string'),
            'tenantId.required' => trans('TenantCreate.tenantId.required'),
            'tenantId.unique' => trans('TenantCreate.tenantId.unique'),
            'tenantId.string' => trans('TenantCreate.tenantId.string'),
            'email.required' => trans('TenantCreate.email.required'),
            'email.email' => trans('TenantCreate.email.email'),
            'name.required' => trans('TenantCreate.name.required'),
            'name.string' => trans('TenantCreate.name.string'),
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'token' => [
                'description' => 'The token',
                'example' => 'token-test-1',
            ],
            'tenantId' => [
                'description' => 'The tenant ID',
                'example' => 'tenant-test-1',
            ],
            'email' => [
                'description' => 'The tenant email',
                'example' => 'email@test.com',
            ],
            'name' => [
                'description' => 'The tenant name',
                'example' => 'Tenant Test',
            ],
        ];
    }
}
