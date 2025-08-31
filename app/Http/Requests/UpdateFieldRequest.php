<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Asegúrate de que el usuario esté autorizado a hacer esta solicitud
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:30',
            'capacity' => 'required|integer|min:10|max:22',
            'coordinates' => 'required|string',
            'price' => 'required|numeric|min:1|max:999999999',
            'type' => 'required|string|in:Sintetico,Natural,Tierra,Cemento,Arena',
            'description' => 'nullable|string|max:255',
            'start' => 'required|string',
            'end' => 'required|string',
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
