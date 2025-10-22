<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
// 
use Illuminate\Validation\Validator;

class UpdateProfileRequest extends FormRequest
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
        $userId = Auth::id(); // للحصول على معرف المستخدم الحالي

        return [
            'name' => 'sometimes|string|max:255',
            // // not allow the use for update the email 
            // 'email' => 'sometimes|string|email|unique:users,email,' . $userId,
            'phone' => 'nullable|string|unique:users,phone,' . $userId,
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
        ];
    }


    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('email')) {
                $validator->errors()->add('email', 'Email address cannot be updated.');
            }
        });
    }
}
