@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6" style="width: 80%;">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Header dengan gradient navy -->
                <div class="card-header bg-gradient-navy text-white border-0 py-4 px-4" style="background: linear-gradient(135deg, #0B2447 0%, #19376D 100%);">
                    <div class="d-flex align-items-center">
                        <div class="game-icon me-3">
                            <i class="fas fa-dice-d20 fa-2x"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">Setup Permainan Ular Tangga</h3>
                            <p class="mb-0 opacity-75 small mt-1">Atur preferensi permainan Anda</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 p-lg-5 bg-light">
                    <form method="POST" action="{{ route('game.setup') }}" id="setupForm">
                        @csrf
                        
                        <!-- Progress Steps dengan warna navy -->
                        <div class="setup-progress mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="step active">
                                    <div class="step-circle" style="background: #0B2447; color: white;">1</div>
                                    <span class="step-label" style="color: #0B2447; font-weight: 600;">Setup</span>
                                </div>
                                <div class="step-line" style="background: #0B2447;"></div>
                                <div class="step">
                                    <div class="step-circle" style="background: #e9ecef; color: #0B2447;">2</div>
                                    <span class="step-label">Pemain</span>
                                </div>
                                <div class="step-line" style="background: #e9ecef;"></div>
                                <div class="step">
                                    <div class="step-circle" style="background: #e9ecef; color: #0B2447;">3</div>
                                    <span class="step-label">Main</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Materi Selection dengan Icon -->
                        <div class="mb-4">
                            <label for="materi_id" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-book-open me-2" style="color: #0B2447;"></i> Pilih Materi
                            </label>
                            <div class="position-relative">
                                <select class="form-select form-select-lg rounded-3 @error('materi_id') is-invalid @enderror" 
                                        id="materi_id" 
                                        name="materi_id" 
                                        required
                                        style="border-left: 4px solid #0B2447; background-color: white;">
                                    <option value="" disabled selected>-- Pilih Materi Pembelajaran --</option>
                                    @foreach($materis as $materi)
                                        <option value="{{ $materi->id }}" {{ old('materi_id') == $materi->id ? 'selected' : '' }}>
                                            📚 {{ $materi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="select-arrow" style="color: #0B2447;">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            @error('materi_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Jumlah Pemain dengan Counter -->
                        <div class="mb-4">
                            <label for="jumlah_pemain" class="form-label fw-semibold text-secondary">
                                <i class="fas fa-users me-2" style="color: #0B2447;"></i> Jumlah Pemain
                            </label>
                            <div class="player-counter-wrapper">
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-navy rounded-start-3" id="decrementPlayer" style="border-color: #0B2447; color: #0B2447;">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" 
                                           class="form-control form-control-lg text-center border-navy @error('jumlah_pemain') is-invalid @enderror" 
                                           id="jumlah_pemain" 
                                           name="jumlah_pemain" 
                                           min="1" 
                                           max="4" 
                                           value="{{ old('jumlah_pemain', 2) }}" 
                                           required
                                           readonly
                                           style="border-top: 1px solid #0B2447; border-bottom: 1px solid #0B2447;">
                                    <button type="button" class="btn btn-outline-navy rounded-end-3" id="incrementPlayer" style="border-color: #0B2447; color: #0B2447;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @error('jumlah_pemain')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            
                            <!-- Player Preview Icons dengan warna navy -->
                            <div class="player-preview mt-3 d-flex justify-content-center gap-3" id="playerPreview">
                                <!-- Akan diisi JavaScript -->
                            </div>
                        </div>
                        
                        <!-- Game Rules Info dengan aksen navy -->
                        <div class="alert bg-white border-0 shadow-sm rounded-3 mb-4" style="border-left: 4px solid #0B2447;">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle fa-2x" style="color: #0B2447;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fw-bold mb-2" style="color: #0B2447;">Aturan Permainan:</h6>
                                    <ul class="small mb-0 ps-3" style="color: #2d3e50;">
                                        <li>Setiap pemain akan menjawab soal secara bergiliran</li>
                                        <li>Guru yang menentukan pemain yang berhak melempar dadu</li>
                                        <li>Pemain pertama mencapai petak 100 adalah pemenangnya</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button dengan efek navy -->
                        <button type="submit" class="btn w-100 py-3 fw-bold rounded-3 position-relative overflow-hidden" id="submitBtn" style="background: linear-gradient(135deg, #0B2447 0%, #19376D 100%); border: none; color: white;">
                            <span class="btn-text">
                                <i class="fas fa-play-circle me-2"></i>Mulai Permainan
                            </span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2"></span>Memuat...
                            </span>
                        </button>
                        
                        <!-- Footer Note -->
                        <p class="text-center text-muted small mt-3 mb-0">
                            <i class="fas fa-shield-alt me-1"></i>Data aman & tidak disimpan
                        </p>
                    </form>
                </div>
            </div>
            
            <!-- Decorative Elements dengan dadu navy -->
            <div class="text-center mt-4">
                <div class="dice-decoration">
                    <i class="fas fa-dice-one fa-2x me-2" style="color: #0B2447; opacity: 0.5;"></i>
                    <i class="fas fa-dice-two fa-2x me-2" style="color: #0B2447; opacity: 0.5;"></i>
                    <i class="fas fa-dice-three fa-2x me-2" style="color: #0B2447; opacity: 0.5;"></i>
                    <i class="fas fa-dice-four fa-2x me-2" style="color: #0B2447; opacity: 0.5;"></i>
                    <i class="fas fa-dice-five fa-2x me-2" style="color: #0B2447; opacity: 0.5;"></i>
                    <i class="fas fa-dice-six fa-2x" style="color: #0B2447; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    /* Navy theme colors */
    .bg-gradient-navy {
        background: linear-gradient(135deg, #0B2447 0%, #19376D 100%);
    }
    
    .btn-outline-navy:hover {
        background: linear-gradient(135deg, #0B2447 0%, #19376D 100%);
        color: white !important;
        border-color: transparent;
    }
    
    .border-navy {
        border-color: #0B2447;
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
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto 8px;
        transition: all 0.3s ease;
    }
    
    .step.active .step-circle {
        box-shadow: 0 5px 15px rgba(11, 36, 71, 0.4);
    }
    
    .step-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .step.active .step-label {
        color: #0B2447;
        font-weight: 600;
    }
    
    .step-line {
        flex: 1;
        height: 2px;
        margin: 0 10px;
        margin-top: -10px;
    }
    
    /* Select Arrow */
    .select-arrow {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }
    
    /* Player Counter */
    .player-counter-wrapper .input-group {
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .player-counter-wrapper .btn {
        width: 50px;
        transition: all 0.3s ease;
        background: white;
    }
    
    .player-counter-wrapper .btn:hover {
        background: linear-gradient(135deg, #0B2447 0%, #19376D 100%);
        color: white !important;
        border-color: transparent;
    }
    
    .player-counter-wrapper input {
        font-size: 1.5rem;
        font-weight: bold;
        background: white;
    }
    
    .player-counter-wrapper input:focus {
        box-shadow: none;
    }
    
    /* Player Preview */
    .player-preview .player-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        animation: popIn 0.3s ease;
    }
    
    @keyframes popIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        80% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    /* Form Select Styling */
    .form-select-lg {
        padding: 12px 20px;
        font-size: 1rem;
        border: 2px solid #e9ecef;
        appearance: none;
        background-image: none;
    }
    
    .form-select-lg:focus {
        border-color: #0B2447;
        box-shadow: 0 0 0 0.2rem rgba(11, 36, 71, 0.25);
    }
    
    /* Card Hover Effect */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(11, 36, 71, 0.15) !important;
    }
    
    /* Submit Button Hover */
    #submitBtn {
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        overflow: hidden;
    }
    
    #submitBtn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #19376D 0%, #0B2447 100%);
        transition: left 0.5s ease;
        z-index: -1;
    }
    
    #submitBtn:hover::before {
        left: 0;
    }
    
    #submitBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(11, 36, 71, 0.3);
    }
    
    /* Dice Decoration */
    .dice-decoration i {
        transition: all 0.3s ease;
    }
    
    .dice-decoration i:hover {
        opacity: 1 !important;
        transform: rotate(180deg) scale(1.2);
        color: #0B2447 !important;
    }
    
    /* Responsive Adjustments */
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
    }
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahInput = document.getElementById('jumlah_pemain');
    const decrementBtn = document.getElementById('decrementPlayer');
    const incrementBtn = document.getElementById('incrementPlayer');
    const playerPreview = document.getElementById('playerPreview');
    const form = document.getElementById('setupForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Warna-warna untuk preview player (navy theme)
    const playerColors = [
        'linear-gradient(135deg, #0B2447 0%, #19376D 100%)',
        'linear-gradient(135deg, #1E3A5F 0%, #2A4F7A 100%)',
        'linear-gradient(135deg, #2A4F7A 0%, #3B6A9E 100%)',
        'linear-gradient(135deg, #3B6A9E 0%, #4C85C2 100%)'
    ];
    
    // Update player preview
    function updatePlayerPreview(count) {
        playerPreview.innerHTML = '';
        for (let i = 0; i < count; i++) {
            const icon = document.createElement('div');
            icon.className = 'player-icon';
            icon.style.background = playerColors[i % playerColors.length];
            icon.style.boxShadow = `0 5px 15px rgba(11, 36, 71, 0.3)`;
            icon.textContent = i + 1;
            icon.setAttribute('title', `Pemain ${i + 1}`);
            playerPreview.appendChild(icon);
        }
    }
    
    // Initialize preview
    updatePlayerPreview(jumlahInput.value);
    
    // Decrement
    decrementBtn.addEventListener('click', function() {
        let value = parseInt(jumlahInput.value);
        if (value > 1) {
            jumlahInput.value = value - 1;
            updatePlayerPreview(jumlahInput.value);
        }
    });
    
    // Increment
    incrementBtn.addEventListener('click', function() {
        let value = parseInt(jumlahInput.value);
        if (value < 4) {
            jumlahInput.value = value + 1;
            updatePlayerPreview(jumlahInput.value);
        }
    });
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        submitBtn.disabled = true;
    });
    
    // Smooth scroll ke error jika ada
    @if($errors->any())
        document.querySelector('.card').scrollIntoView({ 
            behavior: 'smooth',
            block: 'center'
        });
    @endif
});
</script>
@endsection