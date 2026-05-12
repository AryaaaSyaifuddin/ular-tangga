@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <div class="card setup-card overflow-hidden">
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

                <div class="card-body setup-body p-4 p-lg-5">
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
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-semibold mb-0" style="color:var(--gray-700);">
                                    <i class="fas fa-book-open me-2 text-crimson"></i>Pilih Materi
                                </label>
                                <button type="button" class="btn btn-sm btn-outline-crimson" id="selectAllMateriBtnToggle"
                                        style="padding: 0.25rem 0.75rem; font-size: 0.875rem;">
                                    <i class="fas fa-check-square me-1"></i>Pilih Semua
                                </button>
                            </div>
                            <div class="materi-checkboxes-container p-3 border rounded-3" 
                                 style="background: rgba(255,255,255,0.5); border-color: rgba(185,28,28,0.12) !important;">
                                @foreach($materis as $materi)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input materi-checkbox" type="checkbox"
                                               id="materi_{{ $materi->id }}" name="materi_id[]"
                                               value="{{ $materi->id }}"
                                               {{ in_array($materi->id, (array) old('materi_id', [])) ? 'checked' : '' }}
                                               style="width: 1.25rem; height: 1.25rem; cursor: pointer; accent-color: var(--crimson);">
                                        <label class="form-check-label" for="materi_{{ $materi->id }}"
                                               style="cursor: pointer; color: var(--gray-700); margin-bottom: 0;">
                                                {{ $materi->nama }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @if($errors->has('materi_id'))
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first('materi_id') }}
                                </div>
                            @endif
                            <div id="materiErrorMsg" class="invalid-feedback d-none mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>Pilih minimal satu materi
                            </div>
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

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Setup-specific styles */
    .setup-card {
        border: 1px solid rgba(185,28,28,0.12);
        border-radius: 24px;
        box-shadow: var(--shadow-lg);
    }

    .setup-body {
        background:
            linear-gradient(180deg, rgba(255,255,255,0.92), rgba(253,246,246,0.96)),
            var(--crimson-subtle);
    }

    #materi_id,
    #jumlah_pemain {
        border-color: rgba(185,28,28,0.18);
        box-shadow: none;
    }

    /* Materi Checkboxes Styling */
    .materi-checkboxes-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .materi-checkboxes-container .form-check {
        padding: 12px 16px;
        border-radius: 12px;
        background: white;
        border: 1px solid rgba(185,28,28,0.08);
        transition: all 0.2s ease;
        margin-bottom: 0;
    }

    .materi-checkboxes-container .form-check:hover {
        background: rgba(185,28,28,0.02);
        border-color: rgba(185,28,28,0.15);
        box-shadow: 0 2px 8px rgba(185,28,28,0.08);
    }

    .materi-checkboxes-container .form-check-input {
        border-color: rgba(185,28,28,0.25);
        border-radius: 4px;
    }

    .materi-checkboxes-container .form-check-input:checked {
        background-color: var(--crimson);
        border-color: var(--crimson);
        box-shadow: none;
    }

    .materi-checkboxes-container .form-check-input:focus {
        border-color: var(--crimson);
        box-shadow: 0 0 0 0.2rem rgba(185,28,28,0.25);
    }

    .materi-checkboxes-container .form-check-label {
        font-weight: 500;
        color: var(--gray-700);
    }

    #materi_id {
        min-height: 52px;
        border-left: 4px solid var(--crimson);
        border-radius: 12px;
    }

    .player-counter-wrapper .input-group {
        box-shadow: var(--shadow-sm);
        border-radius: 14px;
        overflow: hidden;
    }
    .player-counter-wrapper .btn { width: 54px; background: white; transition: all 0.2s ease; }
    .player-counter-wrapper .btn:focus { box-shadow: none; }
    .player-counter-wrapper .btn:hover { background: var(--crimson-soft); }

    .player-preview .player-icon {
        width: 42px; height: 42px; border-radius: 50%;
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

    .alert-crimson-info {
        border-radius: 16px;
        border: 1px solid rgba(185,28,28,0.12);
        background: rgba(255,255,255,0.72);
    }

    @media (max-width: 576px) {
        .setup-progress .step-label { font-size: 0.72rem; }
    }
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

    // Materi checkboxes - Select All functionality
    const selectAllBtn = document.getElementById('selectAllMateriBtnToggle');
    const materiCheckboxes = document.querySelectorAll('.materi-checkbox');
    const materiErrorMsg = document.getElementById('materiErrorMsg');

    function updateSelectAllBtn() {
        const checkedCount = document.querySelectorAll('.materi-checkbox:checked').length;
        const totalCount = materiCheckboxes.length;
        
        if (checkedCount === totalCount && totalCount > 0) {
            selectAllBtn.innerHTML = '<i class="fas fa-times-square me-1"></i>Batalkan Semua';
            selectAllBtn.classList.remove('btn-outline-crimson');
            selectAllBtn.classList.add('btn-crimson', 'text-white');
        } else {
            selectAllBtn.innerHTML = '<i class="fas fa-check-square me-1"></i>Pilih Semua';
            selectAllBtn.classList.remove('btn-crimson', 'text-white');
            selectAllBtn.classList.add('btn-outline-crimson');
        }
    }

    selectAllBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const checkedCount = document.querySelectorAll('.materi-checkbox:checked').length;
        const totalCount = materiCheckboxes.length;
        const shouldCheckAll = checkedCount < totalCount;

        materiCheckboxes.forEach(checkbox => {
            checkbox.checked = shouldCheckAll;
        });
        
        updateSelectAllBtn();
        materiErrorMsg.classList.add('d-none');
    });

    materiCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllBtn();
            const checkedAny = document.querySelector('.materi-checkbox:checked');
            if (checkedAny) {
                materiErrorMsg.classList.add('d-none');
            }
        });
    });

    // Form validation untuk materi selection
    form.addEventListener('submit', function(e) {
        const checkedMateri = document.querySelectorAll('.materi-checkbox:checked').length;
        if (checkedMateri === 0) {
            e.preventDefault();
            materiErrorMsg.classList.remove('d-none');
            document.querySelector('.materi-checkboxes-container').scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }
        
        submitBtn.querySelector('.btn-text').classList.add('d-none');
        submitBtn.querySelector('.btn-loading').classList.remove('d-none');
        submitBtn.disabled = true;
    });

    updateSelectAllBtn();

    @if($errors->any())
        document.querySelector('.card').scrollIntoView({ behavior: 'smooth', block: 'center' });
    @endif
});
</script>
@endsection
