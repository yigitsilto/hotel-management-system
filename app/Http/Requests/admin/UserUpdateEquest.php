<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateEquest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role == 'ADMIN' || auth()->user()->role == 'WORKER';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'identity_number' => 'required|numeric|digits:11',
            'phone_number' => 'required|regex:/^\d{3}-\d{3}-\d{4}$/',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8',
            'can_do_reservation' => 'required|boolean',
            'role' => 'nullable|in:USER,ADMIN,WORKER',
            'authorized_hotels' => 'nullable|array',
        ];
    }
}
