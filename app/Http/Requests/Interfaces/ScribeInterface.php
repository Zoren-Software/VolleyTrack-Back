<?php

namespace App\Http\Requests\Interfaces;

interface ScribeInterface
{
    /**
     * @return array<string, array<string, string>>
     */
    public function bodyParameters(): array;

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array;
}
