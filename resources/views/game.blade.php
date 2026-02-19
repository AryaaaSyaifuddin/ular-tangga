@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h3>Papan Ular Tangga</h3>
        <div id="board" style="display: grid; grid-template-columns: repeat(10, 50px); gap: 2px; margin-bottom: 20px;">
            @php
                $cells = [];
                for ($i = 10; $i >= 1; $i--) {
                    if ($i % 2 == 0) {
                        // baris genap: dari kiri ke kanan
                        for ($j = 1; $j <= 10; $j++) {
                            $cells[] = ($i-1)*10 + $j;
                        }
                    } else {
                        // baris ganjil: dari kanan ke kiri
                        for ($j = 10; $j >= 1; $j--) {
                            $cells[] = ($i-1)*10 + $j;
                        }
                    }
                }
            @endphp

            @foreach($cells as $cell)
                <div class="grid-item" data-pos="{{ $cell }}" id="cell-{{ $cell }}">
                    {{ $cell }}
                    @foreach($game->players as $player)
                        @if($player->posisi == $cell)
                            <div class="player-token" style="background-color: {{ '#' . substr(md5($player->id), 0, 6) }};" title="{{ $player->nama }}"></div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>

        <div id="question-area" class="p-3 border bg-light mb-3">
            <h5>Soal:</h5>
            <p id="question-text">-</p>
            <div id="timer" class="fw-bold">00</div>
        </div>

        <button id="next-question" class="btn btn-primary">Soal Selanjutnya</button>
        <button id="roll-dice" class="btn btn-success" style="display: none;" disabled>Lempar Dadu</button>
    </div>

    <div class="col-md-4">
        <h4>Daftar Pemain</h4>
        <ul class="list-group" id="player-list">
            @foreach($game->players as $player)
                <li class="list-group-item d-flex justify-content-between align-items-center" data-player-id="{{ $player->id }}" data-player-name="{{ $player->nama }}">
                    {{ $player->nama }}
                    <span class="badge bg-primary rounded-pill pos-badge" id="pos-{{ $player->id }}">{{ $player->posisi }}</span>
                    <button class="btn btn-sm btn-warning select-player-btn" style="display: none;">Pilih</button>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<!-- Modal Podium -->
<div class="modal fade" id="podiumModal" tabindex="-1" aria-labelledby="podiumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="podiumModalLabel">🏆 Permainan Selesai! 🏆</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row text-center mb-4">
                    <div class="col-md-4">
                        <div class="podium-place bg-warning p-3 rounded">
                            <h2>🥈</h2>
                            <h4 id="rank2-name">-</h4>
                            <p>Petak <span id="rank2-pos">0</span></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="podium-place bg-warning p-3 rounded" style="background-color: gold;">
                            <h2>🥇</h2>
                            <h4 id="rank1-name">-</h4>
                            <p>Petak <span id="rank1-pos">0</span></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="podium-place bg-warning p-3 rounded">
                            <h2>🥉</h2>
                            <h4 id="rank3-name">-</h4>
                            <p>Petak <span id="rank3-pos">0</span></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Nama Pemain</th>
                                    <th>Posisi</th>
                                </tr>
                            </thead>
                            <tbody id="ranking-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('setup') }}'">Main Lagi</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let gameId = {{ $game->id }};
    let currentQuestion = null;
    let timerInterval = null;
    let countdown = 15;
    let selectedPlayerId = null;
    let isRollingEnabled = false;
    let hasRolledDice = true; // Flag untuk cek apakah sudah lempar dadu setelah soal
    let gameFinished = {{ $game->status === 'finished' ? 'true' : 'false' }};

    const questionText = document.getElementById('question-text');
    const timerDiv = document.getElementById('timer');
    const nextQuestionBtn = document.getElementById('next-question');
    const rollDiceBtn = document.getElementById('roll-dice');
    const playerListItems = document.querySelectorAll('#player-list li');

    // Jika game sudah selesai, tampilkan podium
    if (gameFinished) {
        showPodium();
    }

    // Fungsi untuk menampilkan podium
    function showPodium() {
        fetch(`/game/${gameId}/ranking`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            // Isi podium
            if (data.length >= 1) {
                document.getElementById('rank1-name').innerText = data[0].nama;
                document.getElementById('rank1-pos').innerText = data[0].posisi;
            }
            if (data.length >= 2) {
                document.getElementById('rank2-name').innerText = data[1].nama;
                document.getElementById('rank2-pos').innerText = data[1].posisi;
            }
            if (data.length >= 3) {
                document.getElementById('rank3-name').innerText = data[2].nama;
                document.getElementById('rank3-pos').innerText = data[2].posisi;
            }
            
            // Isi tabel
            let tbody = document.getElementById('ranking-table-body');
            tbody.innerHTML = '';
            data.forEach((player, index) => {
                tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${player.nama}</td>
                        <td>${player.posisi}</td>
                    </tr>
                `;
            });
            
            // Tampilkan modal
            var myModal = new bootstrap.Modal(document.getElementById('podiumModal'));
            myModal.show();
        });
    }

    // Fungsi untuk memperbarui tampilan pion (dipanggil setelah roll)
    function updatePlayerPosition(playerId, newPos) {
        // Hapus semua token pemain tersebut dari sel lama
        document.querySelectorAll(`.player-token[data-player-id="${playerId}"]`).forEach(el => el.remove());
        // Tambahkan token baru di sel baru
        let cell = document.getElementById(`cell-${newPos}`);
        if (cell) {
            let token = document.createElement('div');
            token.className = 'player-token';
            let hue = (playerId * 50) % 360;
            token.style.backgroundColor = `hsl(${hue}, 70%, 60%)`;
            token.setAttribute('data-player-id', playerId);
            token.title = document.querySelector(`#player-list li[data-player-id="${playerId}"]`).getAttribute('data-player-name');
            cell.appendChild(token);
        }
        // Update posisi di daftar pemain
        document.getElementById(`pos-${playerId}`).innerText = newPos;
    }

    // Inisialisasi warna pion
    document.querySelectorAll('#player-list li').forEach((li, index) => {
        let playerId = li.getAttribute('data-player-id');
        let hue = (playerId * 50) % 360;
        let token = document.querySelector(`#cell-${document.getElementById(`pos-${playerId}`).innerText} .player-token[title="${li.getAttribute('data-player-name')}"]`);
        if (token) {
            token.style.backgroundColor = `hsl(${hue}, 70%, 60%)`;
            token.setAttribute('data-player-id', playerId);
        }
    });

    // Fungsi countdown
    function startTimer(seconds) {
        clearInterval(timerInterval);
        countdown = seconds;
        timerDiv.innerText = countdown.toString().padStart(2, '0');
        timerInterval = setInterval(() => {
            countdown--;
            timerDiv.innerText = countdown.toString().padStart(2, '0');
            if (countdown <= 0) {
                clearInterval(timerInterval);
                // Setelah 15 detik, tampilkan tombol pilih untuk semua pemain
                document.querySelectorAll('.select-player-btn').forEach(btn => btn.style.display = 'inline-block');
            }
        }, 1000);
    }

    // Event: Klik Soal Selanjutnya
    nextQuestionBtn.addEventListener('click', function() {
        // Validasi: tidak bisa klik next kalau belum lempar dadu setelah soal sebelumnya
        if (!hasRolledDice) {
            alert('Harap lempar dadu terlebih dahulu!');
            return;
        }
        
        // Cek apakah game sudah selesai
        if (gameFinished) {
            alert('Permainan sudah selesai. Silakan mulai permainan baru.');
            return;
        }
        
        fetch(`/game/${gameId}/next-question`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            currentQuestion = data.question;
            questionText.innerText = currentQuestion;
            startTimer(15);
            
            // Sembunyikan tombol roll, aktifkan pemilihan pemain
            rollDiceBtn.style.display = 'none';
            rollDiceBtn.disabled = true;
            selectedPlayerId = null;
            
            // Tampilkan tombol pilih di setiap pemain
            document.querySelectorAll('.select-player-btn').forEach(btn => btn.style.display = 'inline-block');
            
            // Nonaktifkan next question selama countdown dan sampai roll dadu
            nextQuestionBtn.disabled = true;
            hasRolledDice = false; // Belum lempar dadu
        });
    });

    // Event: Klik tombol Pilih pada pemain
    playerListItems.forEach(li => {
        let selectBtn = li.querySelector('.select-player-btn');
        selectBtn.addEventListener('click', function() {
            let playerId = li.getAttribute('data-player-id');
            selectedPlayerId = playerId;
            
            // Sembunyikan semua tombol pilih
            document.querySelectorAll('.select-player-btn').forEach(btn => btn.style.display = 'none');
            
            // Tampilkan tombol roll
            rollDiceBtn.style.display = 'inline-block';
            rollDiceBtn.disabled = false;
            
            // Hentikan timer
            clearInterval(timerInterval);
            timerDiv.innerText = '00';
            
            // Next question tetap nonaktif sampai roll selesai
            nextQuestionBtn.disabled = true;
        });
    });

    // Event: Lempar Dadu
    rollDiceBtn.addEventListener('click', function() {
        if (!selectedPlayerId) return;
        
        fetch(`/game/${gameId}/roll-dice/${selectedPlayerId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            // Update posisi pemain
            updatePlayerPosition(selectedPlayerId, data.newPos);

            // Tampilkan info dadu dengan alert yang lebih informatif
            let message = `${data.playerName} mendapat dadu ${data.dice}. Pindah dari ${data.oldPos} ke ${data.newPos}.`;
            
            if (data.effect) {
                message += `\n${data.playerName} terkena ${data.effect === 'snake' ? 'ular' : 'tangga'}! Turun/Naik ke ${data.effectEnd}.`;
            }
            
            alert(message);

            // Jika ada pemenang atau game selesai
            if (data.gameFinished) {
                gameFinished = true;
                alert(`🎉 Selamat! ${data.winner} menang! 🎉`);
                
                // Nonaktifkan semua tombol
                nextQuestionBtn.disabled = true;
                rollDiceBtn.disabled = true;
                document.querySelectorAll('.select-player-btn').forEach(btn => btn.disabled = true);
                
                // Tampilkan podium
                showPodium();
            }

            // Reset area soal
            questionText.innerText = '-';
            timerDiv.innerText = '00';
            rollDiceBtn.style.display = 'none';
            rollDiceBtn.disabled = true;
            selectedPlayerId = null;
            
            // Aktifkan next question karena sudah lempar dadu
            nextQuestionBtn.disabled = false;
            hasRolledDice = true; // Sudah lempar dadu
        });
    });
    
    // Jika game sudah selesai dari awal, nonaktifkan tombol
    if (gameFinished) {
        nextQuestionBtn.disabled = true;
        rollDiceBtn.disabled = true;
        document.querySelectorAll('.select-player-btn').forEach(btn => btn.disabled = true);
    }
</script>
@endpush