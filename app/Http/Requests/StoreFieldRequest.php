<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFieldRequest extends FormRequest
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
            'photos' => 'nullable|array',   // Aceptar un arreglo de fotos (si se envían)
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'photos.*.image' => 'Cada archivo debe ser una imagen válida.',
            'photos.*.mimes' => 'Solo se permiten imágenes de tipo: jpeg, png, jpg, gif.',
            'photos.*.max' => 'Cada imagen no debe superar los 10 MB de tamaño.',
        ];
    }
}
