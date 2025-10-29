<?php

namespace App\Http\Requests\Category;
// 
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            //

            'name' => 'required|string|max:255|unique:categories,name',
            // 'slug' => 'required|string|max:255|unique:categories,slug',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'description' => 'nullable|string',
        ];
    }

    // this function is for custom validation messages
    public function messages(): array
    {
        return [
            // 'name.required' => 'The name field is required.',
            'name.required' => 'The name field is required. Please enter a name.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field must not exceed 255 characters.',
            'name.unique' => 'This name is already registered. Please choose another name.',
            'image.image' => 'The image field must be an image.',
            'image.mimes' => 'The image field must be a file of type: jpg, png, jpeg, gif.',
            'image.max' => 'The image field must not exceed 2MB in size.',
            'description.string' => 'The description field must be a string.',
        ];
    }

    /**
     * Override the failedValidation method to use apiValidationError()
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiValidationError($validator->errors())
        );
    }
}
