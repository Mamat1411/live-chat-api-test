<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isUpdate = in_array($this->method(), ["PUT","PATCH"]);

        return [
            'name' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:100'],
            'username' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:100',
                Rule::unique('users')->ignore($this->user?->id),
            ],
            'email'=> [
                $isUpdate ? 'sometimes' : 'required',
                'email',
                'max:100',
                Rule::unique('users')->ignore($this->user?->id),
            ],
            'password' => [$isUpdate ? 'sometimes' : 'required', 'string'],
        ];
    }
}
