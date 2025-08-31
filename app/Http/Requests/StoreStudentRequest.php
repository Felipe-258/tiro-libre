<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'Dni' => 'required|integer|max:8|unique:students,code',
            'Nombre' => 'required|string|max:250',
            'Apellido' => 'required|string|max:250',
            'Nacimiento' => 'required|date',
            'Asistencias' => 'required|integer|min:1|max:366',
            'Limitacion' => 'required|string|min:1|max:10000',
            'Grupo' => 'required|string|max:1',
        ];
    }
}