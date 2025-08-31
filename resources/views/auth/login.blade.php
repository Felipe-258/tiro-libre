<br>
<br>
<br>
<br>
<style>
.btn-custom {
    background-color: #4a90e2; /* Color de fondo elegante */
    color: white; /* Texto en blanco para buen contraste */
    border: none; /* Sin borde para un look limpio */
    padding: 10px 20px; /* Espaciado para hacerlo más delgado y refinado */
    font-size: 14px; /* Tamaño de fuente moderado */
    font-weight: 500; /* Peso de fuente medio para darle presencia */
    border-radius: 20px ; /* Bordes redondeados para suavidad */
    transition: background-color 0.3s ease; /* Transición suave para efecto hover */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra ligera para darle profundidad */
}

.btn-custom:hover {
    background-color: #357ABD; /* Color de fondo un poco más oscuro al pasar el ratón */
    text-decoration: none; /* Elimina subrayado al pasar el ratón */
}
</style>
<x-guest-layout>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        {{-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
            </label>
        </div> --}}

        <div class="flex items-center justify-end mt-4 space-x-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                {{ __('Registrarse') }}
            </a>
        
            <x-primary-button class="ml-3">
                {{ __('Acceso') }}
            </x-primary-button>
        </div>
    
    </div>
    <br>        
    <a href="{{ route('setPlayerTest') }}" class="btn btn-primary btn-custom">Modo de Prueba (proximamente)</a>
    </div>
    </form>

    
</x-guest-layout>
