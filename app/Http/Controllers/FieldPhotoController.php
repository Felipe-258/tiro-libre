<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Field;
use App\Models\FieldPhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; // Make sure to only use this import

class FieldPhotoController extends Controller
{
    public function store(Request $request, $fieldId)
    {
        // Validate the incoming request data
        $request->validate([
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $field = Field::findOrFail($fieldId);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('field_photos', 'public');

                FieldPhoto::create([
                    'field_id' => $field->id,
                    'photo_path' => $path,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Fotos subidas correctamente.');
    }

    public function deletePhoto($id)
{
    // Busca la foto por ID
    $photo = FieldPhoto::find($id);

    if ($photo) {
        $path = storage_path('app/public/' . $photo->photo_path);

        // Verifica si el archivo existe y lo elimina
        if (File::exists($path)) {
            File::delete($path);
            // Elimina el registro de la base de datos
            $photo->delete();
            flash_notification('Foto Eliminada con exito');
            return redirect() ->back();
        } else {
            flash_notification('Error al eliminar la foto');
            return redirect() ->back();
        }
    } else {
        flash_notification('Error al eliminar la foto');
        return redirect() ->back();
    }
}

}
