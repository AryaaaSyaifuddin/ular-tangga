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

    /* Override body for game page — deep crimson dark background */
    body {
        background:
            radial-gradient(circle at top left, rgba(239,68,68,0.18), transparent 32%),
            linear-gradient(145deg, #FFFFFF 0%, #FDF6F6 58%, #FEE2E2 100%);
        font-family: var(--font-body);
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .container { max-width: 1400px; }

    .game-layout {
        display: flex;
        gap: 28px;
        align-items: stretch;
    }

    .board-section {
        flex: 1;
        min-width: 0;
        background: rgba(255,255,255,0.92);
        border-radius: 28px;
        padding: 20px;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(185,28,28,0.12);
    }

    .player-section {
        width: 450px;
        flex-shrink: 0;
        background: rgba(255,255,255,0.96);
        backdrop-filter: blur(8px);
        border-radius: 28px;
        padding: 28px 24px;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(185,28,28,0.14);
        color: var(--gray-800);
    }

    /* Header */
    .game-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(185,28,28,0.16);
    }

    .game-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .game-logo span {
        padding-top: 15px;
        padding-bottom: 15px;
        font-family: 'Montserrat Custom', 'Montserrat', var(--font-body);
        font-size: 2.6rem;
        line-height: 0.95;
        font-weight: 800;
        background: linear-gradient(135deg, var(--crimson-dark) 0%, var(--crimson-light) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: 3px;
    }

    .game-badge {
        background: #FFFFFF;
        border: 1px solid rgba(185,28,28,0.28);
        color: var(--crimson);
        padding: 0.5rem 1.2rem;
        border-radius: 60px;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .player-section h4 {
        color: var(--crimson-dark);
        font-weight: 600;
        font-size: 1.4rem;
        margin-bottom: 20px;
        padding-left: 12px;
        border-left: 4px solid var(--crimson-light);
    }

    /* Board */
    #board {
        background: #FFFFFF;
        padding: 24px;
        border-radius: 24px;
        box-shadow: var(--shadow-lg), inset 0 0 0 1px rgba(185,28,28,0.08);
        border: 1px solid rgba(185,28,28,0.18);
        display: grid;
        grid-template-columns: repeat(10, 1fr);
        gap: 6px;
        width: 100%;
        aspect-ratio: 1 / 1;
        position: relative;
    }

    .grid-item {
        border: 1px solid rgba(185,28,28,0.14);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.0rem;
        color: var(--crimson-dark);
        position: relative;
        box-shadow: var(--shadow-sm);
        transition: background-color 0.16s ease, border-color 0.16s ease, transform 0.16s ease;
        aspect-ratio: 1 / 1;
        height: 72px;
        width: 72px;
    }

    .grid-item:hover {
        border-color: var(--crimson-light);
        transform: scale(1.02);
        z-index: 10;
        box-shadow: 0 10px 20px rgba(185,28,28,0.18);
    }

    /* Chess-style red and white board cells */
    .grid-item.tile-white {
        background: #FFFFFF;
    }

    .grid-item.tile-red {
        background: #FEE2E2;
        border-color: rgba(185,28,28,0.24);
    }

    #board-overlay {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 5;
    }

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
        background: rgba(0,0,0,0.5);
        color: white;
        border: 1px solid rgba(255,255,255,0.25);
        backdrop-filter: blur(2px);
    }

    .cell-marker.snake  { background: rgba(185,28,28,0.8); border-color: #EF4444; }
    .cell-marker.ladder { background: rgba(22,101,52,0.8);  border-color: #10B981; }

    /* Tokens */
    .player-token {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        position: absolute;
        left: var(--token-x, 50%);
        top: var(--token-y, 50%);
        transform: translate(-50%, -50%);
        box-shadow: 0 8px 16px rgba(0,0,0,0.4), 0 0 0 2px rgba(255,255,255,0.2);
        border: 2px solid var(--crimson-light);
        transition: left 0.16s cubic-bezier(0.22,1,0.36,1), top 0.16s cubic-bezier(0.22,1,0.36,1), width 0.16s ease, height 0.16s ease;
        z-index: 20;
        overflow: hidden;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: var(--crimson-dark);
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));
        will-change: transform, left, top, width, height;
    }

    .player-token:hover {
        transform: translate(-50%, -50%) scale(1.15);
        box-shadow: 0 12px 24px rgba(0,0,0,0.55), 0 0 0 3px var(--crimson-light);
        z-index: 30;
    }

    .token-preview-image {
        display: inline-block;
        width: 35px;
        height: 35px;
        margin-right: 10px;
        border: 2px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: var(--crimson-dark);
        vertical-align: middle;
    }

    /* Player list */
    .list-group {
        background: transparent;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 24px;
        border: 1px solid rgba(185,28,28,0.12);
    }

    .list-group-item {
        background: #FFFFFF;
        border: none;
        border-bottom: 1px solid rgba(185,28,28,0.10);
        padding: 16px 18px;
        color: var(--gray-800);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s;
    }

    .list-group-item:last-child { border-bottom: none; }

    .list-group-item.selected-player {
        background: #FEE2E2;
        border-left: 4px solid var(--crimson-light);
    }

    .pos-badge {
        background: #FFFFFF !important;
        color: var(--crimson-dark);
        font-size: 0.9rem;
        padding: 0.3rem 1rem;
        border-radius: 40px;
        font-weight: 600;
        margin-left: auto;
        border: 1px solid rgba(185,28,28,0.20);
    }

    .select-player-btn {
        background: var(--crimson);
        border: none;
        color: white;
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
        background: var(--crimson-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(239,68,68,0.4);
    }

    /* Dice button */
    #roll-dice-btn {
        width: 100%;
        background: linear-gradient(145deg, var(--crimson), var(--crimson-dark));
        border: 1px solid var(--crimson);
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
        background: linear-gradient(145deg, var(--crimson), var(--crimson-mid));
        border-color: var(--crimson-light);
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -8px rgba(239,68,68,0.5);
    }

    #roll-dice-btn:disabled { opacity: 0.5; filter: grayscale(0.4); }

    /* Question/action buttons */
    #next-question, #show-question-btn {
        width: 100%;
        padding: 14px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.2s;
    }

    #next-question {
        background: linear-gradient(135deg, var(--crimson-dark), var(--crimson));
        border: none;
        color: white;
        box-shadow: 0 8px 16px -6px rgba(185,28,28,0.5);
    }

    #next-question:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--crimson), var(--crimson-light));
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -8px rgba(185,28,28,0.6);
    }

    #show-question-btn {
        background: #FFFFFF;
        border: 2px solid var(--crimson);
        color: var(--crimson);
    }

    #show-question-btn:hover:not(:disabled) {
        background: #FEE2E2;
        border-color: var(--crimson-light);
        color: var(--crimson-dark);
    }

    #next-question:disabled, #show-question-btn:disabled { opacity: 0.45; }

    /* Question area */
    #question-area {
        background: #FFFFFF;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(185,28,28,0.14);
        border-radius: 24px;
        padding: 24px;
        margin-top: 24px;
        color: var(--gray-800);
    }

    #question-area h5 {
        color: var(--crimson);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 12px;
    }

    #question-text {
        font-size: 1.4rem;
        font-weight: 400;
        line-height: 1.6;
        color: var(--gray-800);
    }

    /* Modal - Professional & Elegant (White Dominant) */
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
        background: #FFFFFF;
        border: 1px solid rgba(185,28,28,0.15);
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 24px 48px rgba(0,0,0,0.12), 0 8px 16px rgba(0,0,0,0.08);
        will-change: transform, opacity;
    }

    .modal-header {
        background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(253,246,246,0.96) 100%);
        border-bottom: 1px solid rgba(185,28,28,0.12);
        padding: 28px 32px;
        align-items: center;
    }

    .modal-title {
        color: var(--crimson-dark);
        font-weight: 700;
        font-size: 1.75rem;
        letter-spacing: 0.5px;
    }

    .modal-header .btn-close {
        filter: invert(0.2);
        opacity: 0.6;
        transition: all 0.2s ease;
    }

    .modal-header .btn-close:hover {
        opacity: 1;
        filter: invert(0);
    }

    .modal-body {
        background: #FFFFFF;
        padding: 40px 36px;
        color: var(--gray-800);
    }

    #questionModal .modal-dialog { max-width: 760px; width: min(92vw, 760px); }
    #questionModal .modal-body { padding: 48px 40px; }
    #questionModal #modal-question-text { 
        font-size: 1.95rem; 
        line-height: 1.55; 
        color: var(--gray-800);
        background: linear-gradient(135deg, rgba(239,68,68,0.04) 0%, rgba(253,246,246,0.06) 100%);
        padding: 32px 28px;
        border-radius: 20px;
        border-left: 5px solid var(--crimson);
    }

    #modal-question-text { 
        font-size: 1.9rem; 
        font-weight: 600; 
        margin-bottom: 28px; 
        color: var(--gray-800);
        letter-spacing: 0.3px;
        background: linear-gradient(135deg, rgba(239,68,68,0.04) 0%, rgba(253,246,246,0.06) 100%);
        padding: 32px 28px;
        border-radius: 20px;
        border-left: 5px solid var(--crimson);
    }

    #modal-timer {
        font-size: 2.4rem;
        font-weight: 700;
        color: var(--crimson);
        text-shadow: none;
        margin-top: 16px;
        padding: 16px 24px;
        background: linear-gradient(135deg, rgba(239,68,68,0.12) 0%, rgba(253,246,246,0.12) 100%);
        border-radius: 16px;
        display: inline-block;
        border: 1px solid rgba(239,68,68,0.2);
    }

    /* Dice modal */
    #dice-animation {
        width: 170px;
        height: 170px;
        margin: 24px auto;
        perspective: 800px;
        display: grid;
        place-items: center;
    }

    .dice-cube {
        width: 112px;
        height: 112px;
        position: relative;
        transform-style: preserve-3d;
        transform: rotateX(0deg) rotateY(0deg);
        transition: transform 0.42s cubic-bezier(0.2, 0.9, 0.2, 1.15);
        will-change: transform;
    }

    .dice-face {
        position: absolute;
        inset: 0;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: repeat(3, 1fr);
        gap: 8px;
        padding: 18px;
        background: linear-gradient(145deg, #FFFFFF, #F8FAFC);
        border: 2px solid rgba(185,28,28,0.22);
        border-radius: 18px;
        box-shadow: inset -10px -10px 18px rgba(185,28,28,0.08), inset 8px 8px 16px rgba(255,255,255,0.9);
        backface-visibility: hidden;
    }

    .dice-face.front  { transform: translateZ(56px); }
    .dice-face.back   { transform: rotateY(180deg) translateZ(56px); }
    .dice-face.right  { transform: rotateY(90deg) translateZ(56px); }
    .dice-face.left   { transform: rotateY(-90deg) translateZ(56px); }
    .dice-face.top    { transform: rotateX(90deg) translateZ(56px); }
    .dice-face.bottom { transform: rotateX(-90deg) translateZ(56px); }

    .dice-dot {
        width: 15px;
        height: 15px;
        align-self: center;
        justify-self: center;
        border-radius: 50%;
        background: var(--crimson);
        box-shadow: inset 2px 2px 3px rgba(255,255,255,0.32), inset -2px -2px 4px rgba(69,10,10,0.32);
    }

    .dot-center { grid-column: 2; grid-row: 2; }
    .dot-top-left { grid-column: 1; grid-row: 1; }
    .dot-top-right { grid-column: 3; grid-row: 1; }
    .dot-middle-left { grid-column: 1; grid-row: 2; }
    .dot-middle-right { grid-column: 3; grid-row: 2; }
    .dot-bottom-left { grid-column: 1; grid-row: 3; }
    .dot-bottom-right { grid-column: 3; grid-row: 3; }

    .dice-cube.dice-rolling {
        animation: dice-real-roll 0.72s linear infinite;
        transition: none;
    }

    .dice-cube.dice-land {
        animation: dice-land-bounce 0.34s ease-out;
    }

    @keyframes dice-real-roll {
        0%   { transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale(0.96); }
        35%  { transform: rotateX(190deg) rotateY(130deg) rotateZ(45deg) scale(1.02); }
        70%  { transform: rotateX(360deg) rotateY(300deg) rotateZ(120deg) scale(0.98); }
        100% { transform: rotateX(540deg) rotateY(420deg) rotateZ(180deg) scale(0.96); }
    }

    @keyframes dice-land-bounce {
        0%   { filter: drop-shadow(0 18px 14px rgba(0,0,0,0.22)); }
        45%  { transform: var(--dice-transform) translateY(-8px) scale(1.04); }
        72%  { transform: var(--dice-transform) translateY(3px) scale(0.98); }
        100% { transform: var(--dice-transform) translateY(0) scale(1); }
    }

    .modal-footer { 
        border-top: 1px solid rgba(185,28,28,0.12); 
        background: rgba(255,255,255,0.5);
        padding: 20px 32px;
    }

    .btn-navy, .btn-navy-outline {
        padding: 12px 28px;
        border-radius: 60px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-navy {
        background: linear-gradient(135deg, var(--crimson), var(--crimson-dark));
        border: none;
        color: white;
    }

    .btn-navy:hover {
        background: linear-gradient(135deg, var(--crimson-light), var(--crimson));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(239,68,68,0.3);
    }

    .btn-navy-outline {
        background: transparent;
        border: 2px solid var(--crimson);
        color: var(--crimson);
        transition: all 0.2s ease;
    }

    .btn-navy-outline:hover {
        background: rgba(185,28,28,0.08);
        color: var(--crimson-dark);
        border-color: var(--crimson-dark);
        transform: translateY(-2px);
    }

    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes zoomIn {
        from { opacity: 0; transform: scale(0.7); }
        to   { opacity: 1; transform: scale(1); }
    }

    .animate__animated { animation-duration: 0.6s; animation-fill-mode: both; }
    .animate__fadeInUp { animation-name: fadeInUp; }
    .animate__zoomIn   { animation-name: zoomIn; }
    .animate__fast     { animation-duration: 0.4s; }
    .animate__delay-1  { animation-delay: 0.1s; }
    .animate__delay-2  { animation-delay: 0.2s; }

    /* Podium */
    .podium-container {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        gap: 20px;
        margin: 30px 0 40px;
        padding: 0 10px;
        flex-wrap: wrap;
    }

    .podium-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 15px;
        border-radius: 32px;
        min-width: 160px;
        text-align: center;
        box-shadow: var(--shadow-lg);
        transition: transform 0.2s;
    }
    .podium-item:hover { transform: translateY(-8px); }

    .place-1 {
        background: linear-gradient(145deg, #FBBF24, #D97706);
        border: 2px solid #FDE68A;
        box-shadow: 0 0 30px rgba(251,191,36,0.55);
        order: 1;
        transform: scale(1.05);
    }
    .place-1 .podium-name { font-size: 1.8rem; font-weight: 800; color: #2D0A0A; text-shadow: 0 2px 4px rgba(255,255,255,0.4); }
    .place-1 .podium-pos  { font-size: 1.2rem; color: #2D0A0A; font-weight: 600; }

    .place-2 {
        background: linear-gradient(145deg, #E5E7EB, #9CA3AF);
        border: 2px solid #F3F4F6;
        order: 0;
    }
    .place-2 .podium-name { font-size: 1.5rem; font-weight: 700; color: #1F2937; }

    .place-3 {
        background: linear-gradient(145deg, #D97706, #B45309);
        border: 2px solid #FCD34D;
        order: 2;
    }
    .place-3 .podium-name { font-size: 1.5rem; font-weight: 700; color: #2D1B0E; }

    .medal {
        font-size: 7.5rem;
        line-height: 1;
        filter: drop-shadow(0 8px 12px rgba(0,0,0,0.3));
        margin-bottom: 15px;
        margin-top: 15px;
    }
    .crown { font-size: 2.5rem; margin-top: -25px; margin-bottom: -5px; filter: drop-shadow(0 0 6px gold); }
    .podium-name { font-weight: 700; margin: 8px 0 4px; word-break: break-word; }
    .podium-pos  { font-size: 1rem; opacity: 0.9; }

    /* Ranking table */
    .ranking-table-wrapper {
        background: rgba(255,255,255,0.03);
        border-radius: 28px;
        padding: 20px;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .ranking-title { font-size: 1.2rem; font-weight: 600; margin-bottom: 16px; color: #FCA5A5; letter-spacing: 0.5px; }
    .ranking-table { width: 100%; border-collapse: separate; border-spacing: 0 6px; }
    .ranking-table th {
        color: #FCA5A5; font-weight: 600; font-size: 0.9rem;
        text-transform: uppercase; padding: 10px 12px; background: rgba(0,0,0,0.2);
    }
    .ranking-table td { padding: 12px 12px; background: rgba(255,255,255,0.04); color: white; font-weight: 500; }
    .ranking-table tr:first-child td { background: rgba(251,191,36,0.12); }

    @media (max-width: 992px) {
        .game-layout { flex-direction: column; }
        .player-section { width: 100%; }
    }
</style>

<div class="container">
    <div class="game-layout">
        <!-- Left: Game Board -->
        <div class="board-section">
            <div id="board">
                <svg id="board-overlay" aria-hidden="true"></svg>
                @php
                    $cells = [];
                    $legacyTokenMap = [
                        'triangle_red'    => 'style_1', 'triangle_yellow' => 'style_2',
                        'circle_black'    => 'style_3', 'circle_blue'     => 'style_4',
                        'square_green'    => 'style_5', 'square_orange'   => 'style_6',
                        'diamond_purple'  => 'style_1', 'hex_cyan'        => 'style_2',
                    ];
                    $tokenByPlayer = [];
                    foreach ($game->players as $p) {
                        $raw = $p->token_style ?? 'style_1';
                        $normalized = preg_match('/^style_[1-6]$/', $raw) ? $raw : ($legacyTokenMap[$raw] ?? 'style_1');
                        $tokenByPlayer[$p->id] = $normalized;
                    }
                    for ($i = 10; $i >= 1; $i--) {
                        if ($i % 2 == 0) {
                            for ($j = 1; $j <= 10; $j++) { $cells[] = ($i-1)*10 + $j; }
                        } else {
                            for ($j = 10; $j >= 1; $j--) { $cells[] = ($i-1)*10 + $j; }
                        }
                    }
                @endphp

                @foreach($cells as $cell)
                    @php
                        $rowIndex = intdiv($loop->index, 10);
                        $colIndex = $loop->index % 10;
                        $tileClass = (($rowIndex + $colIndex) % 2 === 0) ? 'tile-white' : 'tile-red';
                    @endphp
                    <div class="grid-item {{ $tileClass }}" data-pos="{{ $cell }}" id="cell-{{ $cell }}">
                        {{ $cell }}
                        @php
                            $cellMarkers = $snakesLadders->filter(fn($item) =>
                                (int)$item->start === (int)$cell || (int)$item->end === (int)$cell);
                        @endphp
                        @if($cellMarkers->isNotEmpty())
                            <div class="cell-markers">
                                @foreach($cellMarkers as $item)
                                    @php
                                        $isStart = (int)$item->start === (int)$cell;
                                        $isSnake = $item->type === 'snake';
                                        $markerClass = $isSnake ? 'snake' : 'ladder';
                                        $symbol = $isSnake ? ($isStart ? 'S' : 's') : ($isStart ? 'L' : 'l');
                                        $label  = $isStart
                                            ? ($isSnake ? "Turun ke {$item->end}" : "Naik ke {$item->end}")
                                            : "Dari {$item->start}";
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

        <!-- Right: Player Panel -->
        <div class="player-section">
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
                        <button class="btn btn-sm select-player-btn" style="display:none;">Pilih</button>
                    </li>
                @endforeach
            </ul>

            <button id="roll-dice-btn" type="button" disabled>🎲 Acak Dadu</button>

            <div style="display:flex; flex-direction:column; gap:12px; margin-top:20px;">
                <button id="next-question" class="btn-navy">Soal Selanjutnya</button>
                <button id="show-question-btn" class="btn-navy-outline" disabled>Tampilkan Soal</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Soal -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="questionModalLabel">Soal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p id="modal-question-text" class="fs-3 fw-bold"></p>
                <div id="modal-timer" class="display-1 mt-3">15</div>
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
                    <div id="dice-cube" class="dice-cube" aria-label="Dadu" role="img">
                        <div class="dice-face front">
                            <span class="dice-dot dot-center"></span>
                        </div>
                        <div class="dice-face right">
                            <span class="dice-dot dot-top-left"></span>
                            <span class="dice-dot dot-bottom-right"></span>
                        </div>
                        <div class="dice-face top">
                            <span class="dice-dot dot-top-left"></span>
                            <span class="dice-dot dot-center"></span>
                            <span class="dice-dot dot-bottom-right"></span>
                        </div>
                        <div class="dice-face bottom">
                            <span class="dice-dot dot-top-left"></span>
                            <span class="dice-dot dot-top-right"></span>
                            <span class="dice-dot dot-bottom-left"></span>
                            <span class="dice-dot dot-bottom-right"></span>
                        </div>
                        <div class="dice-face left">
                            <span class="dice-dot dot-top-left"></span>
                            <span class="dice-dot dot-top-right"></span>
                            <span class="dice-dot dot-center"></span>
                            <span class="dice-dot dot-bottom-left"></span>
                            <span class="dice-dot dot-bottom-right"></span>
                        </div>
                        <div class="dice-face back">
                            <span class="dice-dot dot-top-left"></span>
                            <span class="dice-dot dot-top-right"></span>
                            <span class="dice-dot dot-middle-left"></span>
                            <span class="dice-dot dot-middle-right"></span>
                            <span class="dice-dot dot-bottom-left"></span>
                            <span class="dice-dot dot-bottom-right"></span>
                        </div>
                    </div>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="podiumModalLabel">
                    <span class="me-2">🏆</span> Permainan Selesai
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="podium-container">
                    <div class="podium-item place-2 animate__animated animate__fadeInUp animate__delay-1">
                        <div class="medal silver">🥈</div>
                        <div class="podium-name" id="rank2-name">-</div>
                        <div class="podium-pos">Petak <span id="rank2-pos">0</span></div>
                    </div>
                    <div class="podium-item place-1 animate__animated animate__zoomIn animate__fast">
                        <div class="crown">👑</div>
                        <div class="medal gold">🥇</div>
                        <div class="podium-name winner" id="rank1-name">-</div>
                        <div class="podium-pos">Petak <span id="rank1-pos">0</span></div>
                    </div>
                    <div class="podium-item place-3 animate__animated animate__fadeInUp animate__delay-2">
                        <div class="medal bronze">🥉</div>
                        <div class="podium-name" id="rank3-name">-</div>
                        <div class="podium-pos">Petak <span id="rank3-pos">0</span></div>
                    </div>
                </div>
                <div class="ranking-table-wrapper">
                    <h6 class="ranking-title">📋 Peringkat Lengkap</h6>
                    <table class="table ranking-table">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama Pemain</th>
                                <th>Posisi</th>
                            </tr>
                        </thead>
                        <tbody id="ranking-table-body"></tbody>
                    </table>
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
        return ['start' => (int)$item->start, 'end' => (int)$item->end, 'type' => $item->type];
    })->values();
@endphp
<script>
    let gameId = {{ $game->id }};
    let currentQuestion = null;
    let timerInterval = null;
    let countdown = 15;
    let selectedPlayerId = null;
    let hasRolledDice = true;
    let questionDisplayed = false;
    let gameFinished = {{ $game->status === 'finished' ? 'true' : 'false' }};

    const nextQuestionBtn  = document.getElementById('next-question');
    const showQuestionBtn  = document.getElementById('show-question-btn');
    const playerListItems  = document.querySelectorAll('#player-list li');
    const board            = document.getElementById('board');
    const boardOverlay     = document.getElementById('board-overlay');
    const snakeLadderPaths = @json($snakeLadderPathsJs);

    const questionModal    = new bootstrap.Modal(document.getElementById('questionModal'));
    const modalQuestionText = document.getElementById('modal-question-text');
    const modalTimer       = document.getElementById('modal-timer');
    const diceModal        = new bootstrap.Modal(document.getElementById('diceModal'));
    const diceCube         = document.getElementById('dice-cube');
    const diceMessage      = document.getElementById('dice-message');
    const diceOkBtn        = document.getElementById('dice-ok-btn');
    const rollDiceBtn      = document.getElementById('roll-dice-btn');
    const diceFaceTransforms = {
        1: 'rotateX(0deg) rotateY(0deg)',
        2: 'rotateX(0deg) rotateY(-90deg)',
        3: 'rotateX(-90deg) rotateY(0deg)',
        4: 'rotateX(90deg) rotateY(0deg)',
        5: 'rotateX(0deg) rotateY(90deg)',
        6: 'rotateX(0deg) rotateY(180deg)'
    };
    const tokenBasePath    = @json(asset('images/tokens'));
    let selectedPlayerName = null;

    if (gameFinished) { showPodium(); }

    function setDiceFace(value) {
        const transform = diceFaceTransforms[value] || diceFaceTransforms[1];
        diceCube.style.setProperty('--dice-transform', transform);
        diceCube.style.transform = transform;
        diceCube.setAttribute('aria-label', `Dadu angka ${value}`);
    }

    function startDiceRoll() {
        diceCube.classList.remove('dice-land');
        diceCube.style.transform = '';
        diceCube.classList.add('dice-rolling');
    }

    function landDice(value) {
        diceCube.classList.remove('dice-rolling');
        setDiceFace(value);
        void diceCube.offsetWidth;
        diceCube.classList.add('dice-land');
        setTimeout(() => diceCube.classList.remove('dice-land'), 420);
    }

    function showPodium() {
        fetch(`/game/${gameId}/ranking`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.length >= 1) { document.getElementById('rank1-name').innerText = data[0].nama; document.getElementById('rank1-pos').innerText = data[0].posisi; }
            if (data.length >= 2) { document.getElementById('rank2-name').innerText = data[1].nama; document.getElementById('rank2-pos').innerText = data[1].posisi; }
            if (data.length >= 3) { document.getElementById('rank3-name').innerText = data[2].nama; document.getElementById('rank3-pos').innerText = data[2].posisi; }
            let tbody = document.getElementById('ranking-table-body');
            tbody.innerHTML = '';
            data.forEach((player, index) => {
                tbody.innerHTML += `<tr><td>${index+1}</td><td>${player.nama}</td><td>${player.posisi}</td></tr>`;
            });
            new bootstrap.Modal(document.getElementById('podiumModal')).show();
        });
    }

    function createSvg(tagName, attrs) {
        const el = document.createElementNS('http://www.w3.org/2000/svg', tagName);
        Object.entries(attrs).forEach(([k, v]) => el.setAttribute(k, v));
        return el;
    }

    function getCellCenter(pos) {
        const cell = document.getElementById(`cell-${pos}`);
        if (!cell) return null;
        const cR = cell.getBoundingClientRect(), bR = board.getBoundingClientRect();
        return { x: cR.left - bR.left + cR.width/2, y: cR.top - bR.top + cR.height/2 };
    }

    function appendBoardDefs() {
        const defs = createSvg('defs', {});
        const wood = createSvg('linearGradient', { id: 'ladderWood', x1: '0%', y1: '0%', x2: '100%', y2: '100%' });
        wood.appendChild(createSvg('stop', { offset: '0%', 'stop-color': '#B45309' }));
        wood.appendChild(createSvg('stop', { offset: '48%', 'stop-color': '#D97706' }));
        wood.appendChild(createSvg('stop', { offset: '100%', 'stop-color': '#92400E' }));

        const snake = createSvg('linearGradient', { id: 'snakeBody', x1: '0%', y1: '0%', x2: '100%', y2: '100%' });
        snake.appendChild(createSvg('stop', { offset: '0%', 'stop-color': '#FCA5A5' }));
        snake.appendChild(createSvg('stop', { offset: '45%', 'stop-color': '#EF4444' }));
        snake.appendChild(createSvg('stop', { offset: '100%', 'stop-color': '#991B1B' }));

        defs.appendChild(wood);
        defs.appendChild(snake);
        boardOverlay.appendChild(defs);
    }

    function getWavePoint(s, e, t, amp, waves) {
        const dx=e.x-s.x, dy=e.y-s.y, len=Math.hypot(dx,dy) || 1;
        const px=-dy/len, py=dx/len;
        const wave=Math.sin(t*Math.PI*waves)*amp*(1-Math.abs(t-0.5)*0.35);
        return { x:s.x+dx*t+px*wave, y:s.y+dy*t+py*wave };
    }

    function drawLadder(s, e) {
        const dx = e.x-s.x, dy = e.y-s.y, len = Math.hypot(dx,dy);
        if (!len) return;
        const px = -dy/len, py = dx/len, off = 12, rungs = Math.max(5, Math.floor(len/28));
        const r1s={x:s.x+px*off,y:s.y+py*off}, r1e={x:e.x+px*off,y:e.y+py*off};
        const r2s={x:s.x-px*off,y:s.y-py*off}, r2e={x:e.x-px*off,y:e.y-py*off};
        const group = createSvg('g', { opacity: '0.72', filter: 'drop-shadow(0 2px 2px rgba(120,53,15,0.14))' });

        [[r1s,r1e],[r2s,r2e]].forEach(([a,b]) => {
            group.appendChild(createSvg('line',{x1:a.x,y1:a.y,x2:b.x,y2:b.y,stroke:'#92400E','stroke-width':8,'stroke-linecap':'round'}));
            group.appendChild(createSvg('line',{x1:a.x,y1:a.y,x2:b.x,y2:b.y,stroke:'url(#ladderWood)','stroke-width':5,'stroke-linecap':'round'}));
            group.appendChild(createSvg('line',{x1:a.x+px*2,y1:a.y+py*2,x2:b.x+px*2,y2:b.y+py*2,stroke:'#FEF3C7','stroke-width':1.2,'stroke-linecap':'round',opacity:'0.55'}));
        });
        for (let i=1;i<rungs;i++) {
            const t=i/rungs;
            const x1=r1s.x+(r1e.x-r1s.x)*t, y1=r1s.y+(r1e.y-r1s.y)*t;
            const x2=r2s.x+(r2e.x-r2s.x)*t, y2=r2s.y+(r2e.y-r2s.y)*t;
            group.appendChild(createSvg('line',{x1,y1,x2,y2,stroke:'#92400E','stroke-width':6,'stroke-linecap':'round'}));
            group.appendChild(createSvg('line',{x1,y1,x2,y2,stroke:'url(#ladderWood)','stroke-width':3.8,'stroke-linecap':'round'}));
        }
        boardOverlay.appendChild(group);
    }

    function drawSnake(s, e) {
        const dx=e.x-s.x, dy=e.y-s.y, len=Math.hypot(dx,dy);
        if (!len) return;
        const angle=Math.atan2(dy,dx)*180/Math.PI;
        const segs=Math.max(14,Math.floor(len/22)), amp=Math.min(18,Math.max(9,len/18));
        const waves=Math.max(3,Math.round(len/70));
        const points=[];
        for (let i=0;i<=segs;i++) points.push(getWavePoint(s,e,i/segs,amp,waves));
        const d=points.map((p,i)=>`${i?'L':'M'} ${p.x} ${p.y}`).join(' ');
        const head=points[0], neck=points[1] || points[0], tail=points[points.length-1];
        const headAngle=Math.atan2(head.y-neck.y,head.x-neck.x)*180/Math.PI;
        const group=createSvg('g',{opacity:'0.74',filter:'drop-shadow(0 2px 2px rgba(127,29,29,0.16))'});

        group.appendChild(createSvg('path',{d,fill:'none',stroke:'#7F1D1D','stroke-width':13,'stroke-linecap':'round','stroke-linejoin':'round',opacity:'0.72'}));
        group.appendChild(createSvg('path',{d,fill:'none',stroke:'url(#snakeBody)','stroke-width':10,'stroke-linecap':'round','stroke-linejoin':'round'}));
        group.appendChild(createSvg('path',{d,fill:'none',stroke:'#FEE2E2','stroke-width':2,'stroke-linecap':'round','stroke-linejoin':'round',opacity:'0.46'}));

        for (let i=2;i<points.length-2;i+=2) {
            const p=points[i];
            group.appendChild(createSvg('circle',{cx:p.x,cy:p.y,r:2.1,fill:'#FEE2E2',opacity:'0.45'}));
        }

        group.appendChild(createSvg('ellipse',{cx:head.x,cy:head.y,rx:11,ry:8.5,fill:'#EF4444',stroke:'#7F1D1D','stroke-width':2,transform:`rotate(${headAngle} ${head.x} ${head.y})`}));
        group.appendChild(createSvg('circle',{cx:head.x+Math.cos((headAngle-32)*Math.PI/180)*7,cy:head.y+Math.sin((headAngle-32)*Math.PI/180)*7,r:2.1,fill:'#111827'}));
        group.appendChild(createSvg('circle',{cx:head.x+Math.cos((headAngle+32)*Math.PI/180)*7,cy:head.y+Math.sin((headAngle+32)*Math.PI/180)*7,r:2.1,fill:'#111827'}));
        group.appendChild(createSvg('path',{d:`M ${head.x} ${head.y} l ${Math.cos(headAngle*Math.PI/180)*13} ${Math.sin(headAngle*Math.PI/180)*13} m 0 0 l ${Math.cos((headAngle-25)*Math.PI/180)*5} ${Math.sin((headAngle-25)*Math.PI/180)*5} m ${-Math.cos((headAngle-25)*Math.PI/180)*5} ${-Math.sin((headAngle-25)*Math.PI/180)*5} l ${Math.cos((headAngle+25)*Math.PI/180)*5} ${Math.sin((headAngle+25)*Math.PI/180)*5}`,fill:'none',stroke:'#DC2626','stroke-width':1.5,'stroke-linecap':'round'}));
        group.appendChild(createSvg('circle',{cx:tail.x,cy:tail.y,r:4,fill:'#991B1B',stroke:'#7F1D1D','stroke-width':1.5}));
        boardOverlay.appendChild(group);
    }

    function drawBoardConnections() {
        if (!board||!boardOverlay) return;
        const bR=board.getBoundingClientRect();
        boardOverlay.setAttribute('viewBox',`0 0 ${bR.width} ${bR.height}`);
        boardOverlay.setAttribute('width',`${bR.width}`);
        boardOverlay.setAttribute('height',`${bR.height}`);
        boardOverlay.innerHTML='';
        appendBoardDefs();
        snakeLadderPaths.forEach(path => {
            const s=getCellCenter(path.start), e=getCellCenter(path.end);
            if (!s||!e) return;
            path.type==='ladder' ? drawLadder(s,e) : drawSnake(s,e);
        });
    }

    function sleep(ms) { return new Promise(r => setTimeout(r,ms)); }
    function nextFrame() { return new Promise(r => requestAnimationFrame(r)); }

    function normalizeTokenStyle(t) {
        const m={triangle_red:'style_1',triangle_yellow:'style_2',circle_black:'style_3',circle_blue:'style_4',square_green:'style_5',square_orange:'style_6',diamond_purple:'style_1',hex_cyan:'style_2'};
        return /^style_[1-6]$/.test(t||'') ? t : (m[t]||'style_1');
    }

    function layoutTokensInCell(cell) {
        if (!cell) return;
        const tokens=Array.from(cell.querySelectorAll('.player-token')), n=tokens.length;
        if (!n) return;
        const slots={1:[[50,50]],2:[[35,35],[65,65]],3:[[50,28],[34,64],[66,64]],4:[[34,34],[66,34],[34,66],[66,66]]};
        const s=slots[Math.min(n,4)]||slots[4], sz=n===1?44:n===2?32:n===3?28:24;
        tokens.forEach((t,i)=>{
            const [x,y]=s[i]||s[s.length-1];
            t.style.setProperty('--token-x',`${x}%`);
            t.style.setProperty('--token-y',`${y}%`);
            t.style.width=`${sz}px`; t.style.height=`${sz}px`;
        });
    }

    function layoutAllTokens() {
        document.querySelectorAll('#board .grid-item').forEach(layoutTokensInCell);
    }

    async function movePlayerToken(playerId, newPos, opts={}) {
        const {animate=false, duration=220}=opts;
        const targetCell=document.getElementById(`cell-${newPos}`);
        if (!targetCell) return;
        const li=document.querySelector(`#player-list li[data-player-id="${playerId}"]`);
        const ts=normalizeTokenStyle(li?.getAttribute('data-token-style'));
        let token=document.querySelector(`.player-token[data-player-id="${playerId}"]`);
        if (!token) {
            token=document.createElement('div');
            token.className='player-token';
            token.setAttribute('data-player-id',playerId);
            token.setAttribute('data-token-style',ts);
            token.style.backgroundImage=`url('${tokenBasePath}/${ts}.png')`;
            token.title=li?.getAttribute('data-player-name')||'';
            targetCell.appendChild(token);
            document.getElementById(`pos-${playerId}`).innerText=newPos;
            layoutTokensInCell(targetCell);
            return;
        }
        const oldCell=token.parentElement?.classList?.contains('grid-item')?token.parentElement:null;
        const oldRect=token.getBoundingClientRect();
        token.setAttribute('data-token-style',ts);
        token.style.backgroundImage=`url('${tokenBasePath}/${ts}.png')`;
        token.title=li?.getAttribute('data-player-name')||'';
        targetCell.appendChild(token);
        document.getElementById(`pos-${playerId}`).innerText=newPos;
        if (oldCell) layoutTokensInCell(oldCell);
        layoutTokensInCell(targetCell);
        if (!animate||!token.animate) return;
        const newRect=token.getBoundingClientRect();
        const dx=oldRect.left-newRect.left, dy=oldRect.top-newRect.top;
        if (Math.abs(dx)<1&&Math.abs(dy)<1) return;
        await nextFrame();
        const anim=token.animate([{transform:`translate(calc(-50% + ${dx}px), calc(-50% + ${dy}px)) scale(1.04)`},{transform:'translate(-50%, -50%) scale(1)'}],{duration,easing:'cubic-bezier(0.22,1,0.36,1)',fill:'none'});
        try { await anim.finished; } catch(_) {}
    }

    async function animatePlayerMovement(playerId, oldPos, diceValue, finalPos, effectType) {
        const firstTarget=effectType?Math.min(oldPos+diceValue,100):finalPos;
        if (firstTarget!==oldPos) {
            const step=firstTarget>oldPos?1:-1;
            for (let pos=oldPos+step; step>0?pos<=firstTarget:pos>=firstTarget; pos+=step) {
                await movePlayerToken(playerId,pos,{animate:true,duration:290});
            }
        }
        if (effectType&&finalPos!==firstTarget) {
            await sleep(420);
            await movePlayerToken(playerId,finalPos,{animate:true,duration:980});
        }
    }

    document.querySelectorAll('#player-list li').forEach(li => {
        const pid=li.getAttribute('data-player-id');
        const ts=normalizeTokenStyle(li.getAttribute('data-token-style'));
        const posEl=document.getElementById(`pos-${pid}`);
        if (!posEl) return;
        const token=document.querySelector(`#cell-${posEl.innerText} .player-token[title="${li.getAttribute('data-player-name')}"]`);
        if (token) { token.setAttribute('data-token-style',ts); token.style.backgroundImage=`url('${tokenBasePath}/${ts}.png')`; token.setAttribute('data-player-id',pid); }
    });
    layoutAllTokens();
    drawBoardConnections();
    window.addEventListener('resize', drawBoardConnections);

    function startModalTimer(sec) {
        clearInterval(timerInterval);
        countdown=sec;
        modalTimer.innerText=countdown;
        timerInterval=setInterval(()=>{ countdown--; modalTimer.innerText=countdown; if(countdown<=0){clearInterval(timerInterval);questionModal.hide();} },1000);
    }

    nextQuestionBtn.addEventListener('click', function() {
        if (!hasRolledDice) { alert('Harap selesaikan lempar dadu terlebih dahulu!'); return; }
        if (gameFinished) { alert('Permainan sudah selesai. Silakan mulai permainan baru.'); return; }
        fetch(`/game/${gameId}/next-question`,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}})
        .then(r=>r.json()).then(data=>{
            currentQuestion=data.question; questionDisplayed=true;
            modalQuestionText.innerText=currentQuestion;
            startModalTimer(15); questionModal.show();
            showQuestionBtn.disabled=true; showQuestionBtn.innerText='Soal Ditampilkan';
            nextQuestionBtn.disabled=true; hasRolledDice=false;
            selectedPlayerId=null; selectedPlayerName=null;
            rollDiceBtn.disabled=true; rollDiceBtn.innerText='🎲 Acak Dadu';
            document.querySelectorAll('.select-player-btn').forEach(b=>b.style.display='none');
            playerListItems.forEach(i=>i.classList.remove('selected-player'));
        });
    });

    showQuestionBtn.addEventListener('click', function() {
        if (!currentQuestion) { alert('Soal belum tersedia. Silakan tekan Soal Selanjutnya dulu.'); return; }
        questionDisplayed=true; modalQuestionText.innerText=currentQuestion;
        startModalTimer(15); questionModal.show();
        showQuestionBtn.disabled=true; showQuestionBtn.innerText='Soal Ditampilkan';
    });

    document.getElementById('questionModal').addEventListener('hidden.bs.modal', function() {
        clearInterval(timerInterval); questionDisplayed=false;
        if (!hasRolledDice) {
            showQuestionBtn.disabled=false; showQuestionBtn.innerText='Tampilkan Soal';
            document.querySelectorAll('.select-player-btn').forEach(b=>b.style.display='inline-block');
        }
    });

    playerListItems.forEach(li => {
        li.querySelector('.select-player-btn').addEventListener('click', function() {
            selectedPlayerId=li.getAttribute('data-player-id');
            selectedPlayerName=li.getAttribute('data-player-name');
            playerListItems.forEach(i=>i.classList.remove('selected-player'));
            li.classList.add('selected-player');
            rollDiceBtn.disabled=false;
            rollDiceBtn.innerText=`🎲 Acak Dadu (${selectedPlayerName})`;
        });
    });

    rollDiceBtn.addEventListener('click', function() {
        if (!selectedPlayerId) { alert('Pilih pemain terlebih dahulu.'); return; }
        rollDiceBtn.disabled=true; rollDiceBtn.innerText='Mengacak...';
        document.querySelectorAll('.select-player-btn').forEach(b=>b.style.display='none');
        startDiceRoll();
        diceMessage.innerText=`Mengocok dadu untuk ${selectedPlayerName}...`;
        diceOkBtn.disabled=true; window.lastDiceData=null; diceModal.show();
        setTimeout(()=>{
            fetch(`/game/${gameId}/roll-dice/${selectedPlayerId}`,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}})
            .then(r=>r.json()).then(data=>{
                landDice(data.dice);
                let msg=`${data.playerName} mendapat dadu ${data.dice}. Pindah dari ${data.oldPos} ke ${data.newPos}.`;
                if (data.effect==='snake') msg+=`\n${data.playerName} terkena ular, turun ke ${data.effectEnd}.`;
                else if (data.effect==='ladder') msg+=`\n${data.playerName} mendapat tangga, naik ke ${data.effectEnd}.`;
                diceMessage.innerText=msg;
                window.lastDiceData=data; diceOkBtn.disabled=false;
            }).catch(()=>{
                diceCube.classList.remove('dice-rolling');
                diceMessage.innerText='Terjadi kesalahan. Silakan coba lagi.'; diceOkBtn.disabled=false;
            });
        },1450);
    });

    diceOkBtn.addEventListener('click', async function() {
        const data=window.lastDiceData; if (!data) return;
        const oldPos=Number(data.oldPos), dv=Number(data.dice), fp=Number(data.newPos), pid=Number(selectedPlayerId);
        await animatePlayerMovement(pid,oldPos,dv,fp,data.effect);
        if (data.gameFinished) {
            gameFinished=true; alert(`🎉 Selamat! ${data.winner} menang! 🎉`);
            nextQuestionBtn.disabled=true;
            document.querySelectorAll('.select-player-btn').forEach(b=>b.disabled=true);
            rollDiceBtn.disabled=true; showQuestionBtn.disabled=true;
            showPodium(); return;
        }
        nextQuestionBtn.disabled=false; showQuestionBtn.disabled=true;
        showQuestionBtn.innerText='Tampilkan Soal';
        currentQuestion=null; questionDisplayed=false; hasRolledDice=true;
        selectedPlayerId=null; selectedPlayerName=null;
        rollDiceBtn.disabled=true; rollDiceBtn.innerText='🎲 Acak Dadu';
        playerListItems.forEach(i=>i.classList.remove('selected-player'));
    });

    if (gameFinished) {
        nextQuestionBtn.disabled=true;
        document.querySelectorAll('.select-player-btn').forEach(b=>b.disabled=true);
        rollDiceBtn.disabled=true; showQuestionBtn.disabled=true;
    }
</script>
@endpush
