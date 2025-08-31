<?php

namespace App\Http\Controllers;

use App\Models\OwnerTurns;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Turn;
use App\Models\User;
use App\Http\Requests\StoreturnRequest;
use App\Http\Requests\UpdateturnRequest;
use App\Models\Field;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\PendingTurn;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;
use App\Jobs\SendWhatsAppNotificationJob;
use App\Models\PlayerRoom;
use App\Models\Room;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
class TurnController extends Controller
{
    /**
     * Muestra la vista con todos los turnos del usuario autenticado.
     * 
     * @return View
     */
    public function index(): View
    {
        $userId = Auth::id(); // Obtiene el ID del usuario autenticado
        $turns = collect(Turn::where('id_player', $userId)->get()); // Obtiene todos los turnos del usuario

        /* if ($turns->isEmpty()) {
            // Si no hay turnos, muestra una vista vacía
            return view('turns.index', [
                'turns' => $turns,
                'fields' => collect(),
            ]);
        } */

        // Obtiene los IDs de los campos relacionados con los turnos
        $fieldIds = $turns->pluck('id_field')->unique();
        $fields = Field::whereIn('id', $fieldIds)->get()->keyBy('id'); // Obtiene los campos y los indexa por ID

        $rooms = PlayerRoom::with('room.turn')->where('id_player', $userId)->get();

        return view('turns.index', [
            'turns' => $turns,
            'fields' => $fields,
            'rooms' => $rooms
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo turno.
     * 
     * @return View
     */
    public function create(): View
    {
        return view('turns.create');
    }

    /**
     * Actualiza un turno existente con la información proporcionada.
     * 
     * @param UpdateTurnRequest $request
     * @param Turn $turn
     * @return RedirectResponse
     */
    public function update(UpdateTurnRequest $request, Turn $turn): RedirectResponse
    {
        $turn->update($request->all()); // Actualiza el turno con los datos del request
        flash_notification('Error al enviar la ubicación.', 'danger');
        return redirect()->back()
            /* ->withSuccess('Turn is updated successfully.') */ ;
    }

    /**
     * Elimina un turno existente.
     * 
     * @param Turn $turn
     * @return RedirectResponse
     */
    public function destroy(Turn $turn): RedirectResponse
    {
        $turn->delete(); // Elimina el turno
        flash_notification('Turn is deleted successfully.');
        return redirect()->back()/*
->withSuccess('Turn is deleted successfully.') */ ;
    }

    /**
     * Muestra todas las solicitudes pendientes para los propietarios.
     * 
     * @return View
     */
    /*     public function pendingRequests()
        {
            if (auth()->user()->hasRole('owner')) {
                $ownerId = auth()->user()->id; // Obtiene el ID del propietario

                // Obtiene todas las canchas del propietario
                $fields = Field::where('id_owner', $ownerId)->get();

                // Inicializa la colección de turnos pendientes
                $pendingTurns = collect();

            // Obtiene la fecha actual
            $today = now()->startOfDay(); // Obtiene el inicio del día actual

            // Itera sobre cada cancha y agrega los turnos pendientes a la colección
            foreach ($fields as $field) {
                $turns = PendingTurn::with('player', 'field')
                    ->where('id_field', $field->id)
                    ->where('deny', false)
                    ->whereDate('day', '>=', $today) // Filtra los turnos que son a partir de hoy
                    ->get();
                $pendingTurns = $pendingTurns->merge($turns);
            }
                // Si no hay turnos pendientes, devuelve una colección vacía
                if ($pendingTurns->isEmpty()) {
                    $pendingTurns = collect();
                }
                return view('turns.pending', [
                    'pendingTurns' => $pendingTurns
                ]);
            } else {
                return $this->index(); // Para usuarios que no son propietarios, muestra sus propios turnos
            }
        }
     */
    public function pendingRequests()
    {
        if (auth()->check() && auth()->user()->hasRole('owner') || auth()->user()->hasRole('super-admin')) {
            $ownerId = auth()->user()->id; // Obtiene el ID del propietario

            // Obtiene todas las canchas del propietario
            $fields = Field::where('id_owner', $ownerId)->get();

            // Inicializa la colección de turnos pendientes
            $pendingTurns = collect();

            // Obtiene la fecha y hora actual
            $now = now(); // Obtiene la fecha y hora actuales

            // Itera sobre cada cancha y agrega los turnos pendientes a la colección
            foreach ($fields as $field) {
                $turns = PendingTurn::with('player', 'field')
                    ->where('id_field', $field->id)
                    ->where('deny', false)
                    ->where('day', '>=', $now) // Filtra los turnos que son a partir de la fecha y hora actuales
                    ->get();
                $pendingTurns = $pendingTurns->merge($turns);
            }

            // Si no hay turnos pendientes, devuelve una colección vacía
            if ($pendingTurns->isEmpty()) {
                $pendingTurns = collect();
            }

            return view('turns.pending', [
                'pendingTurns' => $pendingTurns
            ]);
        } else {
            return $this->index(); // Para usuarios que no son propietarios, muestra sus propios turnos
        }
    }

    /**
     * Autoriza una solicitud pendiente y la mueve a la tabla de turnos.
     * 
     * @param int $id
     * @return RedirectResponse
     */
    public function authorizeRequest($id)
    {
        $pendingTurn = PendingTurn::with('field')->find($id);

        if ($pendingTurn) {
            // Mueve la solicitud pendiente a la tabla de turnos
            Turn::create([
                'id_player' => $pendingTurn->id_player,
                'id_field' => $pendingTurn->id_field,
                'day' => $pendingTurn->day,
            ]);

            // Elimina la solicitud pendiente
            $pendingTurn->delete();
            Carbon::setLocale('es'); // Establece el locale a español
            $carbonDate = Carbon::parse($pendingTurn->day);

            // Formatea la fecha y hora para el mensaje
            $fecha = $carbonDate->translatedFormat('d \d\e F');
            $hora = $carbonDate->format('H');

            // Envía mensaje de WhatsApp notificando la aprobación del turno
            $coordinates = explode(', ', $pendingTurn->field->coordinates);

            // Envía mensaje de WhatsApp notificando el rechazo del turno
            SendWhatsAppNotificationJob::dispatch($pendingTurn->id_player, "✅ Tu reserva de la cancha " . $pendingTurn->field->name . " ha sido autorizada ✅. Para el 📆 " . $fecha . " a las " . $hora . "hs");

            // Despacha el Job para enviar la ubicación
            SendWhatsAppNotificationJob::dispatch($pendingTurn->id_player, "", $coordinates[0], $coordinates[1]);
            flash_notification('Se le notifico al jugador');
            return redirect()->route('turns.pending');
        }

        flash_notification('Solicitud no encontrada.', 'danger');
        return redirect()->route('turns.pending');
    }

    /**
     * Rechaza una solicitud pendiente.
     * 
     * @param int $id
     * @return RedirectResponse
     */
    public function denyRequest($id)
    {
        $pendingTurn = PendingTurn::find($id);

        if ($pendingTurn) {
            // Elimina la solicitud pendiente
            //$pendingTurn->delete();
            Carbon::setLocale('es'); // Establece el locale a español
            $carbonDate = Carbon::parse($pendingTurn->day);

            // Formatea la fecha y hora para el mensaje
            $fecha = $carbonDate->translatedFormat('d \d\e F');
            $hora = $carbonDate->format('H');

            flash_notification('Se le notifico al jugador');
            $coordinates = explode(', ', $pendingTurn->field->coordinates);

            // sleep(0.5);  Pausa de medio segundo

            // Envía mensaje de WhatsApp notificando el rechazo del turno
            SendWhatsAppNotificationJob::dispatch($pendingTurn->id_player, "⛔ Tu reserva de la cancha " . $pendingTurn->field->name . " ha sido rechazada ⛔. Para el 🗓 " . $fecha . " a las " . $hora . "hs");

            // Despacha el Job para enviar la ubicación
            SendWhatsAppNotificationJob::dispatch($pendingTurn->id_player, "", $coordinates[0], $coordinates[1]);

            // Actualiza el campo 'deny' a true
            $pendingTurn->deny = true;
            //$pendingTurn->state = 2;
            $pendingTurn->save();
            return redirect()->route('turns.pending');
        }

        flash_notification('Solicitud no encontrada.', 'danger');
        return redirect()->route('turns.pending');
    }

    /**
     * Crea una solicitud de turno en la tabla de turnos pendientes.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    function store(Request $request)
    {
        // Crea una solicitud en pending_turns
        $pendingTurn = PendingTurn::create([
            'id_player' => auth()->user()->id,
            'id_field' => $request->id_field,
            'day' => $request->day,
        ]);

        // Lógica para enviar el mensaje por WhatsApp
        $user = User::find($pendingTurn->id_player);
        flash_notification('El Canchero ha sido notificado.', 'success');

        $owner = Field::with('owner')->find($request->id_field);
        $ownerId = $owner->owner->id;
        $fieldName = $owner->name;

        $carbonDate = Carbon::parse($request->day);

        // Formatea la fecha y hora para el mensaje
        $fecha = $carbonDate->translatedFormat('d \d\e F');
        $hora = $carbonDate->format('H');

        SendWhatsAppNotificationJob::dispatch($ownerId, "Se solicito un turno para tu cancha " . $fieldName . ". Para el 🗓 " . $fecha . " a las " . $hora . "hs. Entra a Tiro Libre para aceptar o rechazarla");


        return redirect()->back()/* ->with([
'message' => 'Solicitud enviada, esperando aprobación.',
]) */ ;
    }
        
    public function eraseTurn($id)
    {
        $pendingTurn = OwnerTurns::find($id);
        if ($pendingTurn) {
            $pendingTurn->deny = true;
            $pendingTurn->save();
            flash_notification(message: 'Turno eliminado.', type: 'success');
            return redirect()->route('owner-turns');
        }

        flash_notification('Solicitud no encontrada.', 'danger');
        return redirect()->route('owner-turns');
    }
    function createTurn(Request $request)
    {
         // Crear el timestamp a partir de la entrada del request
         $timestamp = Carbon::createFromFormat('Y-m-d H', "$request->day $request->hour");
        $existingTurnOnline = Turn::where('id_field', $request->field)
            ->where('day', $timestamp)
            ->first();
    
        if ($existingTurnOnline) {
            // Si existe un turno, puedes devolver un mensaje de error
            $user=User::findOrFail($existingTurnOnline->id_player);
            
            //dd($user);
            flash_notification('Ya existe un turno para esta fecha y hora. Pedido por ' . $user->name . ' ' . $user->surname, type: 'info');
            return redirect()->back();
        }

        // Verificar si ya existe un turno en esa fecha y hora
        $existingTurn = OwnerTurns::where('id_field', $request->field)
            ->where('day', $timestamp)
            ->first();
    
        if ($existingTurn) {
            // Si existe un turno, puedes devolver un mensaje de error
            flash_notification('Ya existe un turno para esta fecha y hora.', type: 'info');
            return redirect()->back();
        }
    
        // Crea un nuevo turno si no existe
        OwnerTurns::create([
            'id_owner' => auth()->user()->id,
            'id_field' => $request->field,
            'player' => $request->name,
            'day' => $timestamp,
        ]);
    
        flash_notification('Turno Cargado Correctamente');
        return redirect()->back();
    }
    

}