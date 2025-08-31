<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Requests\StoreratingRequest;
use App\Http\Requests\UpdateratingRequest;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userIdAuth = Auth::id();
        $fieldId = $request->input('fieldId');
        $newRating = $request->input('rating');

        $rating = Rating::where('id_player', $userIdAuth)
            ->where('id_field', $fieldId)
            ->first();

        if (is_null($rating)) {
            Rating::create([
                'id_player' => $userIdAuth,
                'id_field' => $request->fieldId,
                'rating' => $newRating,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            flash_notification('Calificación actualizada con éxito.');
        } elseif ($rating->rating == $newRating) {
            $rating->delete();
            flash_notification('Calificación eliminada con éxito.', 'danger');
        } else {
            $rating->update([
                'rating' => $newRating,
                'updated_at' => now(),
            ]);
            flash_notification('Calificación actualizada con éxito.');

        }

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function start()
    {
        $topsRankeds = DB::table('ratings')
            ->select('id_field', DB::raw('AVG(rating) as average_rating'))
            ->groupBy('id_field')
            ->orderByDesc('average_rating')
            ->take(5)
            ->get();

        $fieldsTopsRankeds = [];

        foreach ($topsRankeds as $topRanked) {
            $fieldsTopsRankeds[] = DB::table('fields')
                ->where('id', $topRanked->id_field)
                ->first();
        }

        //Rooms
        $emergencyRooms = Room::with(['turn.field'])
            ->whereHas('turn', function ($query) {
                $query->where('day', '>', now()) // Filtra los turns que aún no han pasado
                    ->orderBy('day', 'desc'); // Ordena por el día más próximo
            })
            ->whereColumn('quantity', '<', 'max') // Comparar columnas 'quantity' y 'max'
            ->take(5)
            ->get();

        return view('screens.start', [
            'fields' => $fieldsTopsRankeds,
            'rooms' => $emergencyRooms
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreratingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateratingRequest $request, rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(rating $rating)
    {
        //
    }
}
