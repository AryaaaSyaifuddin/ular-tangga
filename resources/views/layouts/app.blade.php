<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ular Tangga Edukasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .grid-item {
            width: 50px;
            height: 50px;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            font-size: 12px;
        }
        .player-token {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            position: absolute;
            bottom: 2px;
            right: 2px;
            border: 1px solid white;
        }
        .snake-ladder-info {
            font-size: 10px;
            color: red;
        }

    .grid-item { width: 50px; height: 50px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; position: relative; }
    .player-token { width: 20px; height: 20px; border-radius: 50%; position: absolute; bottom: 2px; right: 2px; }
    
    /* Style untuk podium */
    .podium-place {
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
        padding: 15px;
    }
    
    .podium-place h2 {
        font-size: 48px;
        margin-bottom: 10px;
    }
    
    .podium-place h4 {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .podium-place:nth-child(2) {
        background: linear-gradient(145deg, #ffd700, #f7b600);
        border: 2px solid #b8860b;
    }
    
    .podium-place:nth-child(1) {
        background: linear-gradient(145deg, #c0c0c0, #a0a0a0);
        border: 2px solid #808080;
    }
    
    .podium-place:nth-child(3) {
        background: linear-gradient(145deg, #cd7f32, #b06e2b);
        border: 2px solid #8b4513;
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