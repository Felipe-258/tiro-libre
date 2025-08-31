<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTurnRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'id_field' => 'required|exists:fields,id', // Verifica que el campo existe en la tabla fields
            'day' => 'required|date', // Verifica que es una fecha válida
            //'duration' => 'required|integer|min:1',  Verifica que es un entero y al menos 1
        ];
    }
}
