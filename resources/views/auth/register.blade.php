<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Surname --}}
        <div>
            <x-input-label for="surname" :value="__('Apellido')" />
            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')" required autofocus autocomplete="surname" />
            <x-input-error :messages="$errors->get('surname')" class="mt-2" />
        </div>
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        
        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Teléfono')" />
            <div class="flex">
                <select id="phone_prefix" name="phone_prefix" class="block mt-1 w-1/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    {{-- <option value="+1">+1 (Estados Unidos, Canadá)</option>
                    <option value="+7">+7 (Rusia, Kazajistán)</option>
                    <option value="+20">+20 (Egipto)</option>
                    <option value="+27">+27 (Sudáfrica)</option>
                    <option value="+30">+30 (Grecia)</option>
                    <option value="+31">+31 (Países Bajos)</option>
                    <option value="+32">+32 (Bélgica)</option>
                    <option value="+33">+33 (Francia)</option>
                    <option value="+34">+34 (España)</option>
                    <option value="+36">+36 (Hungría)</option>
                    <option value="+39">+39 (Italia)</option>
                    <option value="+40">+40 (Rumanía)</option>
                    <option value="+41">+41 (Suiza)</option>
                    <option value="+44">+44 (Reino Unido)</option>
                    <option value="+45">+45 (Dinamarca)</option>
                    <option value="+46">+46 (Suecia)</option>
                    <option value="+47">+47 (Noruega)</option>
                    <option value="+48">+48 (Polonia)</option>
                    <option value="+49">+49 (Alemania)</option>
                    <option value="+51">+51 (Perú)</option>
                    <option value="+52">+52 (México)</option>
                    <option value="+53">+53 (Cuba)</option> --}}
                    <option value="+54">+54 (Argentina)</option>
                    {{-- <option value="+55">+55 (Brasil)</option>
                    <option value="+56">+56 (Chile)</option>
                    <option value="+57">+57 (Colombia)</option>
                    <option value="+58">+58 (Venezuela)</option> --}}
                    {{-- <option value="+60">+60 (Malasia)</option>
                    <option value="+61">+61 (Australia)</option>
                    <option value="+62">+62 (Indonesia)</option>
                    <option value="+63">+63 (Filipinas)</option>
                    <option value="+64">+64 (Nueva Zelanda)</option>
                    <option value="+65">+65 (Singapur)</option>
                    <option value="+66">+66 (Tailandia)</option>
                    <option value="+81">+81 (Japón)</option>
                    <option value="+82">+82 (Corea del Sur)</option>
                    <option value="+84">+84 (Vietnam)</option>
                    <option value="+86">+86 (China)</option>
                    <option value="+90">+90 (Turquía)</option>
                    <option value="+91">+91 (India)</option>
                    <option value="+92">+92 (Pakistán)</option>
                    <option value="+93">+93 (Afganistán)</option>
                    <option value="+94">+94 (Sri Lanka)</option>
                    <option value="+95">+95 (Myanmar)</option>
                    <option value="+98">+98 (Irán)</option>
                    <option value="+211">+211 (Sudán del Sur)</option>
                    <option value="+212">+212 (Marruecos)</option>
                    <option value="+213">+213 (Argelia)</option>
                    <option value="+216">+216 (Túnez)</option>
                    <option value="+218">+218 (Libia)</option>
                    <option value="+220">+220 (Gambia)</option>
                    <option value="+221">+221 (Senegal)</option>
                    <option value="+222">+222 (Mauritania)</option>
                    <option value="+223">+223 (Mali)</option>
                    <option value="+224">+224 (Guinea)</option>
                    <option value="+225">+225 (Costa de Marfil)</option>
                    <option value="+226">+226 (Burkina Faso)</option>
                    <option value="+227">+227 (Níger)</option>
                    <option value="+228">+228 (Togo)</option>
                    <option value="+229">+229 (Benín)</option>
                    <option value="+230">+230 (Mauricio)</option>
                    <option value="+231">+231 (Liberia)</option>
                    <option value="+232">+232 (Sierra Leona)</option>
                    <option value="+233">+233 (Ghana)</option>
                    <option value="+234">+234 (Nigeria)</option>
                    <option value="+235">+235 (Chad)</option>
                    <option value="+236">+236 (República Centroafricana)</option>
                    <option value="+237">+237 (Camerún)</option>
                    <option value="+238">+238 (Cabo Verde)</option>
                    <option value="+239">+239 (Santo Tomé y Príncipe)</option>
                    <option value="+240">+240 (Guinea Ecuatorial)</option>
                    <option value="+241">+241 (Gabón)</option>
                    <option value="+242">+242 (República del Congo)</option>
                    <option value="+243">+243 (República Democrática del Congo)</option>
                    <option value="+244">+244 (Angola)</option>
                    <option value="+245">+245 (Guinea-Bisáu)</option>
                    <option value="+246">+246 (Territorio Británico del Océano Índico)</option>
                    <option value="+248">+248 (Seychelles)</option>
                    <option value="+249">+249 (Sudán)</option>
                    <option value="+250">+250 (Ruanda)</option>
                    <option value="+251">+251 (Etiopía)</option>
                    <option value="+252">+252 (Somalia)</option>
                    <option value="+253">+253 (Yibuti)</option>
                    <option value="+254">+254 (Kenia)</option>
                    <option value="+255">+255 (Tanzania)</option>
                    <option value="+256">+256 (Uganda)</option>
                    <option value="+257">+257 (Burundi)</option>
                    <option value="+258">+258 (Mozambique)</option>
                    <option value="+260">+260 (Zambia)</option>
                    <option value="+261">+261 (Madagascar)</option>
                    <option value="+262">+262 (Reunión)</option>
                    <option value="+263">+263 (Zimbabue)</option>
                    <option value="+264">+264 (Namibia)</option>
                    <option value="+265">+265 (Malaui)</option>
                    <option value="+266">+266 (Lesoto)</option>
                    <option value="+267">+267 (Botsuana)</option>
                    <option value="+268">+268 (Esuatini)</option>
                    <option value="+269">+269 (Comoras)</option>
                    <option value="+290">+290 (Santa Elena, Ascensión y Tristán de Acuña)</option>
                    <option value="+291">+291 (Eritrea)</option>
                    <option value="+297">+297 (Aruba)</option>
                    <option value="+298">+298 (Islas Feroe)</option>
                    <option value="+299">+299 (Groenlandia)</option>
                    <option value="+350">+350 (Gibraltar)</option>
                    <option value="+351">+351 (Portugal)</option>
                    <option value="+352">+352 (Luxemburgo)</option>
                    <option value="+353">+353 (Irlanda)</option>
                    <option value="+354">+354 (Islandia)</option>
                    <option value="+355">+355 (Albania)</option>
                    <option value="+356">+356 (Malta)</option>
                    <option value="+357">+357 (Chipre)</option>
                    <option value="+358">+358 (Finlandia)</option>
                    <option value="+359">+359 (Bulgaria)</option>
                    <option value="+370">+370 (Lituania)</option>
                    <option value="+371">+371 (Letonia)</option>
                    <option value="+372">+372 (Estonia)</option>
                    <option value="+373">+373 (Moldavia)</option>
                    <option value="+374">+374 (Armenia)</option>
                    <option value="+375">+375 (Bielorrusia)</option>
                    <option value="+376">+376 (Andorra)</option>
                    <option value="+377">+377 (Mónaco)</option>
                    <option value="+378">+378 (San Marino)</option>
                    <option value="+380">+380 (Ucrania)</option>
                    <option value="+381">+381 (Serbia)</option> --}}
                </select>
                <x-text-input 
                    id="phone" 
                    class="block mt-1 w-full ms-2" 
                    type="text" 
                    name="phone" 
                    :value="old('phone')" 
                    required 
                    autocomplete="phone" 
                    placeholder="Número de teléfono"
                    pattern="[0-9]*"
                    inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Select Role -->
        <div>
            <x-input-label for="role" :value="__('Rol')" />
            <select id="role" name="role" class="block mt-1 w-full" required>
                <option value="owner">Canchero</option>
                <option value="player">Jugador</option>
            </select>
        </div>
        

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
