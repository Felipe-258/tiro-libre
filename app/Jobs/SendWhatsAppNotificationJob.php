<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendWhatsAppNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $playerId; // ID del usuario a quien se enviará el mensaje
    protected $message; // Mensaje de WhatsApp a enviar
    protected $latitude; // Latitud para enviar ubicación (opcional)
    protected $longitude; // Longitud para enviar ubicación (opcional)

    /**
     * Crea una nueva instancia del Job.
     *
     * @param int $playerId ID del usuario
     * @param string $message Mensaje a enviar
     * @param float|null $latitude Latitud de la ubicación (opcional)
     * @param float|null $longitude Longitud de la ubicación (opcional)
     */
    public function __construct($playerId, $message, $latitude = null, $longitude = null)
    {
        $this->playerId = $playerId;
        $this->message = $message;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Ejecuta el Job.
     *
     * @return void
     */
    public function handle()
    {
        // Ruta al script de Node.js que maneja la lógica de envío de WhatsApp
        $path = base_path('resources/js/whatsapp/whatsapp.js');

        // Encuentra al usuario por ID
        $player = User::find($this->playerId);

        // Verifica si el usuario existe y tiene un número de teléfono registrado
        if ($player && $player->phone) {
            $phoneNumber = $player->phone; // Obtiene el número de teléfono del usuario

            // Formatea el número de teléfono para añadir el código de área correcto
            $areaCode = substr($phoneNumber, 0, 2);
            $phoneNumber = $areaCode . 9 . substr($phoneNumber, 2);
            /* dd($phoneNumber); */
            // Comando para ejecutar el script de Node.js con los argumentos adecuados
            $command = "node \"$path\" \"$phoneNumber\" \"$this->message\"";

            if ($this->latitude !== null && $this->longitude !== null) {
                $command .= " \"$this->latitude\" \"$this->longitude\"";
            }


            // Ejecuta el comando de Node.js
            exec($command);
        }
    }
}
