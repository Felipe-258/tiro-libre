<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        // Obtener la lista de archivos en el directorio de avatares
        $avatars = Storage::disk('public')->files('avatars');
        // Convertir los archivos en URLs accesibles
        $avatars = File::files(public_path('avatars'));
        
        return view('profile.edit', [
            'user' => $request->user(),
            'avatars' => $avatars,
            'selectedAvatar' => $user->profile_photo_path, // Ruta del avatar seleccionado
        ]);
    }

    /**
     * Actualizar el avatar del usuario.
     */
    public function selectAvatar(Request $request, $avatarPath)
    {
        $user = $request->user();

        // Validar que el avatar seleccionado existe
        /* if (!Storage::disk('public')->exists('avatares/' . $avatarPath)) {
            return Redirect::route('profile.edit')->withErrors(['avatar' => 'Avatar no encontrado.']);
        } */

        // Actualiza el perfil del usuario con el nuevo avatar
        $user->photo = 'avatars/' . $avatarPath;
        $user->save();
        flash_notification('Avatar actualizado correctamente');
        sleep(0.5);
        return Redirect::route('profile.edit');
    }
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        /* if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        } */

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');

    }
}
