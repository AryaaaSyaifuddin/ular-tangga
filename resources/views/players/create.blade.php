@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card player-setup-card overflow-hidden">
                <!-- Header -->
                <div class="card-header crimson-header">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-user-plus fa-2x" style="opacity:.9;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold" style="font-family:var(--font-display); letter-spacing:1px;">
                                Input Nama Pemain
                            </h3>
                            <p class="mb-0 small mt-1" style="opacity:.8;">Masukkan nama-nama pemain yang akan bermain</p>
                        </div>
                    </div>
                </div>

                <div class="card-body player-setup-body p-4 p-lg-5">
                    <!-- Progress Steps -->
                    <div class="setup-progress mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="step completed">
                                <div class="step-circle"><i class="fas fa-check"></i></div>
                                <span class="step-label">Setup</span>
                            </div>
                            <div class="step-line active"></div>
                            <div class="step active">
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

                    <!-- Game Info -->
                    <div class="alert-crimson-info p-3 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-dice-d6 fa-2x text-crimson me-3 flex-shrink-0"></i>
                            <div>
                                <h6 class="fw-bold text-crimson mb-1">Detail Permainan</h6>
                                <p class="mb-0 small" style="color:var(--gray-700);">
                                    <i class="fas fa-book-open me-1 text-crimson"></i>Materi:
                                    <span class="fw-semibold">
                                        @php
                                            $materiIds = is_array($game->materi_id) ? $game->materi_id : [$game->materi_id];
                                            $materis = \App\Models\Materi::whereIn('id', $materiIds)->pluck('nama')->toArray();
                                        @endphp
                                        {{ implode(', ', $materis) }}
                                    </span><br>
                                    <i class="fas fa-users me-1 text-crimson"></i>Jumlah Pemain:
                                    <span class="fw-semibold">{{ $game->jumlah_pemain }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('game.players.store', $game) }}" id="playerForm">
                        @csrf
                        @php
                            $tokenOptions = [
                                'style_1' => 'Style 1', 'style_2' => 'Style 2',
                                'style_3' => 'Style 3', 'style_4' => 'Style 4',
                                'style_5' => 'Style 5', 'style_6' => 'Style 6',
                            ];
                        @endphp

                        <div class="players-container mb-4">
                            @for($i = 1; $i <= $game->jumlah_pemain; $i++)
                                <div class="player-input-card" id="player-card-{{ $i }}">
                                    <div class="player-card-layout">
                                        <!-- Number circle — maroon for odd, white for even -->
                                        <div class="player-number">
                                            <div class="number-circle {{ $i % 2 === 1 ? 'token-maroon' : 'token-white' }}">
                                                {{ $i }}
                                            </div>
                                        </div>
                                        <div class="player-form-fields">
                                            <div class="player-name-field">
                                                <label for="nama{{ $i }}" class="form-label fw-semibold mb-1">
                                                    Nama Pemain {{ $i }}
                                                </label>
                                                <div class="input-group player-name-input">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user text-crimson"></i>
                                                    </span>
                                                    <input type="text"
                                                           class="form-control @error('nama.' . ($i-1)) is-invalid @enderror"
                                                           id="nama{{ $i }}" name="nama[]"
                                                           placeholder="Contoh: Andi"
                                                           value="{{ old('nama.' . ($i-1)) }}"
                                                           required autocomplete="off">
                                                </div>
                                                @error('nama.' . ($i-1))
                                                    <div class="invalid-feedback d-block">
                                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="player-token-field">
                                                <label class="form-label fw-semibold mb-1">
                                                    Pilih Bidak
                                                </label>
                                                @php
                                                    $selectedToken = old('token_style.' . ($i-1), array_keys($tokenOptions)[$i - 1] ?? 'style_1');
                                                    $selectedToken = array_key_exists($selectedToken, $tokenOptions) ? $selectedToken : 'style_1';
                                                @endphp
                                                <div class="token-current-preview" id="token-preview-wrapper-{{ $i }}">
                                                    <img id="token-preview-{{ $i }}"
                                                         src="{{ asset('images/tokens/' . $selectedToken . '.png') }}"
                                                         alt="Preview Bidak Pemain {{ $i }}"
                                                         class="token-preview-image-lg">
                                                    <span class="small ms-2" id="token-preview-label-{{ $i }}">{{ $tokenOptions[$selectedToken] ?? 'Style 1' }}</span>
                                                </div>
                                                <div class="token-options-grid @error('token_style.' . ($i-1)) is-invalid @enderror">
                                                    @foreach($tokenOptions as $value => $label)
                                                        <label class="token-option-item">
                                                            <input type="radio"
                                                                   name="token_style[{{ $i-1 }}]"
                                                                   value="{{ $value }}"
                                                                   data-player-index="{{ $i }}"
                                                                   data-token-label="{{ $label }}"
                                                                   {{ $selectedToken === $value ? 'checked' : '' }}
                                                                   required>
                                                            <img src="{{ asset('images/tokens/' . $value . '.png') }}" alt="{{ $label }}">
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @error('token_style.' . ($i-1))
                                                    <div class="invalid-feedback d-block">
                                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <!-- Tips -->
                        <div class="alert-crimson-info p-3 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-lightbulb fa-2x text-crimson me-3 flex-shrink-0 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-crimson">Tips:</h6>
                                    <p class="small mb-0" style="color:var(--gray-700);">
                                        Gunakan nama panggilan yang mudah diingat. Nama akan ditampilkan di papan permainan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('setup') }}" class="btn btn-outline-crimson w-50 py-3 rounded-3">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-crimson w-50 py-3 rounded-3" id="submitBtn">
                                <span class="btn-text">
                                    <i class="fas fa-play-circle me-2"></i>Mulai Permainan
                                </span>
                                <span class="btn-loading d-none">
                                    <span class="spinner-border spinner-border-sm me-2"></span>Memuat...
                                </span>
                            </button>
                        </div>
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
    .player-setup-card {
        border: 1px solid rgba(185,28,28,0.12);
        border-radius: 24px;
        box-shadow: var(--shadow-lg);
    }

    .player-setup-body {
        background:
            linear-gradient(180deg, rgba(255,255,255,0.9), rgba(253,246,246,0.95)),
            var(--crimson-subtle);
    }

    .players-container {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    /* Player number circle variants */
    .number-circle {
        width: 44px; height: 44px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.1rem;
    }
    .number-circle.token-maroon {
        background: linear-gradient(135deg, var(--crimson-dark), var(--crimson));
        color: white; box-shadow: 0 4px 10px rgba(185,28,28,0.35);
        border: 2px solid rgba(255,255,255,0.3);
    }
    .number-circle.token-white {
        background: white; color: var(--crimson-dark);
        border: 2px solid var(--crimson);
        box-shadow: 0 4px 10px rgba(185,28,28,0.2);
    }

    .player-input-card {
        background: white; padding: 18px; border-radius: 16px;
        box-shadow: var(--shadow-sm);
        border: 1px solid rgba(185,28,28,0.12);
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }
    .player-input-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: rgba(185,28,28,0.28);
    }

    .player-card-layout {
        display: grid;
        grid-template-columns: 44px minmax(0, 1fr);
        gap: 14px;
        align-items: start;
    }

    .player-form-fields {
        display: grid;
        gap: 14px;
    }

    .player-name-field .form-label,
    .player-token-field .form-label {
        color: var(--gray-700);
    }

    .player-name-input .input-group-text {
        background: #FFFFFF;
        border-color: var(--gray-200);
        border-radius: 10px 0 0 10px;
    }

    .player-name-input .form-control {
        min-height: 44px;
        border-left: none;
        border-radius: 0 10px 10px 0;
    }

    .token-current-preview {
        display: flex; align-items: center; padding: 8px 10px;
        border: 1.5px solid var(--crimson-soft); border-radius: 10px;
        background: var(--crimson-subtle);
        margin-bottom: 10px;
    }

    .token-current-preview span {
        color: var(--gray-600);
        font-weight: 600;
    }

    .token-preview-image-lg {
        width: 38px; height: 38px; object-fit: contain;
        border-radius: 8px; border: 1px solid var(--gray-200);
        background: white; padding: 2px;
    }

    .token-options-grid {
        display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 8px;
    }
    .token-option-item {
        border: 1.5px solid var(--crimson-soft); border-radius: 10px;
        padding: 7px 4px; text-align: center; cursor: pointer;
        background: white; transition: all 0.2s ease; margin: 0;
    }
    .token-option-item:hover {
        border-color: var(--crimson); transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(185,28,28,0.18);
    }
    .token-option-item input { display: none; }
    .token-option-item img { width: 36px; height: 36px; object-fit: contain; border-radius: 6px; }
    .token-option-item:has(input:checked) {
        border-color: var(--crimson); background: var(--crimson-soft);
        box-shadow: inset 0 0 0 1px rgba(185,28,28,0.12);
    }

    @media (max-width: 992px) {
        .players-container { grid-template-columns: 1fr; }
    }

    @media (max-width: 576px) {
        .player-card-layout { grid-template-columns: 1fr; }
        .player-number { display: flex; }
        .token-options-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('playerForm');
    const submitBtn = document.getElementById('submitBtn');

    document.getElementById('nama1')?.focus();

    form.addEventListener('submit', function() {
        submitBtn.querySelector('.btn-text').classList.add('d-none');
        submitBtn.querySelector('.btn-loading').classList.remove('d-none');
        submitBtn.disabled = true;
    });

    document.querySelectorAll('.player-input-card').forEach(card => {
        const input = card.querySelector('input[type="text"]');
        if (!input) return;
        input.addEventListener('focus', () => {
            card.style.transform = 'translateY(-2px)';
            card.style.borderColor = 'var(--crimson)';
            card.style.boxShadow = '0 6px 20px rgba(185,28,28,0.18)';
        });
        input.addEventListener('blur', () => {
            if (!input.value) {
                card.style.transform = 'translateX(0)';
                card.style.borderColor = 'rgba(185,28,28,0.08)';
                card.style.boxShadow = 'var(--shadow-sm)';
            }
        });
        if (input.value) {
            card.style.borderColor = 'var(--crimson)';
        }
    });

    document.querySelectorAll('.token-option-item input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const idx = this.dataset.playerIndex;
            const preview = document.getElementById(`token-preview-${idx}`);
            const label   = document.getElementById(`token-preview-label-${idx}`);
            const img     = this.parentElement.querySelector('img');
            if (preview && img) preview.src = img.src;
            if (label) label.textContent = this.dataset.tokenLabel || this.value;
        });
    });
});
</script>
@endsection
