@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-10">
            <div class="card overflow-hidden">
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

                <div class="card-body p-4 p-lg-5" style="background: var(--crimson-subtle);">
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
                                    <span class="fw-semibold">{{ $game->materi->nama }}</span><br>
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
                                'style_7' => 'Style 7', 'style_8' => 'Style 8',
                            ];
                        @endphp

                        <div class="players-container mb-4">
                            @for($i = 1; $i <= $game->jumlah_pemain; $i++)
                                <div class="player-input-card mb-3" id="player-card-{{ $i }}">
                                    <div class="d-flex align-items-center">
                                        <!-- Number circle — maroon for odd, white for even -->
                                        <div class="player-number me-3">
                                            <div class="number-circle {{ $i % 2 === 1 ? 'token-maroon' : 'token-white' }}">
                                                {{ $i }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <label for="nama{{ $i }}" class="form-label fw-semibold mb-1" style="color:var(--gray-700);">
                                                Nama Pemain {{ $i }}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0" style="border-radius:10px 0 0 10px; border-color:var(--gray-200);">
                                                    <i class="fas fa-user text-crimson"></i>
                                                </span>
                                                <input type="text"
                                                       class="form-control @error('nama.' . ($i-1)) is-invalid @enderror"
                                                       id="nama{{ $i }}" name="nama[]"
                                                       placeholder="Contoh: Andi"
                                                       value="{{ old('nama.' . ($i-1)) }}"
                                                       required autocomplete="off"
                                                       style="border-radius:0 10px 10px 0; border-left:none;">
                                            </div>
                                            @error('nama.' . ($i-1))
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror

                                            <div class="mt-2">
                                                <label class="form-label fw-semibold mb-1" style="color:var(--gray-700);">
                                                    Pilih Bidak
                                                </label>
                                                @php
                                                    $selectedToken = old('token_style.' . ($i-1), array_keys($tokenOptions)[$i - 1] ?? 'style_1');
                                                @endphp
                                                <div class="token-current-preview mb-2" id="token-preview-wrapper-{{ $i }}">
                                                    <img id="token-preview-{{ $i }}"
                                                         src="{{ asset('images/tokens/' . $selectedToken . '.png') }}"
                                                         alt="Preview Bidak Pemain {{ $i }}"
                                                         class="token-preview-image-lg">
                                                    <span class="small ms-2" style="color:var(--gray-500);" id="token-preview-label-{{ $i }}">{{ $tokenOptions[$selectedToken] ?? 'Style 1' }}</span>
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
    /* Player number circle variants */
    .number-circle {
        width: 45px; height: 45px; border-radius: 50%;
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
        background: white; padding: 16px; border-radius: 14px;
        box-shadow: var(--shadow-sm);
        border: 1.5px solid rgba(185,28,28,0.08);
        transition: all 0.3s ease;
    }
    .player-input-card:hover {
        transform: translateX(4px);
        box-shadow: var(--shadow-md);
        border-color: var(--crimson);
    }

    .token-current-preview {
        display: flex; align-items: center; padding: 8px 10px;
        border: 1.5px solid var(--crimson-soft); border-radius: 10px;
        background: var(--crimson-subtle);
    }
    .token-preview-image-lg {
        width: 34px; height: 34px; object-fit: contain;
        border-radius: 8px; border: 1px solid var(--gray-200);
        background: white; padding: 2px;
    }

    .token-options-grid {
        display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px;
    }
    .token-option-item {
        border: 1.5px solid var(--crimson-soft); border-radius: 10px;
        padding: 6px 4px; text-align: center; cursor: pointer;
        background: white; transition: all 0.2s ease; margin: 0;
    }
    .token-option-item:hover {
        border-color: var(--crimson); transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(185,28,28,0.18);
    }
    .token-option-item input { display: none; }
    .token-option-item img { width: 38px; height: 38px; object-fit: contain; border-radius: 6px; }
    .token-option-item:has(input:checked) {
        border-color: var(--crimson); background: var(--crimson-soft);
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
            card.style.transform = 'translateX(4px)';
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
