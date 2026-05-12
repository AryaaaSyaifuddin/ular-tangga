@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6" style="width: 80%;">
            <div class="card overflow-hidden">
                <!-- Header -->
                <div class="card-header crimson-header">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-dice-d20 fa-2x" style="opacity:.9;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold" style="font-family:var(--font-display); letter-spacing:1px;">
                                Setup Permainan Ular Tangga
                            </h3>
                            <p class="mb-0 small mt-1" style="opacity:.8;">Atur preferensi permainan Anda</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5" style="background: var(--crimson-subtle);">
                    <form method="POST" action="{{ route('game.setup') }}" id="setupForm">
                        @csrf

                        <!-- Progress Steps -->
                        <div class="setup-progress mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="step active">
                                    <div class="step-circle">1</div>
                                    <span class="step-label">Setup</span>
                                </div>
                                <div class="step-line active"></div>
                                <div class="step">
                                    <div class="step-circle">2</div>
                                    <span class="step-label">Pemain</span>
                                </div>
                                <div class="step-line"></div>
                                <div class="step">
                                    <div class="step-circle">3</div>
                                    <span class="step-label">Main</span>
                                </div>
                            </div>
                        </div>

                        <!-- Materi Selection -->
                        <div class="mb-4">
                            <label for="materi_id" class="form-label fw-semibold" style="color:var(--gray-700);">
                                <i class="fas fa-book-open me-2 text-crimson"></i>Pilih Materi
                            </label>
                            <div class="position-relative">
                                <select class="form-select form-select-lg @error('materi_id') is-invalid @enderror"
                                        id="materi_id" name="materi_id" required
                                        style="border-left: 4px solid var(--crimson); background-color: white;">
                                    <option value="" disabled selected>-- Pilih Materi Pembelajaran --</option>
                                    @foreach($materis as $materi)
                                        <option value="{{ $materi->id }}" {{ old('materi_id') == $materi->id ? 'selected' : '' }}>
                                            📚 {{ $materi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('materi_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Jumlah Pemain -->
                        <div class="mb-4">
                            <label for="jumlah_pemain" class="form-label fw-semibold" style="color:var(--gray-700);">
                                <i class="fas fa-users me-2 text-crimson"></i>Jumlah Pemain
                            </label>
                            <div class="player-counter-wrapper">
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-crimson rounded-start-3" id="decrementPlayer">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number"
                                           class="form-control form-control-lg text-center border-crimson @error('jumlah_pemain') is-invalid @enderror"
                                           id="jumlah_pemain" name="jumlah_pemain"
                                           min="1" max="4" value="{{ old('jumlah_pemain', 2) }}"
                                           required readonly
                                           style="background:white; font-size:1.5rem; font-weight:700; border-left:none; border-right:none;">
                                    <button type="button" class="btn btn-outline-crimson rounded-end-3" id="incrementPlayer">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @error('jumlah_pemain')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <div class="player-preview mt-3 d-flex justify-content-center gap-3" id="playerPreview"></div>
                        </div>

                        <!-- Game Rules Info -->
                        <div class="alert-crimson-info p-3 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-2x text-crimson me-3 flex-shrink-0 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-2 text-crimson">Aturan Permainan:</h6>
                                    <ul class="small mb-0 ps-3" style="color: var(--gray-700);">
                                        <li>Setiap pemain akan menjawab soal secara bergiliran</li>
                                        <li>Guru yang menentukan pemain yang berhak melempar dadu</li>
                                        <li>Pemain pertama mencapai petak 100 adalah pemenangnya</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-crimson w-100 py-3 rounded-3 fw-bold" id="submitBtn">
                            <span class="btn-text">
                                <i class="fas fa-play-circle me-2"></i>Mulai Permainan
                            </span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2"></span>Memuat...
                            </span>
                        </button>

                        <p class="text-center small mt-3 mb-0" style="color:var(--gray-500);">
                            <i class="fas fa-shield-alt me-1"></i>Data aman &amp; tidak disimpan
                        </p>
                    </form>
                </div>
            </div>

            <!-- Decorative Dice -->
            <div class="text-center mt-4">
                <div class="dice-decoration">
                    <i class="fas fa-dice-one fa-2x me-2"></i>
                    <i class="fas fa-dice-two fa-2x me-2"></i>
                    <i class="fas fa-dice-three fa-2x me-2"></i>
                    <i class="fas fa-dice-four fa-2x me-2"></i>
                    <i class="fas fa-dice-five fa-2x me-2"></i>
                    <i class="fas fa-dice-six fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Setup-specific styles */
    .player-counter-wrapper .input-group { box-shadow: var(--shadow-sm); }
    .player-counter-wrapper .btn { width: 50px; background: white; transition: all 0.3s ease; }
    .player-counter-wrapper .btn:focus { box-shadow: none; }

    .player-preview .player-icon {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.1rem; animation: popIn 0.3s ease;
    }
    /* Maroon & white alternating tokens */
    .player-preview .player-icon.token-maroon {
        background: linear-gradient(135deg, var(--crimson-dark), var(--crimson));
        color: white; border: 2px solid var(--crimson-light);
        box-shadow: 0 4px 12px rgba(185,28,28,0.35);
    }
    .player-preview .player-icon.token-white {
        background: white;
        color: var(--crimson-dark); border: 2px solid var(--crimson);
        box-shadow: 0 4px 12px rgba(185,28,28,0.2);
    }

    @keyframes popIn {
        0%   { transform: scale(0); opacity: 0; }
        80%  { transform: scale(1.15); }
        100% { transform: scale(1); opacity: 1; }
    }

    .form-select-lg { background-image: none; appearance: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahInput  = document.getElementById('jumlah_pemain');
    const decrementBtn = document.getElementById('decrementPlayer');
    const incrementBtn = document.getElementById('incrementPlayer');
    const playerPreview = document.getElementById('playerPreview');
    const form = document.getElementById('setupForm');
    const submitBtn = document.getElementById('submitBtn');

    function updatePlayerPreview(count) {
        playerPreview.innerHTML = '';
        for (let i = 0; i < count; i++) {
            const icon = document.createElement('div');
            icon.className = 'player-icon ' + (i % 2 === 0 ? 'token-maroon' : 'token-white');
            icon.textContent = i + 1;
            icon.title = `Pemain ${i + 1}`;
            playerPreview.appendChild(icon);
        }
    }

    updatePlayerPreview(jumlahInput.value);

    decrementBtn.addEventListener('click', function() {
        let v = parseInt(jumlahInput.value);
        if (v > 1) { jumlahInput.value = v - 1; updatePlayerPreview(jumlahInput.value); }
    });
    incrementBtn.addEventListener('click', function() {
        let v = parseInt(jumlahInput.value);
        if (v < 4) { jumlahInput.value = v + 1; updatePlayerPreview(jumlahInput.value); }
    });

    form.addEventListener('submit', function() {
        submitBtn.querySelector('.btn-text').classList.add('d-none');
        submitBtn.querySelector('.btn-loading').classList.remove('d-none');
        submitBtn.disabled = true;
    });

    @if($errors->any())
        document.querySelector('.card').scrollIntoView({ behavior: 'smooth', block: 'center' });
    @endif
});
</script>
@endsection
