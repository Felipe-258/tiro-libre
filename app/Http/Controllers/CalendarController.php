<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Field;
use App\Models\PendingTurn;
use App\Models\OwnerTurns;
use App\Models\User;

class CalendarController extends Controller
{
    public function owner() {
        $ownerId = auth()->user()->id;
        
        $turns = Turn::with('field')
            ->whereHas('field', function ($query) use ($ownerId) {
                $query->where('id_owner', $ownerId);
            })
            ->get();
    
        $ownerTurns = OwnerTurns::with('field')
            ->whereHas('field', function ($query) use ($ownerId) {
                $query->where('id_owner', $ownerId);
            })
            ->get();
    
        $turnEvents = $turns->map(function ($turn) {
            $start = Carbon::parse($turn->day)->format('Y-m-d\TH:i:s');
            $end = Carbon::parse($turn->day)->addHour()->format('Y-m-d\TH:i:s');
    
            $player = DB::table('users')->where('id', $turn->id_player)->first();
    
            $isPast = Carbon::now()->gt(Carbon::parse($end));
            $isCurrent = Carbon::now()->between(Carbon::parse($start), Carbon::parse($end));
    
            if ($isCurrent) {
                $backgroundColor = '#ff5100'; 
                $borderColor = '#ff5100';
                $estado = 'en curso';
            } elseif ($isPast) {
                $backgroundColor = 'red';
                $borderColor = 'black';
                $estado = 'finalizado';
            } else {
                $backgroundColor = 'green';
                $borderColor = 'black';
                $estado = 'confirmado';
            }
    
            return [
                'title' => $turn->field->name,
                'start' => $start,
                'end' => $end,
                'backgroundColor' => $backgroundColor,
                'borderColor' => $borderColor,
                'extendedProps' => [
                    'player' => $player->surname,
                    'price' => (string) $turn->field->price,
                    'estado' => $estado
                ]
            ];
        });
    
        $ownerTurnEvents = $ownerTurns->map(function ($ownerTurn) {
            $start = Carbon::parse($ownerTurn->day)->format('Y-m-d\TH:i:s');
            $end = Carbon::parse($ownerTurn->day)->addHour()->format('Y-m-d\TH:i:s');
    
            $isPast = Carbon::now()->gt(Carbon::parse($end));
            $isCurrent = Carbon::now()->between(Carbon::parse($start), Carbon::parse($end));
    
            if ($isCurrent) {
                $backgroundColor = '#ff5100'; 
                $borderColor = '#ff5100';
                $estado = 'en curso';
            } elseif ($isPast) {
                $backgroundColor = 'red';
                $borderColor = 'black';
                $estado = 'finalizado';
            } else {
                $backgroundColor = 'green';
                $borderColor = 'black';
                $estado = 'confirmado';
            }
    
            return [
                'title' => $ownerTurn->field->name,
                'start' => $start,
                'end' => $end,
                'backgroundColor' => $backgroundColor,
                'borderColor' => $borderColor,
                'extendedProps' => [
                    'price' => (string) $ownerTurn->field->price,
                    'player' => $ownerTurn->player,
                    'estado' => $estado
                ]
            ];
        });
    
        $events = collect($turnEvents)->merge(collect($ownerTurnEvents))->all();

        // Obtener todos los campos del propietario y determinar los límites de horario
        $fields = Field::where('id_owner', $ownerId)->get();

        // Verificar si hay campos
        if ($fields->isNotEmpty()) {
            // Obtener el start y end más extremos
            $minTime = $fields->min('start'); // Obtener el tiempo de inicio más temprano
            $maxTime = $fields->max('end'); // Obtener el tiempo de finalización más tardío

            // Formatear los tiempos para el calendario
            $minTime = Carbon::parse($minTime)->format('H:i:s');
            $maxTime = Carbon::parse($maxTime)->format('H:i:s');
        } else {
            // Si no hay campos, establecer valores por defecto
            $minTime = '06:00:00'; // Valor por defecto
            $maxTime = '24:00:00'; // Valor por defecto
        }

        if ($maxTime === '00:00:00') {
            $maxTime = '24:00:00';
        }

        // Retornar la vista con los eventos y tiempos
        return view('calendar.owner', [
            'events' => $events,
            'minTime' => $minTime,
            'maxTime' => $maxTime
        ]);
    }
    
    public function pastDay($date) {
        dd($date);
        return view('calendar.hour', compact('date'));
    }

    public function futureDay($date) {
        dd($date);
        return view('calendar.hour', compact('date'));
    }

    public function pastHour($date) {
        dd($date);
        return view('calendar.hour', compact('date'));
    }

    public function futureHour($date) {
        dd($date);
        return view('calendar.hour', compact('date'));
    }

    public function player() {
        $playerId = auth()->user()->id;
    
        // Obtener los turnos confirmados
        $turns = DB::table('turns')
            ->where('id_player', $playerId)
            ->get();
    
        // Obtener los turnos pendientes (futuros)
        $pendingTurns = DB::table('pending_turns')
            ->where('id_player', $playerId)
            ->where('day', '>=', Carbon::now()) // Filtrar por fechas futuras
            ->get();
    
        // Mapear los turnos confirmados a eventos
        $confirmedEvents = $turns->map(function ($turn) {
            $start = Carbon::parse($turn->day);
            $end = $start->copy()->addHour();
    
            $field = DB::table('fields')
                ->where('id', $turn->id_field)
                ->first();
    
            // Asignar color y estado basado en el estado del turno
            $currentTime = Carbon::now();
            if ($end->isPast()) {
                $backgroundColor = 'red'; // Turno finalizado
                $estado = 'finalizado';
            } elseif ($start->isPast() && $end->isFuture()) {
                $backgroundColor = 'orange'; // Turno corriendo
                $estado = 'en curso';
            } else {
                $backgroundColor = 'green'; // Turno confirmado
                $estado = 'confirmado';
            }
    
            return [
                'title' => $field->name,
                'start' => $start->format('Y-m-d\TH:i:s'),
                'end' => $end->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $backgroundColor,
                'borderColor' => 'black',
                'extendedProps' => [
                    'player' => auth()->user()->surname,
                    'price' => (string) $field->price,
                    'estado' => $estado // Estado dinámico basado en la lógica
                ]
            ];
        })->all();
    
        // Mapear los turnos pendientes a eventos
        $pendingEvents = $pendingTurns->map(function ($pendingTurn) {
            $start = Carbon::parse($pendingTurn->day);
            $end = $start->copy()->addHour();
    
            $field = DB::table('fields')
                ->where('id', $pendingTurn->id_field)
                ->first();
    
            // Asignar color azul para los turnos solicitados (pendientes)
            return [
                'title' => $field->name,
                'start' => $start->format('Y-m-d\TH:i:s'),
                'end' => $end->format('Y-m-d\TH:i:s'),
                'backgroundColor' => 'blue', // Turno solicitado (pendiente)
                'borderColor' => 'black',
                'extendedProps' => [
                    'player' => auth()->user()->surname,
                    'price' => (string) $field->price,
                    'estado' => 'solicitado' // Estado de turno pendiente (solicitado)
                ]
            ];
        })->all();
    
        // Combinar eventos confirmados y pendientes
        $events = array_merge($confirmedEvents, $pendingEvents);
        $minTime = '10:00:00'; // Valor por defecto
        $maxTime = '24:00:00';
        /* dd($events); */

        return view('calendar.player', ['events' => $events ,'minTime' => $minTime,
            'maxTime' => $maxTime]);
    }
}
