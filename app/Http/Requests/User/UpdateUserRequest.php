<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('id'); // الحصول على معرف المستخدم من مسار الطلب

        return [
            'name'     => 'sometimes|required|string|max:255',
            'email'    => [
                'sometimes',
                'required',
                'string',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'sometimes|required|string|min:6',
            'phone'    => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s()]*$/',
                Rule::unique('users', 'phone')->ignore($userId),
            ],
            'address'  => 'nullable|string',
            'country'  => 'nullable|string',
            'city'     => 'nullable|string',
            'zip_code' => 'nullable|string',
            'status'   => ['nullable', Rule::in(['active', 'inactive'])],
        ];
    }
}
