<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::min(8), Password::default()],
//            'password_confirmation' => ['same:password']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('Name is required'),
            'name.string' => __('Name must be a string'),
            'name.max' => __('Name can not be more than 255 letter'),
            'email.required' => __('Email is required'),
            'email.email' => __('Enter a valid email'),
            'email.unique' => __('This email is used before'),
            'password.required' => __('Password is required'),
            'password.confirmed' => __('Password and password confirmation does not match'),
            'password.min' => __('Password can not be less than 8 characters'),
            'password_confirmation.required' => __('Password confirmation is required'),
            'password_confirmation.same' => __('Password and password confirmation does not match')
        ];
    }
}
