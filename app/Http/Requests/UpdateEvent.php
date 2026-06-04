<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvent extends FormRequest
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
            'title' => "required|max:255|string",
            'description' => "nullable|string",
            'location' => "nullable",
            'date' => "required|date",     //search about date_format
            'AvalibleSeats' => "required|numeric",
            'category_id' => "required|numeric",
            'images.*'  =>  "nullable|image|mimes:jpg,png,jpeg|max:51200",
        ];
    }
}
