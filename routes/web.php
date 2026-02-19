<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GameController;

Route::get('/', [GameController::class, 'setupForm'])->name('setup');
Route::post('/game/setup', [GameController::class, 'setupStore'])->name('game.setup');
Route::get('/game/{game}/players/create', [GameController::class, 'createPlayers'])->name('game.players.create');
Route::post('/game/{game}/players', [GameController::class, 'storePlayers'])->name('game.players.store');
Route::get('/game/{game}', [GameController::class, 'show'])->name('game.show');
Route::post('/game/{game}/next-question', [GameController::class, 'nextQuestion'])->name('game.nextQuestion');
Route::post('/game/{game}/roll-dice/{player}', [GameController::class, 'rollDice'])->name('game.rollDice');
Route::post('/game/{game}/ranking', [GameController::class, 'getRanking'])->name('game.ranking');