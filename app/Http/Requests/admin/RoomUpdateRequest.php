<?php

namespace App\Http\Requests\admin;

use App\Enums\RoomTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class RoomUpdateRequest extends FormRequest
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
                'title' => 'required|string|max:255',
                'room_type' => ['required', 'string', 'in:' . implode(',', RoomTypeEnum::getStringValue()) ],
                'description' => 'required|string',
                'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp',
                'capacity' => 'nullable|integer|min:0',
                'is_available' => 'required|boolean',
                'price' => 'required|numeric|min:0',
        ];
    }
}
