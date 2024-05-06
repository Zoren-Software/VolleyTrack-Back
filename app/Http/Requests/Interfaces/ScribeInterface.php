<?php

namespace App\Http\Requests\Interfaces;

interface ScribeInterface
{
    public function bodyParameters(): array;

    public function rules(): array;
}
