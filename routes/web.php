<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\FieldPhotoController;
use App\Http\Controllers\TurnController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PlayerRoomController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\OwnerTurnsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::middleware(['auth'])->group(function () {

    /* Route to view the system without authentication */
    Route::get('/', [RatingController::class, 'start'])->name('start');
    Route::get('/start', [RatingController::class, 'start'])->name('/start');

    /* Guest access without login */
    Route::get('/setPlayerTest', [GuestController::class, 'setPlayerTest'])->name('setPlayerTest');

    /* Fields routes */
    Route::resource('fields', FieldController::class);
    Route::post('fields/search', [FieldController::class, 'search'])->name('fields.search');
    Route::get('allfields', [FieldController::class, 'allTeam'])->name('allTeam');
    Route::get('ourteam', [FieldController::class, 'indexTeam'])->name('team');

    /* Profile routes */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/avatar/{avatarPath}', [ProfileController::class, 'selectAvatar'])->name('profile.selectAvatar');
    
    /* Fields routes for authenticated users */
    Route::get('my-Fields', [FieldController::class, 'showMyFields'])->name('fields.showMyFields');
    Route::delete('/photo/delete/{id}', [FieldController::class, 'deletePhoto'])->name('photo.delete');
    Route::get('fields-userCoordinates', [FieldController::class, 'userCoordinates'])->name('userCoordinates');

    /* Turns routes */
    Route::resource('turns', TurnController::class);
    Route::get('turns-pending', [TurnController::class, 'pendingRequests'])->name('turns.pending');
    Route::post('turns-approve-{id}', [TurnController::class, 'authorizeRequest'])->name('turns.approve');
    Route::post('turns-deny-{id}', [TurnController::class, 'denyRequest'])->name('turns.deny');
    Route::post('turns-cancel-{id}', [TurnController::class, 'cancelTurn'])->name('turns.cancel');

    /* Owner Turns routes */
    Route::get('owner-turns', [OwnerTurnsController::class, 'ownerTurnsView'])->name('owner-turns');
    Route::post('owner-turns-deny-{id}', [OwnerTurnsController::class, 'eraseTurn'])->name('turns.owner.eraseTurn');
    Route::post('owner-turns-create', [OwnerTurnsController::class, 'createTurn'])->name('turns.owner.createTurn');
    Route::post('/turns/change-state/{table}', [OwnerTurnsController::class, 'changeState'])->name('turns.changeState');

    /* Exportar a PDF */
    Route::get('/export-turns-pdf', [OwnerTurnsController::class, 'exportTurnsToPDF'])->name('export.turns.pdf');
    Route::get('/export-turns', [OwnerTurnsController::class, 'exportTurnsToPDF'])->name('export.turns');

    /* Calendar routes */
    Route::get('calendar-owner', [CalendarController::class, 'owner'])->name('calendar.owner');
    /* Month */
    Route::get('/ruta/pasado-dia/{date}', [OwnerTurnsController::class, 'ownerTurnsView'])->name('past.day');
    Route::get('/ruta/futuro-dia/{date}', [OwnerTurnsController::class, 'ownerTurnsView'])->name('future.day');
    /* Week and Day */
    Route::get('/ruta/pasado-hora/{date}', [OwnerTurnsController::class, 'ownerTurnsView'])->name('past.hour');
    Route::get('/ruta/futuro-hora/{date}', [OwnerTurnsController::class, 'ownerTurnsView'])->name('future.hour');


    Route::get('calendar-player', [CalendarController::class, 'player'])->name('calendar.player');

    /* Message and notification routes */
    Route::get('message', [MessageController::class, 'message'])->name('message');
    Route::get('notification', [NotificationController::class, 'notification'])->name('notification');

    /* Rating routes */
    Route::post('rating', [RatingController::class, 'index'])->name('rating.index');

    /* Room routes */
    Route::get('room', [RoomController::class, 'index'])->name('room');
    Route::get('room/{id}', [RoomController::class, 'show'])->name('room.show');
    Route::get('rooms/create', [RoomController::class, 'create'])->name('room.create');
    Route::post('rooms/store', [RoomController::class, 'store'])->name('room.store');
    Route::post('joinRoom/{id}', [PlayerRoomController::class, 'joinRoom'])->name('joinRoom');
    Route::post('leaveRoom/{id}', [PlayerRoomController::class, 'leaveRoom'])->name('leaveRoom');
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('room.destroy');
});

/* Upgrade Max File Size*/
ini_set('upload_max_filesize', '10M');


require __DIR__ . '/auth.php';
