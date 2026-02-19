<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Materi;
use App\Models\Player;
use App\Models\Soal;
use App\Models\SnakeLadder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{
    public function setupForm()
    {
        $materis = Materi::all();
        return view('setup', compact('materis'));
    }

    public function setupStore(Request $request)
    {
        $request->validate([
            'materi_id' => 'required|exists:materis,id',
            'jumlah_pemain' => 'required|integer|min:1|max:4'
        ]);

        $game = Game::create([
            'materi_id' => $request->materi_id,
            'jumlah_pemain' => $request->jumlah_pemain,
            'status' => 'waiting'
        ]);

        return redirect()->route('game.players.create', $game);
    }

    public function createPlayers(Game $game)
    {
        if ($game->status != 'waiting') {
            return redirect()->route('game.show', $game);
        }
        return view('players.create', compact('game'));
    }

    public function storePlayers(Request $request, Game $game)
    {
        $request->validate([
            'nama.*' => 'required|string|max:255'
        ]);

        foreach ($request->nama as $index => $nama) {
            Player::create([
                'game_id' => $game->id,
                'nama' => $nama,
                'posisi' => 1,
                'urutan' => $index + 1
            ]);
        }

        $game->update(['status' => 'playing']);

        return redirect()->route('game.show', $game);
    }

    public function show(Game $game)
    {
        if ($game->status == 'waiting') {
            return redirect()->route('game.players.create', $game);
        }

        $game->load('players');
        $snakesLadders = SnakeLadder::all();

        return view('game', compact('game', 'snakesLadders'));
    }

    public function nextQuestion(Game $game)
    {
        // Ambil soal acak dari materi game
        $soal = Soal::where('materi_id', $game->materi_id)->inRandomOrder()->first();

        if (!$soal) {
            return response()->json(['error' => 'Tidak ada soal untuk materi ini'], 404);
        }

        // Simpan soal ke session (gunakan prefix game id)
        Session::put('game_' . $game->id . '_current_question', $soal);

        return response()->json([
            'question' => $soal->pertanyaan,
        ]);
    }

    public function rollDice(Game $game, Player $player)
    {
        // Pastikan player milik game ini
        if ($player->game_id != $game->id) {
            abort(403);
        }

        $dice = rand(1, 6);
        $oldPos = $player->posisi;
        $newPos = $oldPos + $dice;

        // Cek apakah melebihi 100
        if ($newPos > 100) {
            $newPos = 100; // Jika lebih, set ke 100 (menang)
        } else {
            // Cek efek ular/tangga
            $snakeLadder = SnakeLadder::where('start', $newPos)->first();
            if ($snakeLadder) {
                $newPos = $snakeLadder->end;
            }
        }

        // Update posisi
        $player->update(['posisi' => $newPos]);

        // Cek apakah ada yang mencapai 100
        $winner = null;
        $gameFinished = false;
        
        if ($newPos >= 100) {
            $winner = $player->nama;
            $gameFinished = true;
            $game->update(['status' => 'finished']);
        }

        // Ambil semua pemain untuk peringkat
        $allPlayers = $game->players()->orderBy('posisi', 'desc')->get();
        $ranking = [];
        foreach ($allPlayers as $p) {
            $ranking[] = [
                'nama' => $p->nama,
                'posisi' => $p->posisi
            ];
        }

        return response()->json([
            'dice' => $dice,
            'oldPos' => $oldPos,
            'newPos' => $newPos,
            'winner' => $winner,
            'gameFinished' => $gameFinished,
            'playerName' => $player->nama,
            'effect' => $snakeLadder ? $snakeLadder->type : null,
            'effectEnd' => $snakeLadder ? $snakeLadder->end : null,
            'ranking' => $ranking // Kirim data peringkat
        ]);
    }

    // Tambahkan method ini di GameController.php
    public function getRanking(Game $game)
    {
        $players = $game->players()->orderBy('posisi', 'desc')->get();
        
        $ranking = [];
        foreach ($players as $index => $player) {
            $ranking[] = [
                'rank' => $index + 1,
                'nama' => $player->nama,
                'posisi' => $player->posisi,
                'is_winner' => $player->posisi >= 100
            ];
        }
        
        return response()->json($ranking);
    }

}