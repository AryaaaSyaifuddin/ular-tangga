@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-10" st>
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Header dengan tema navy -->
                <div class="card-header bg-navy text-white border-0 py-4 px-4" style="background: linear-gradient(135deg, #000080 0%, #1E3A8A 50%, #2563EB 100%);">
                    <div class="d-flex align-items-center">
                        <div class="game-icon me-3">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">Input Nama Pemain</h3>
                            <p class="mb-0 opacity-75 small mt-1">Masukkan nama-nama pemain yang akan bermain</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 p-lg-5 bg-light">
                    <!-- Progress Steps (lanjutan dari setup) -->
                    <div class="setup-progress mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="step completed">
                                <div class="step-circle bg-navy text-white">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="step-label text-navy">Setup</span>
                            </div>
                            <div class="step-line bg-navy"></div>
                            <div class="step active">
                                <div class="step-circle" style="background: linear-gradient(135deg, #000080 0%, #1E3A8A 100%); color: white;">
                                    2
                                </div>
                                <span class="step-label fw-bold text-navy">Pemain</span>
                            </div>
                            <div class="step-line"></div>
                            <div class="step">
                                <div class="step-circle">3</div>
                                <span class="step-label">Main</span>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Game -->
                    <div class="alert alert-navy bg-soft-navy border-navy rounded-3 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-dice-d6 text-navy fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fw-bold text-navy mb-1">Detail Permainan</h6>
                                <p class="mb-0 small">
                                    <i class="fas fa-book-open me-1 text-navy"></i>Materi: <span class="fw-semibold">{{ $game->materi->nama }}</span><br>
                                    <i class="fas fa-users me-1 text-navy"></i>Jumlah Pemain: <span class="fw-semibold">{{ $game->jumlah_pemain }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('game.players.store', $game) }}" id="playerForm">
                        @csrf
                        @php
                            $tokenOptions = [
                                'style_1' => 'Style 1',
                                'style_2' => 'Style 2',
                                'style_3' => 'Style 3',
                                'style_4' => 'Style 4',
                                'style_5' => 'Style 5',
                                'style_6' => 'Style 6',
                                'style_7' => 'Style 7',
                                'style_8' => 'Style 8',
                            ];
                        @endphp
                        
                        <!-- Daftar Input Nama Pemain dengan desain kartu -->
                        <div class="players-container mb-4">
                            @for($i = 1; $i <= $game->jumlah_pemain; $i++)
                                <div class="player-input-card mb-3" id="player-card-{{ $i }}">
                                    <div class="d-flex align-items-center">
                                        <div class="player-number me-3">
                                            <div class="number-circle bg-navy text-white">
                                                {{ $i }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <label for="nama{{ $i }}" class="form-label fw-semibold text-secondary mb-1">
                                                Nama Pemain {{ $i }}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="fas fa-user text-navy"></i>
                                                </span>
                                                <input type="text" 
                                                       class="form-control @error('nama.' . ($i-1)) is-invalid @enderror" 
                                                       id="nama{{ $i }}" 
                                                       name="nama[]" 
                                                       placeholder="Contoh: Andi" 
                                                       value="{{ old('nama.' . ($i-1)) }}"
                                                       required
                                                       autocomplete="off">
                                            </div>
                                            @error('nama.' . ($i-1))
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                            <div class="mt-2">
                                                <label class="form-label fw-semibold text-secondary mb-1">
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
                                                    <span class="small text-muted ms-2" id="token-preview-label-{{ $i }}">{{ $tokenOptions[$selectedToken] ?? 'Style 1' }}</span>
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
                        <div class="alert alert-light bg-white border-0 shadow-sm rounded-3 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-lightbulb text-navy fa-2x me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Tips:</h6>
                                    <p class="small text-muted mb-0">
                                        Gunakan nama panggilan yang mudah diingat. Nama akan ditampilkan di papan permainan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('setup') }}" class="btn btn-outline-navy w-50 py-3 rounded-3">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-navy w-50 py-3 rounded-3 position-relative overflow-hidden" id="submitBtn">
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

            <!-- Decorative Elements -->
            <div class="text-center mt-4">
                <div class="dice-decoration">
                    <i class="fas fa-dice-one fa-2x text-navy-light me-2"></i>
                    <i class="fas fa-dice-two fa-2x text-navy-light me-2"></i>
                    <i class="fas fa-dice-three fa-2x text-navy-light me-2"></i>
                    <i class="fas fa-dice-four fa-2x text-navy-light me-2"></i>
                    <i class="fas fa-dice-five fa-2x text-navy-light me-2"></i>
                    <i class="fas fa-dice-six fa-2x text-navy-light"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Warna Navy */
    :root {
        --navy: #000080;
        --navy-dark: #000066;
        --navy-light: #333399;
        --navy-soft: #e6e6ff;
    }

    .bg-navy {
        background: linear-gradient(135deg, #000080 0%, #1E3A8A 100%) !important;
    }

    .bg-soft-navy {
        background-color: #e8eaf6;
    }

    .text-navy {
        color: #000080 !important;
    }

    .border-navy {
        border-color: #000080 !important;
    }

    .btn-navy {
        background: linear-gradient(135deg, #000080 0%, #1E3A8A 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .btn-navy::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1E3A8A 0%, #000080 100%);
        transition: left 0.5s ease;
        z-index: -1;
    }

    .btn-navy:hover::before {
        left: 0;
    }

    .btn-navy:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 128, 0.3);
    }

    .btn-outline-navy {
        border: 2px solid #000080;
        color: #000080;
        background: transparent;
        transition: all 0.3s ease;
    }

    .btn-outline-navy:hover {
        background: linear-gradient(135deg, #000080 0%, #1E3A8A 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 128, 0.2);
    }

    /* Progress Steps */
    .setup-progress {
        position: relative;
    }

    .step {
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #6c757d;
        margin: 0 auto 8px;
        transition: all 0.3s ease;
    }

    .step.completed .step-circle {
        background: linear-gradient(135deg, #000080 0%, #1E3A8A 100%);
        color: white;
    }

    .step.active .step-circle {
        background: linear-gradient(135deg, #000080 0%, #1E3A8A 100%);
        color: white;
        box-shadow: 0 5px 15px rgba(0, 0, 128, 0.4);
    }

    .step-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
    }

    .step.active .step-label {
        color: #000080;
        font-weight: 600;
    }

    .step.completed .step-label {
        color: #000080;
    }

    .step-line {
        flex: 1;
        height: 2px;
        background: #e9ecef;
        margin: 0 10px;
        margin-top: -10px;
    }

    .step-line.bg-navy {
        background: linear-gradient(135deg, #000080 0%, #1E3A8A 100%);
    }

    /* Player Number Circle */
    .number-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        background: linear-gradient(135deg, #000080 0%, #1E3A8A 100%);
        color: white;
        box-shadow: 0 5px 10px rgba(0, 0, 128, 0.2);
    }

    /* Player Input Card */
    .player-input-card {
        background: white;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 128, 0.1);
    }

    .player-input-card:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 25px rgba(0, 0, 128, 0.15);
        border-color: #000080;
    }

    /* Input Group Styling */
    .input-group-text {
        border: 1px solid #ced4da;
        border-right: none;
        border-radius: 8px 0 0 8px;
        background: white;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-left: none;
        border-radius: 0 8px 8px 0;
        padding: 0.75rem 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2929ae00;
        box-shadow: 0 0 0 0.001rem rgba(0, 0, 128, 0.45);
    }

    .token-current-preview {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        border: 1px solid #dbe4ff;
        border-radius: 10px;
        background: #f8fbff;
    }

    .token-preview-image-lg {
        width: 34px;
        height: 34px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: white;
        padding: 2px;
    }

    .token-options-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 8px;
    }

    .token-option-item {
        border: 1px solid #dbe4ff;
        border-radius: 10px;
        padding: 6px 4px;
        text-align: center;
        cursor: pointer;
        background: white;
        transition: all 0.2s ease;
        margin: 0;
    }

    .token-option-item:hover {
        border-color: #000080;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(0, 0, 128, 0.12);
    }

    .token-option-item input {
        display: none;
    }

    .token-option-item img {
        width: 38px;
        height: 38px;
        object-fit: contain;
        border-radius: 6px;
    }

    .token-option-item span {
        display: block;
        font-size: 0.68rem;
        color: #334155;
        line-height: 1.1;
    }

    .token-option-item input:checked + img {
        border-color: #000080;
    }

    .token-option-item:has(input:checked) {
        border-color: #000080;
        background: #eef2ff;
    }

    /* Alert Navy */
    .alert-navy {
        border-left: 4px solid #000080;
    }

    /* Dice Decoration */
    .dice-decoration i {
        opacity: 0.5;
        transition: all 0.3s ease;
        color: #000080;
    }

    .dice-decoration i:hover {
        opacity: 1;
        transform: rotate(180deg) scale(1.2);
        color: #1E3A8A;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .card-body {
            padding: 1.5rem !important;
        }
        
        .step-circle {
            width: 30px;
            height: 30px;
            font-size: 0.9rem;
        }
        
        .step-label {
            font-size: 0.7rem;
        }
        
        .number-circle {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
    }
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('playerForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Auto-focus ke input pertama
    document.getElementById('nama1')?.focus();
    
    // Form submission dengan loading state
    form.addEventListener('submit', function(e) {
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        submitBtn.disabled = true;
    });
    
    // Animasi untuk setiap input card
    const playerCards = document.querySelectorAll('.player-input-card');
    playerCards.forEach((card, index) => {
        const input = card.querySelector('input[type="text"]');
        if (!input) return;
        input.addEventListener('focus', () => {
            card.style.transform = 'translateX(5px)';
            card.style.borderColor = '#000080';
            card.style.boxShadow = '0 10px 25px rgba(0, 0, 128, 0.15)';
        });
        
        input.addEventListener('blur', () => {
            card.style.transform = 'translateX(0)';
            if (!input.value) {
                card.style.borderColor = 'rgba(0, 0, 128, 0.1)';
                card.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.05)';
            }
        });
        
        // Jika ada nilai (dari old input), beri highlight
        if (input.value) {
            card.style.borderColor = '#000080';
            card.style.boxShadow = '0 5px 15px rgba(0, 0, 128, 0.1)';
        }
    });

    // Update preview gambar bidak per pemain
    const tokenRadios = document.querySelectorAll('.token-option-item input[type="radio"]');
    tokenRadios.forEach((radio) => {
        radio.addEventListener('change', function() {
            const playerIndex = this.dataset.playerIndex;
            const preview = document.getElementById(`token-preview-${playerIndex}`);
            const previewLabel = document.getElementById(`token-preview-label-${playerIndex}`);
            const selectedImage = this.parentElement.querySelector('img');
            if (preview && selectedImage) {
                preview.src = selectedImage.src;
            }
            if (previewLabel) {
                previewLabel.textContent = this.dataset.tokenLabel || this.value;
            }
        });
    });
});
</script>
@endsection
