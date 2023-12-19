<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class HotelCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role == 'ADMIN';
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
                'location' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp',
                'total_rooms' => 'nullable|integer|min:0',
                'contact_email' => 'required|email',
                'contact_phone' => 'required|string',
                'is_available' => 'required|boolean',
        ];
    }
}
