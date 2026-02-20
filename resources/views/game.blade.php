@extends('layouts.app')

@section('content')
<style>
    @font-face {
        font-family: 'Montserrat Custom';
        src: url('{{ asset('fonts/Montserrat.otf') }}') format('opentype');
        font-weight: 100 900;
        font-style: normal;
        font-display: swap;
    }

    :root {
        --navy-deep: #0B1E33;       /* dasar paling gelap */
        --navy-dark: #13294B;        /* navy utama */
        --navy-medium: #1E3A6F;       /* navy medium */
        --navy-light: #2B4F8C;        /* navy terang */
        --navy-lighter: #3A6BB0;      /* aksen */
        --gold: #C6A43F;              /* emas utama */
        --gold-light: #E0C26B;        /* emas muda */
        --gold-soft: #F3E1A0;          /* emas sangat muda */
        --white: #FFFFFF;
        --gray-50: #F8FAFC;
        --gray-100: #F1F5F9;
        --gray-200: #E2E8F0;
        --gray-600: #475569;
        --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        --shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        --font-sans: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
        background: linear-gradient(145deg, var(--navy-deep) 0%, #0A1A2F 100%);
        font-family: var(--font-sans);
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .container {
        max-width: 1400px;
    }

    /* Layout utama: papan di kiri, panel di kanan */
    .game-layout {
        display: flex;
        gap: 28px;
        align-items: stretch;
    }

    .board-section {
        flex: 1;
        min-width: 0;
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(2px);
        border-radius: 40px;
        padding: 20px;
        box-shadow: var(--shadow-lg);
    }

    .player-section {
        width: 450px;
        flex-shrink: 0;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(8px);
        border-radius: 40px;
        padding: 28px 24px;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
    }

    /* Header / Logo di panel kanan */
    .game-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(198, 164, 63, 0.3);
    }

    .game-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .game-logo svg {
        width: 44px;
        height: 44px;
        stroke: var(--gold);
        stroke-width: 1.8;
        filter: drop-shadow(0 0 8px rgba(198, 164, 63, 0.4));
    }

    .game-logo span {
        padding-top: 15px;
        padding-bottom: 15px;
        font-family: 'Montserrat Custom', 'Montserrat', 'Inter', sans-serif;
        font-size: 2.6rem;
        line-height: 0.95;
        font-weight: 800;
        background: linear-gradient(135deg, #FFFFFF 0%, var(--gold-light) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: 3px;
    }

    .game-badge {
        background: var(--navy-dark);
        border: 1px solid var(--gold);
        color: var(--gold-light);
        padding: 0.5rem 1.2rem;
        border-radius: 60px;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Judul di panel kanan */
    .player-section h4 {
        color: white;
        font-weight: 600;
        font-size: 1.4rem;
        margin-bottom: 20px;
        padding-left: 12px;
        border-left: 4px solid var(--gold);
    }

    /* Papan */
    #board {
        background: rgba(10, 25, 45, 0.8);
        backdrop-filter: blur(4px);
        padding: 24px;
        border-radius: 32px;
        box-shadow: var(--shadow-lg), inset 0 2px 6px rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: grid;
        grid-template-columns: repeat(10, 1fr);
        gap: 6px;
        width: 100%;
        aspect-ratio: 1 / 1;
        position: relative;
    }

    .grid-item {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.0rem;
        color: rgba(255, 255, 255, 0.8);
        position: relative;
        box-shadow: var(--shadow-sm);
        transition: background-color 0.16s ease, border-color 0.16s ease, transform 0.16s ease, box-shadow 0.16s ease;
        aspect-ratio: 1 / 1;
        backdrop-filter: blur(2px);
        height: 72px;
        width: 72px;
    }

    .grid-item:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: var(--gold);
        transform: scale(1.02);
        z-index: 10;
        box-shadow: 0 0 20px rgba(198, 164, 63, 0.3);
    }

    /* Overlay SVG untuk tangga/ular */
    #board-overlay {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 5;
    }

    /* Marker (S/L) di pojok sel */
    .cell-markers {
        position: absolute;
        top: 2px;
        right: 2px;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 2px;
        z-index: 6;
    }

    .cell-marker {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(2px);
    }

    .cell-marker.snake {
        background: rgba(185, 28, 28, 0.8);
        border-color: #EF4444;
    }

    .cell-marker.ladder {
        background: rgba(22, 101, 52, 0.8);
        border-color: #10B981;
    }

    /* Token pemain */
    .player-token {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        position: absolute;
        left: var(--token-x, 50%);
        top: var(--token-y, 50%);
        transform: translate(-50%, -50%);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4), 0 0 0 2px rgba(255, 255, 255, 0.2);
        border: 2px solid var(--gold);
        transition: left 0.16s cubic-bezier(0.22, 1, 0.36, 1), top 0.16s cubic-bezier(0.22, 1, 0.36, 1), width 0.16s ease, height 0.16s ease, box-shadow 0.2s ease;
        z-index: 20;
        overflow: hidden;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: var(--navy-dark);
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));
        will-change: transform, left, top, width, height;
    }

    .player-token:hover {
        transform: translate(-50%, -50%) scale(1.15);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.6), 0 0 0 3px var(--gold-light);
        z-index: 30;
    }

    .token-preview-image {
        display: inline-block;
        width: 35px;
        height: 35px;
        margin-right: 10px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: var(--navy-dark);
        vertical-align: middle;
    }

    /* Daftar pemain */
    .list-group {
        background: transparent;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 24px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .list-group-item {
        background: rgba(255, 255, 255, 0.03);
        border: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 16px 18px;
        color: white;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-item.selected-player {
        background: rgba(198, 164, 63, 0.2);
        border-left: 4px solid var(--gold);
    }

    .pos-badge {
        background: rgba(0, 0, 0, 0.4) !important;
        color: white;
        font-size: 0.9rem;
        padding: 0.3rem 1rem;
        border-radius: 40px;
        font-weight: 600;
        margin-left: auto;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .select-player-btn {
        background: var(--gold);
        border: none;
        color: var(--navy-dark);
        font-weight: 700;
        border-radius: 40px;
        padding: 0.3rem 1.2rem;
        font-size: 0.8rem;
        transition: all 0.2s;
        margin-left: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .select-player-btn:hover {
        background: var(--gold-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(198, 164, 63, 0.4);
    }

    /* Tombol dadu */
    #roll-dice-btn {
        width: 100%;
        background: linear-gradient(145deg, var(--navy-medium), var(--navy-dark));
        border: 1px solid var(--gold);
        color: white;
        font-weight: 700;
        border-radius: 60px;
        padding: 16px;
        font-size: 1.2rem;
        box-shadow: var(--shadow-md);
        transition: all 0.2s;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    #roll-dice-btn:hover:not(:disabled) {
        background: linear-gradient(145deg, var(--navy-light), var(--navy-medium));
        border-color: var(--gold-light);
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -10px rgba(198, 164, 63, 0.5);
    }

    #roll-dice-btn:disabled {
        opacity: 0.5;
        filter: grayscale(0.5);
    }

    /* Tombol soal */
    #next-question, #show-question-btn {
        width: 100%;
        padding: 14px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.2s;
        margin-bottom: 12px;
    }

    #next-question {
        background: var(--gold);
        border: none;
        color: var(--navy-dark);
        box-shadow: 0 8px 16px -6px rgba(198, 164, 63, 0.4);
    }

    #next-question:hover:not(:disabled) {
        background: var(--gold-light);
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -8px var(--gold);
    }

    #show-question-btn {
        background: transparent;
        border: 2px solid var(--gold);
        color: var(--gold);
    }

    #show-question-btn:hover:not(:disabled) {
        background: rgba(198, 164, 63, 0.15);
        border-color: var(--gold-light);
        color: var(--gold-light);
    }

    /* Question area (saat soal ditampilkan di bawah papan) */
    #question-area {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 32px;
        padding: 24px;
        margin-top: 24px;
        color: white;
    }

    #question-area h5 {
        color: var(--gold);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 12px;
    }

    #question-text {
        font-size: 1.4rem;
        font-weight: 400;
        line-height: 1.6;
        color: rgba(255,255,255,0.9);
    }

    /* Modal */
    .modal.fade .modal-dialog {
        transform: translate3d(0, 18px, 0) scale(0.985);
        opacity: 0;
        transition: transform 0.22s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.22s ease;
        will-change: transform, opacity;
    }

    .modal.show .modal-dialog {
        transform: translate3d(0, 0, 0) scale(1);
        opacity: 1;
    }

    .modal-content {
        background: var(--navy-dark);
        border: 1px solid var(--gold);
        border-radius: 40px;
        overflow: hidden;
        box-shadow: 0 16px 28px rgba(0,0,0,0.48);
        will-change: transform, opacity;
    }

    .modal-header {
        background: var(--navy-deep);
        border-bottom: 1px solid rgba(198, 164, 63, 0.3);
        padding: 24px 28px;
    }

    .modal-title {
        color: var(--gold);
        font-weight: 700;
        font-size: 1.8rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1) sepia(1) saturate(5) hue-rotate(350deg);
        opacity: 0.8;
    }

    .modal-body {
        background: var(--navy-dark);
        padding: 32px;
        color: white;
    }

    #questionModal .modal-dialog {
        max-width: 840px;
        width: min(92vw, 840px);
    }

    #questionModal .modal-body {
        padding: 30px 36px;
    }

    #questionModal #modal-question-text {
        font-size: 2.1rem;
        line-height: 1.45;
    }

    #modal-question-text {
        font-size: 2rem;
        font-weight: 500;
        margin-bottom: 24px;
    }

    #modal-timer {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gold);
        text-shadow: 0 0 20px rgba(198,164,63,0.5);
    }

    /* Modal dadu */
    #dice-animation {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        background: rgba(255,255,255,0.03);
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(198,164,63,0.3);
    }

    #dice-result {
        font-size: 8rem;
        line-height: 1;
        filter: drop-shadow(0 0 10px rgba(198, 164, 63, 0.45));
        color: white;
        will-change: transform;
    }

    .dice-rolling {
        animation: dice-roll-3d 0.34s infinite linear;
        will-change: transform, filter;
    }

    .dice-land {
        animation: dice-land-bounce 0.3s ease-out;
    }

    @keyframes dice-roll-3d {
        0% { transform: rotate(0deg) scale(0.92); }
        50% { transform: rotate(180deg) scale(1.04); }
        100% { transform: rotate(360deg) scale(0.92); }
    }

    @keyframes dice-land-bounce {
        0% { transform: scale(1.22); opacity: 0.9; }
        55% { transform: scale(0.96); }
        80% { transform: scale(1.03); }
        100% { transform: scale(1); }
    }

    .modal-footer {
        border-top: 1px solid rgba(198,164,63,0.2);
    }

    .btn-navy, .btn-navy-outline {
        padding: 12px 28px;
        border-radius: 60px;
        font-weight: 600;
    }

    .btn-navy {
        background: var(--gold);
        border: none;
        color: var(--navy-dark);
    }

    .btn-navy-outline {
        background: transparent;
        border: 2px solid var(--gold);
        color: var(--gold);
    }

    .btn-navy-outline:hover {
        background: var(--gold);
        color: var(--navy-dark);
    }

    /* Podium */
    .podium-place {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 24px;
        color: white;
    }

    .table {
        color: white;
        --bs-table-bg: transparent;
        --bs-table-border-color: rgba(255,255,255,0.1);
    }

    /* Responsif */
    @media (max-width: 992px) {
        .game-layout {
            flex-direction: column;
        }
        .player-section {
            width: 100%;
        }
        #board {
            grid-template-columns: repeat(10, 1fr);
        }
    }
</style>

<div class="container">
    <!-- Layout baru: kiri papan, kanan panel -->
    <div class="game-layout">
        <!-- Sebelah kiri: FULL PAPAN -->
        <div class="board-section">
            <div id="board">
                <svg id="board-overlay" aria-hidden="true"></svg>
                @php
                    $cells = [];
                    $legacyTokenMap = [
                        'triangle_red' => 'style_1',
                        'triangle_yellow' => 'style_2',
                        'circle_black' => 'style_3',
                        'circle_blue' => 'style_4',
                        'square_green' => 'style_5',
                        'square_orange' => 'style_6',
                        'diamond_purple' => 'style_7',
                        'hex_cyan' => 'style_8',
                    ];
                    $tokenByPlayer = [];
                    foreach ($game->players as $p) {
                        $raw = $p->token_style ?? 'style_1';
                        $normalized = preg_match('/^style_[1-8]$/', $raw) ? $raw : ($legacyTokenMap[$raw] ?? 'style_1');
                        $tokenByPlayer[$p->id] = $normalized;
                    }
                    for ($i = 10; $i >= 1; $i--) {
                        if ($i % 2 == 0) {
                            for ($j = 1; $j <= 10; $j++) {
                                $cells[] = ($i-1)*10 + $j;
                            }
                        } else {
                            for ($j = 10; $j >= 1; $j--) {
                                $cells[] = ($i-1)*10 + $j;
                            }
                        }
                    }
                @endphp

                @foreach($cells as $cell)
                    <div class="grid-item" data-pos="{{ $cell }}" id="cell-{{ $cell }}">
                        {{ $cell }}
                        @php
                            $cellMarkers = $snakesLadders->filter(function ($item) use ($cell) {
                                return (int) $item->start === (int) $cell || (int) $item->end === (int) $cell;
                            });
                        @endphp
                        @if($cellMarkers->isNotEmpty())
                            <div class="cell-markers">
                                @foreach($cellMarkers as $item)
                                    @php
                                        $isStart = (int) $item->start === (int) $cell;
                                        $isSnake = $item->type === 'snake';
                                        $markerClass = $isSnake ? 'snake' : 'ladder';
                                        $symbol = $isSnake ? ($isStart ? 'S' : 's') : ($isStart ? 'L' : 'l');
                                        $label = $isStart
                                            ? ($isSnake ? "Turun ke {$item->end}" : "Naik ke {$item->end}")
                                            : ($isSnake ? "Dari {$item->start}" : "Dari {$item->start}");
                                    @endphp
                                    <span class="cell-marker {{ $markerClass }}" title="{{ $label }}">{{ $symbol }}</span>
                                @endforeach
                            </div>
                        @endif
                        @foreach($game->players as $player)
                            @if($player->posisi == $cell)
                                <div class="player-token"
                                     data-player-id="{{ $player->id }}"
                                     data-token-style="{{ $tokenByPlayer[$player->id] ?? 'style_1' }}"
                                     style="background-image:url('{{ asset('images/tokens/' . ($tokenByPlayer[$player->id] ?? 'style_1') . '.png') }}');"
                                     title="{{ $player->nama }}"></div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>

        </div>

        <!-- Sebelah kanan: panel dengan header, pemain, tombol -->
        <div class="player-section">
            <!-- Header dipindah ke sini -->
            <div class="game-header">
                <div class="game-logo">
                    <span>Ular<br/>tangga</span>
                </div>
                <div class="game-badge">
                    {{ $game->status === 'playing' ? 'Sedang Bermain' : 'Selesai' }}
                </div>
            </div>

            <h4>Daftar Pemain</h4>
            <ul class="list-group" id="player-list">
                @foreach($game->players as $player)
                    <li class="list-group-item"
                        data-player-id="{{ $player->id }}"
                        data-player-name="{{ $player->nama }}"
                        data-token-style="{{ $tokenByPlayer[$player->id] ?? 'style_1' }}">
                        <span class="token-preview-image"
                              style="background-image:url('{{ asset('images/tokens/' . ($tokenByPlayer[$player->id] ?? 'style_1') . '.png') }}');"></span>
                        <span>{{ $player->nama }}</span>
                        <span class="badge pos-badge" id="pos-{{ $player->id }}">{{ $player->posisi }}</span>
                        <button class="btn btn-sm select-player-btn" style="display: none;">Pilih</button>
                    </li>
                @endforeach
            </ul>

            <button id="roll-dice-btn" type="button" disabled>🎲 Acak Dadu</button>

            <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 20px;">
                <button id="next-question" class="btn-navy" >Soal Selanjutnya</button>
                <button id="show-question-btn" class="btn-navy-outline" disabled>Tampilkan Soal</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Soal (sama, hanya gaya berubah via CSS) -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionModalLabel">Soal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="modal-question-text" class="fs-3 fw-bold"></p>
                <div id="modal-timer" class="display-1 text-primary mt-3">15</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-navy-outline" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dadu -->
<div class="modal fade" id="diceModal" tabindex="-1" aria-labelledby="diceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="diceModalLabel">Lempar Dadu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="dice-animation" class="my-4">
                    <span id="dice-result" class="display-1">⚀</span>
                </div>
                <p id="dice-message" class="fs-5"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-navy" id="dice-ok-btn" data-bs-dismiss="modal" disabled>OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Podium -->
<div class="modal fade" id="podiumModal" tabindex="-1" aria-labelledby="podiumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="podiumModalLabel">Permainan Selesai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row text-center mb-4">
                    <div class="col-md-4">
                        <div class="podium-place p-3">
                            <h2 class="text-secondary">2</h2>
                            <h5 id="rank2-name">-</h5>
                            <p>Petak <span id="rank2-pos">0</span></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="podium-place p-3" style="background: rgba(198,164,63,0.15); border-color: var(--gold);">
                            <h2 class="text-warning">1</h2>
                            <h5 id="rank1-name">-</h5>
                            <p>Petak <span id="rank1-pos">0</span></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="podium-place p-3">
                            <h2 class="text-secondary">3</h2>
                            <h5 id="rank3-name">-</h5>
                            <p>Petak <span id="rank3-pos">0</span></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table">
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
                <button type="button" class="btn-navy" onclick="window.location.href='{{ route('setup') }}'">Main Lagi</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@php
    $snakeLadderPathsJs = $snakesLadders->map(function ($item) {
        return [
            'start' => (int) $item->start,
            'end' => (int) $item->end,
            'type' => $item->type,
        ];
    })->values();
@endphp
<script>
    // (Script tetap sama seperti sebelumnya, tidak diubah)
    let gameId = {{ $game->id }};
    let currentQuestion = null;
    let timerInterval = null;
    let countdown = 15;
    let selectedPlayerId = null;
    let hasRolledDice = true;
    let questionDisplayed = false;
    let gameFinished = {{ $game->status === 'finished' ? 'true' : 'false' }};

    const nextQuestionBtn = document.getElementById('next-question');
    const showQuestionBtn = document.getElementById('show-question-btn');
    const playerListItems = document.querySelectorAll('#player-list li');
    const board = document.getElementById('board');
    const boardOverlay = document.getElementById('board-overlay');
    const snakeLadderPaths = @json($snakeLadderPathsJs);

    // Modal elements
    const questionModal = new bootstrap.Modal(document.getElementById('questionModal'));
    const modalQuestionText = document.getElementById('modal-question-text');
    const modalTimer = document.getElementById('modal-timer');
    const diceModal = new bootstrap.Modal(document.getElementById('diceModal'));
    const diceResult = document.getElementById('dice-result');
    const diceMessage = document.getElementById('dice-message');
    const diceOkBtn = document.getElementById('dice-ok-btn');
    const rollDiceBtn = document.getElementById('roll-dice-btn');
    const diceFaces = ['\u2680', '\u2681', '\u2682', '\u2683', '\u2684', '\u2685'];
    const tokenBasePath = @json(asset('images/tokens'));
    let randomDiceInterval = null;
    let selectedPlayerName = null;

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
            
            var myModal = new bootstrap.Modal(document.getElementById('podiumModal'));
            myModal.show();
        });
    }

    function createSvg(tagName, attrs) {
        const el = document.createElementNS('http://www.w3.org/2000/svg', tagName);
        Object.entries(attrs).forEach(([key, value]) => el.setAttribute(key, value));
        return el;
    }

    function getCellCenter(pos) {
        const cell = document.getElementById(`cell-${pos}`);
        if (!cell) return null;
        const cellRect = cell.getBoundingClientRect();
        const boardRect = board.getBoundingClientRect();
        return {
            x: cellRect.left - boardRect.left + (cellRect.width / 2),
            y: cellRect.top - boardRect.top + (cellRect.height / 2),
        };
    }

    // Fungsi menggambar tangga dengan gaya baru
    function drawLadder(startPoint, endPoint) {
        const dx = endPoint.x - startPoint.x;
        const dy = endPoint.y - startPoint.y;
        const len = Math.hypot(dx, dy);
        if (!len) return;

        const px = -dy / len;
        const py = dx / len;
        const offset = 8;
        const rungCount = Math.max(6, Math.floor(len / 22));

        const rail1Start = { x: startPoint.x + (px * offset), y: startPoint.y + (py * offset) };
        const rail1End = { x: endPoint.x + (px * offset), y: endPoint.y + (py * offset) };
        const rail2Start = { x: startPoint.x - (px * offset), y: startPoint.y - (py * offset) };
        const rail2End = { x: endPoint.x - (px * offset), y: endPoint.y - (py * offset) };

        // Rel tangga (warna emas dengan gradien)
        boardOverlay.appendChild(createSvg('line', {
            x1: rail1Start.x, y1: rail1Start.y, x2: rail1End.x, y2: rail1End.y,
            stroke: '#04792f', 'stroke-width': 5, 'stroke-linecap': 'round', opacity: '0.9',
            'filter': 'drop-shadow(0 0 4px #16a34a)'
        }));
        boardOverlay.appendChild(createSvg('line', {
            x1: rail2Start.x, y1: rail2Start.y, x2: rail2End.x, y2: rail2End.y,
            stroke: '#04792f', 'stroke-width': 5, 'stroke-linecap': 'round', opacity: '0.9',
            'filter': 'drop-shadow(0 0 4px #16a34a)'
        }));

        // Anak tangga
        for (let i = 1; i < rungCount; i++) {
            const t = i / rungCount;
            const x1 = rail1Start.x + ((rail1End.x - rail1Start.x) * t);
            const y1 = rail1Start.y + ((rail1End.y - rail1Start.y) * t);
            const x2 = rail2Start.x + ((rail2End.x - rail2Start.x) * t);
            const y2 = rail2Start.y + ((rail2End.y - rail2Start.y) * t);
            boardOverlay.appendChild(createSvg('line', {
                x1, y1, x2, y2, stroke: '#04792f', 'stroke-width': 3.5, 'stroke-linecap': 'round', opacity: '0.82'
            }));
        }
    }

    // Fungsi menggambar ular dengan gaya baru (berwarna merah dengan efek gradien dan kepala)
    function drawSnake(startPoint, endPoint) {
        const dx = endPoint.x - startPoint.x;
        const dy = endPoint.y - startPoint.y;
        const len = Math.hypot(dx, dy);
        if (!len) return;

        const px = -dy / len;
        const py = dx / len;
        const segments = Math.max(12, Math.floor(len / 18));
        const amplitude = 9;
        let pathData = `M ${startPoint.x} ${startPoint.y}`;

        for (let i = 1; i <= segments; i++) {
            const t = i / segments;
            const baseX = startPoint.x + (dx * t);
            const baseY = startPoint.y + (dy * t);
            const wave = Math.sin(t * Math.PI * segments * 0.6) * amplitude;
            const x = baseX + (px * wave);
            const y = baseY + (py * wave);
            pathData += ` L ${x} ${y}`;
        }

        // Badan ular dengan gradien (stroke merah gelap ke merah terang)
        boardOverlay.appendChild(createSvg('path', {
            d: pathData, fill: 'none', stroke: '#B91C1C', 'stroke-width': 6,
            'stroke-linecap': 'round', opacity: '0.9',
            'filter': 'drop-shadow(0 0 5px #EF4444)'
        }));

        // Kepala ular (lingkaran di start)
        boardOverlay.appendChild(createSvg('circle', {
            cx: startPoint.x, cy: startPoint.y, r: 7,
            fill: 'url(#snakeGradient)', stroke: '#FCA5A5', 'stroke-width': 2
        }));

        // Tambahkan gradien jika belum ada
        if (!document.getElementById('snakeGradient')) {
            const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
            const gradient = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
            gradient.setAttribute('id', 'snakeGradient');
            gradient.setAttribute('x1', '0%'); gradient.setAttribute('y1', '0%');
            gradient.setAttribute('x2', '100%'); gradient.setAttribute('y2', '0%');
            const stop1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
            stop1.setAttribute('offset', '0%'); stop1.setAttribute('stop-color', '#DC2626');
            const stop2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
            stop2.setAttribute('offset', '100%'); stop2.setAttribute('stop-color', '#7F1D1D');
            gradient.appendChild(stop1); gradient.appendChild(stop2);
            defs.appendChild(gradient);
            boardOverlay.appendChild(defs);
        }
    }

    function drawBoardConnections() {
        if (!board || !boardOverlay) return;

        const boardRect = board.getBoundingClientRect();
        boardOverlay.setAttribute('viewBox', `0 0 ${boardRect.width} ${boardRect.height}`);
        boardOverlay.setAttribute('width', `${boardRect.width}`);
        boardOverlay.setAttribute('height', `${boardRect.height}`);
        boardOverlay.innerHTML = '';

        snakeLadderPaths.forEach((path) => {
            const startPoint = getCellCenter(path.start);
            const endPoint = getCellCenter(path.end);
            if (!startPoint || !endPoint) return;

            if (path.type === 'ladder') {
                drawLadder(startPoint, endPoint);
            } else if (path.type === 'snake') {
                drawSnake(startPoint, endPoint);
            }
        });
    }

    function sleep(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
    }

    function nextFrame() {
        return new Promise((resolve) => requestAnimationFrame(() => resolve()));
    }

    function normalizeTokenStyle(tokenStyle) {
        const legacyMap = {
            triangle_red: 'style_1',
            triangle_yellow: 'style_2',
            circle_black: 'style_3',
            circle_blue: 'style_4',
            square_green: 'style_5',
            square_orange: 'style_6',
            diamond_purple: 'style_7',
            hex_cyan: 'style_8',
        };

        if (/^style_[1-8]$/.test(tokenStyle || '')) {
            return tokenStyle;
        }
        return legacyMap[tokenStyle] || 'style_1';
    }

    function layoutTokensInCell(cell) {
        if (!cell) return;
        const tokens = Array.from(cell.querySelectorAll('.player-token'));
        const count = tokens.length;
        if (count === 0) return;

        const slotsByCount = {
            1: [[50, 50]],
            2: [[35, 35], [65, 65]],
            3: [[50, 28], [34, 64], [66, 64]],
            4: [[34, 34], [66, 34], [34, 66], [66, 66]],
        };

        const slots = slotsByCount[Math.min(count, 4)] || slotsByCount[4];
        const tokenSize = count === 1 ? 44 : count === 2 ? 32 : count === 3 ? 28 : 24;

        tokens.forEach((token, index) => {
            const [x, y] = slots[index] || slots[slots.length - 1];
            token.style.setProperty('--token-x', `${x}%`);
            token.style.setProperty('--token-y', `${y}%`);
            token.style.width = `${tokenSize}px`;
            token.style.height = `${tokenSize}px`;
        });
    }

    function layoutAllTokens() {
        document.querySelectorAll('#board .grid-item').forEach((cell) => {
            layoutTokensInCell(cell);
        });
    }

    async function movePlayerToken(playerId, newPos, options = {}) {
        const { animate = false, duration = 220 } = options;
        const targetCell = document.getElementById(`cell-${newPos}`);
        if (!targetCell) return;

        const listItem = document.querySelector(`#player-list li[data-player-id="${playerId}"]`);
        const tokenStyle = normalizeTokenStyle(listItem?.getAttribute('data-token-style'));
        let token = document.querySelector(`.player-token[data-player-id="${playerId}"]`);

        if (!token) {
            token = document.createElement('div');
            token.className = 'player-token';
            token.setAttribute('data-player-id', playerId);
            token.setAttribute('data-token-style', tokenStyle);
            token.style.backgroundImage = `url('${tokenBasePath}/${tokenStyle}.png')`;
            token.title = listItem?.getAttribute('data-player-name') || '';
            targetCell.appendChild(token);
            document.getElementById(`pos-${playerId}`).innerText = newPos;
            layoutTokensInCell(targetCell);
            return;
        }

        const oldCell = token.parentElement?.classList?.contains('grid-item') ? token.parentElement : null;
        const oldRect = token.getBoundingClientRect();

        token.setAttribute('data-token-style', tokenStyle);
        token.style.backgroundImage = `url('${tokenBasePath}/${tokenStyle}.png')`;
        token.title = listItem?.getAttribute('data-player-name') || '';
        targetCell.appendChild(token);
        document.getElementById(`pos-${playerId}`).innerText = newPos;

        if (oldCell) {
            layoutTokensInCell(oldCell);
        }
        layoutTokensInCell(targetCell);

        if (!animate || !token.animate) {
            return;
        }

        const newRect = token.getBoundingClientRect();
        const dx = oldRect.left - newRect.left;
        const dy = oldRect.top - newRect.top;

        if (Math.abs(dx) < 1 && Math.abs(dy) < 1) {
            return;
        }

        await nextFrame();
        const animation = token.animate([
            { transform: `translate(calc(-50% + ${dx}px), calc(-50% + ${dy}px)) scale(1.04)` },
            { transform: 'translate(-50%, -50%) scale(1)' }
        ], {
            duration,
            easing: 'cubic-bezier(0.22, 1, 0.36, 1)',
            fill: 'none'
        });

        try {
            await animation.finished;
        } catch (_) {
            // ignore animation cancellation
        }
    }

    async function animatePlayerMovement(playerId, oldPos, diceValue, finalPos, effectType) {
        const firstTarget = effectType ? Math.min(oldPos + diceValue, 100) : finalPos;

        if (firstTarget !== oldPos) {
            const firstStep = firstTarget > oldPos ? 1 : -1;
            for (let pos = oldPos + firstStep; firstStep > 0 ? pos <= firstTarget : pos >= firstTarget; pos += firstStep) {
                await movePlayerToken(playerId, pos, { animate: true, duration: 290 });
            }
        }

        if (effectType && finalPos !== firstTarget) {
            await sleep(360);
            const secondStep = finalPos > firstTarget ? 1 : -1;
            for (let pos = firstTarget + secondStep; secondStep > 0 ? pos <= finalPos : pos >= finalPos; pos += secondStep) {
                await movePlayerToken(playerId, pos, { animate: true, duration: 250 });
            }
        }
    }

    // Inisialisasi pion
    document.querySelectorAll('#player-list li').forEach((li, index) => {
        let playerId = li.getAttribute('data-player-id');
        let tokenStyle = normalizeTokenStyle(li.getAttribute('data-token-style'));
        let token = document.querySelector(`#cell-${document.getElementById(`pos-${playerId}`).innerText} .player-token[title="${li.getAttribute('data-player-name')}"]`);
        if (token) {
            token.setAttribute('data-token-style', tokenStyle);
            token.style.backgroundImage = `url('${tokenBasePath}/${tokenStyle}.png')`;
            token.setAttribute('data-player-id', playerId);
        }
    });
    layoutAllTokens();

    drawBoardConnections();
    window.addEventListener('resize', drawBoardConnections);

    // Fungsi untuk memulai timer di modal soal
    function startModalTimer(seconds) {
        clearInterval(timerInterval);
        countdown = seconds;
        modalTimer.innerText = countdown;
        timerInterval = setInterval(() => {
            countdown--;
            modalTimer.innerText = countdown;
            if (countdown <= 0) {
                clearInterval(timerInterval);
                questionModal.hide(); // Tutup modal otomatis
            }
        }, 1000);
    }

    // Event: Klik Soal Selanjutnya
    nextQuestionBtn.addEventListener('click', function() {
        if (!hasRolledDice) {
            alert('Harap selesaikan lempar dadu terlebih dahulu!');
            return;
        }
        
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
            questionDisplayed = true;
            modalQuestionText.innerText = currentQuestion;
            startModalTimer(15);
            questionModal.show();

            showQuestionBtn.disabled = true;
            showQuestionBtn.innerText = 'Soal Ditampilkan';

            nextQuestionBtn.disabled = true;
            hasRolledDice = false;
            selectedPlayerId = null;
            selectedPlayerName = null;
            rollDiceBtn.disabled = true;
            rollDiceBtn.innerText = '🎲 Acak Dadu';

            document.querySelectorAll('.select-player-btn').forEach(btn => btn.style.display = 'none');
            playerListItems.forEach(item => item.classList.remove('selected-player'));
        });
    });

    showQuestionBtn.addEventListener('click', function() {
        if (!currentQuestion) {
            alert('Soal belum tersedia. Silakan tekan Soal Selanjutnya dulu.');
            return;
        }

        questionDisplayed = true;
        modalQuestionText.innerText = currentQuestion;
        startModalTimer(15);
        questionModal.show();

        showQuestionBtn.disabled = true;
        showQuestionBtn.innerText = 'Soal Ditampilkan';
    });

    // Saat modal soal ditutup (manual atau otomatis), aktifkan tombol pilih
    document.getElementById('questionModal').addEventListener('hidden.bs.modal', function () {
        clearInterval(timerInterval);
        questionDisplayed = false;

        if (!hasRolledDice) {
            showQuestionBtn.disabled = false;
            showQuestionBtn.innerText = 'Tampilkan Soal';
            document.querySelectorAll('.select-player-btn').forEach(btn => btn.style.display = 'inline-block');
        }
    });

    // Event: Klik tombol Pilih pada pemain
    playerListItems.forEach(li => {
        let selectBtn = li.querySelector('.select-player-btn');
        selectBtn.addEventListener('click', function() {
            let playerId = li.getAttribute('data-player-id');
            selectedPlayerId = playerId;
            selectedPlayerName = li.getAttribute('data-player-name');

            playerListItems.forEach(item => item.classList.remove('selected-player'));
            li.classList.add('selected-player');

            rollDiceBtn.disabled = false;
            rollDiceBtn.innerText = `🎲 Acak Dadu (${selectedPlayerName})`;
        });
    });

    rollDiceBtn.addEventListener('click', function() {
        if (!selectedPlayerId) {
            alert('Pilih pemain terlebih dahulu.');
            return;
        }

        rollDiceBtn.disabled = true;
        rollDiceBtn.innerText = 'Mengacak...';
        document.querySelectorAll('.select-player-btn').forEach(btn => btn.style.display = 'none');

        // Reset modal dadu
        diceResult.innerText = '⚀';
        diceResult.classList.remove('dice-land');
        diceResult.classList.add('dice-rolling');
        diceMessage.innerText = `Mengocok dadu untuk ${selectedPlayerName}...`;
        diceOkBtn.disabled = true;
        window.lastDiceData = null;
        diceModal.show();

        // Animasi acak dadu (berubah cepat)
        randomDiceInterval = setInterval(() => {
            const randomIndex = Math.floor(Math.random() * 6);
            diceResult.innerText = diceFaces[randomIndex];
        }, 110);

        setTimeout(() => {
            fetch(`/game/${gameId}/roll-dice/${selectedPlayerId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                // Hentikan interval animasi acak
                clearInterval(randomDiceInterval);
                randomDiceInterval = null;
                
                // Hentikan animasi roll
                diceResult.classList.remove('dice-rolling');
                
                // Tampilkan hasil dadu sesuai data.dice
                diceResult.innerText = diceFaces[data.dice - 1];
                
                // Efek landing saat hasil muncul
                diceResult.classList.add('dice-land');
                setTimeout(() => { diceResult.classList.remove('dice-land'); }, 450);
                
                // Buat pesan
                let message = `${data.playerName} mendapat dadu ${data.dice}. Pindah dari ${data.oldPos} ke ${data.newPos}.`;
                if (data.effect === 'snake') {
                    message += `\n${data.playerName} terkena ular, turun ke ${data.effectEnd}.`;
                } else if (data.effect === 'ladder') {
                    message += `\n${data.playerName} mendapat tangga, naik ke ${data.effectEnd}.`;
                }
                diceMessage.innerText = message;
                
                // Simpan data untuk diproses setelah OK
                window.lastDiceData = data;
                
                // Aktifkan tombol OK
                diceOkBtn.disabled = false;
            })
            .catch(() => {
                clearInterval(randomDiceInterval);
                randomDiceInterval = null;
                diceResult.classList.remove('dice-rolling');
                diceMessage.innerText = 'Terjadi kesalahan. Silakan coba lagi.';
                diceOkBtn.disabled = false;
            });
        }, 1450);
    });

    // Event: Klik OK di modal dadu
    diceOkBtn.addEventListener('click', async function() {
        const data = window.lastDiceData;
        if (!data) return;

        const oldPos = Number(data.oldPos);
        const diceValue = Number(data.dice);
        const finalPos = Number(data.newPos);
        const playerId = Number(selectedPlayerId);

        // Update posisi pemain dengan animasi per langkah
        await animatePlayerMovement(playerId, oldPos, diceValue, finalPos, data.effect);
        
        // Jika ada pemenang
        if (data.gameFinished) {
            gameFinished = true;
            alert(`🎉 Selamat! ${data.winner} menang! 🎉`);
            
            nextQuestionBtn.disabled = true;
            document.querySelectorAll('.select-player-btn').forEach(btn => btn.disabled = true);
            rollDiceBtn.disabled = true;
            showQuestionBtn.disabled = true;
            
            showPodium();
            return;
        }
        
        // Aktifkan next question
        nextQuestionBtn.disabled = false;
        showQuestionBtn.disabled = true;
        showQuestionBtn.innerText = 'Tampilkan Soal';
        currentQuestion = null;
        questionDisplayed = false;
        hasRolledDice = true;
        selectedPlayerId = null;
        selectedPlayerName = null;
        rollDiceBtn.disabled = true;
        rollDiceBtn.innerText = '🎲 Acak Dadu';
        playerListItems.forEach(item => item.classList.remove('selected-player'));
        
        // Tutup modal (dijamin oleh data-bs-dismiss)
    });

    // Jika game sudah selesai, nonaktifkan tombol
    if (gameFinished) {
        nextQuestionBtn.disabled = true;
        document.querySelectorAll('.select-player-btn').forEach(btn => btn.disabled = true);
        rollDiceBtn.disabled = true;
        showQuestionBtn.disabled = true;
    }
</script>
@endpush
