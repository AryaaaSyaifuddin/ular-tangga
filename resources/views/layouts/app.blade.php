<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ular Tangga Edukasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* =============================================
           GLOBAL RED PALETTE shared across all views
        ============================================= */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --crimson:        #B91C1C;
            --crimson-dark:   #7F1D1D;
            --crimson-deeper: #450A0A;
            --crimson-mid:    #991B1B;
            --crimson-light:  #EF4444;
            --crimson-soft:   #FEE2E2;
            --crimson-subtle: #FFF1F1;
            --white:          #FFFFFF;
            --off-white:      #FDF6F6;
            --gray-200:       #E5E7EB;
            --gray-500:       #6B7280;
            --gray-700:       #374151;
            --gold:           #D97706;
            --gold-light:     #F59E0B;
            --shadow-sm:      0 2px 6px rgba(127,29,29,0.10);
            --shadow-md:      0 6px 20px rgba(127,29,29,0.18);
            --shadow-lg:      0 16px 40px rgba(127,29,29,0.25);
            --font-display:   'Playfair Display', Georgia, serif;
            --font-body:      'Inter', system-ui, sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background: var(--off-white);
            color: var(--gray-700);
            min-height: 100vh;
        }

        .bg-crimson        { background: linear-gradient(135deg, var(--crimson-dark) 0%, var(--crimson) 60%, var(--crimson-light) 100%) !important; }
        .bg-soft-crimson   { background-color: var(--crimson-soft); }
        .text-crimson      { color: var(--crimson) !important; }
        .border-crimson    { border-color: var(--crimson) !important; }

        .btn-crimson {
            background: linear-gradient(135deg, var(--crimson-dark) 0%, var(--crimson) 100%);
            border: none; color: white; font-weight: 600;
            transition: all 0.3s ease; position: relative; z-index: 1; overflow: hidden;
        }
        .btn-crimson::before {
            content: ''; position: absolute; top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, var(--crimson-light) 0%, var(--crimson) 100%);
            transition: left 0.45s ease; z-index: -1;
        }
        .btn-crimson:hover::before { left: 0; }
        .btn-crimson:hover { color: white; transform: translateY(-2px); box-shadow: var(--shadow-md); }

        .btn-outline-crimson {
            border: 2px solid var(--crimson); color: var(--crimson);
            background: transparent; font-weight: 600; transition: all 0.3s ease;
        }
        .btn-outline-crimson:hover {
            background: linear-gradient(135deg, var(--crimson-dark) 0%, var(--crimson) 100%);
            color: white; border-color: transparent;
            transform: translateY(-2px); box-shadow: var(--shadow-md);
        }

        /* Progress Steps */
        .setup-progress { position: relative; }
        .step { text-align: center; position: relative; z-index: 1; }
        .step-circle {
            width: 40px; height: 40px; background: var(--gray-200); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: var(--gray-500); margin: 0 auto 8px; transition: all 0.3s ease;
        }
        .step.completed .step-circle,
        .step.active    .step-circle {
            background: linear-gradient(135deg, var(--crimson-dark) 0%, var(--crimson) 100%);
            color: white;
        }
        .step.active .step-circle { box-shadow: 0 4px 14px rgba(185,28,28,0.45); }
        .step-label { font-size: 0.8rem; color: var(--gray-500); font-weight: 500; }
        .step.active .step-label    { color: var(--crimson); font-weight: 700; }
        .step.completed .step-label { color: var(--crimson-mid); }
        .step-line {
            flex: 1; height: 2px; background: var(--gray-200);
            margin: 0 10px; margin-top: -10px;
        }
        .step-line.active { background: linear-gradient(90deg, var(--crimson-dark), var(--crimson)); }

        /* Card */
        .card {
            border: none !important; border-radius: 20px !important;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg) !important; }
        .card-header.crimson-header {
            background: linear-gradient(135deg, var(--crimson-dark) 0%, var(--crimson) 60%, var(--crimson-light) 100%);
            color: white; border-radius: 20px 20px 0 0 !important; padding: 1.4rem 1.6rem;
        }

        /* Form */
        .form-control, .form-select {
            border: 1.5px solid var(--gray-200); border-radius: 10px;
            padding: 0.7rem 1rem; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--crimson);
            box-shadow: 0 0 0 0.18rem rgba(185,28,28,0.18); outline: none;
        }

        .alert-crimson-info {
            background: white; border-left: 4px solid var(--crimson);
            border-radius: 12px; box-shadow: var(--shadow-sm);
        }

        .dice-decoration i { opacity: 0.45; color: var(--crimson); transition: all 0.3s ease; }
        .dice-decoration i:hover {
            opacity: 1; transform: rotate(180deg) scale(1.3); color: var(--crimson-light);
        }

        .grid-item {
            width: 50px; height: 50px;
            border: 1px solid rgba(255,255,255,0.12);
            display: flex; align-items: center; justify-content: center;
            position: relative; font-size: 12px;
        }

        .podium-place {
            min-height: 150px; display: flex; flex-direction: column;
            justify-content: flex-end; align-items: center; padding: 15px;
        }
        .podium-place h2 { font-size: 48px; margin-bottom: 10px; }
        .podium-place h4 { font-weight: bold; margin-bottom: 5px; }
        .podium-place:nth-child(2) { background: linear-gradient(145deg, #ffd700, #f7b600); border: 2px solid #b8860b; }
        .podium-place:nth-child(1) { background: linear-gradient(145deg, #c0c0c0, #a0a0a0); border: 2px solid #808080; }
        .podium-place:nth-child(3) { background: linear-gradient(145deg, #cd7f32, #b06e2b); border: 2px solid #8b4513; }

        @media (max-width: 768px) {
            .step-circle { width: 30px; height: 30px; font-size: 0.85rem; }
            .step-label  { font-size: 0.7rem; }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
