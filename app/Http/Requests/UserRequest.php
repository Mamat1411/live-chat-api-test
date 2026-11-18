<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
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
        return auth()->check() && auth()->user()->role === RoleEnum::ADMIN->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isUpdate = in_array($this->method(), ["PUT","PATCH"]);
        $userId = $this->route('user');

        return [
            'name' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:100'],
            'username' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:100',
                Rule::unique('users')->ignore($userId),
            ],
            'email'=> [
                $isUpdate ? 'sometimes' : 'required',
                'email',
                'max:100',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => [$isUpdate ? 'sometimes' : 'required', 'string'],
            'role' => [$isUpdate ? 'sometimes' : 'required', Rule::in(RoleEnum::values())],
        ];
    }
}
