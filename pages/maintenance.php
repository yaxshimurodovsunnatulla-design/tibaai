<?php
/**
 * Tiba AI — Texnik ishlar sahifasi
 * Bu sahifa maintenance rejim yoqilganda barcha foydalanuvchilarga ko'rsatiladi
 * Admin sessiyasi bor foydalanuvchilar o'tib ketadi
 */
http_response_code(503);
header('Content-Type: text/html; charset=UTF-8');
header('Retry-After: 3600');
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Texnik ishlar — Tiba AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #07070d;
            color: #fff;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated gradient background */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 40%, rgba(99,102,241,0.08) 0%, transparent 50%),
                        radial-gradient(circle at 70% 60%, rgba(168,85,247,0.06) 0%, transparent 50%),
                        radial-gradient(circle at 50% 80%, rgba(236,72,153,0.04) 0%, transparent 50%);
            animation: bgFloat 20s ease-in-out infinite;
        }

        @keyframes bgFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -20px) rotate(1deg); }
            66% { transform: translate(-20px, 15px) rotate(-1deg); }
        }

        .container {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 2rem;
            max-width: 540px;
        }

        /* Gear animation */
        .gear-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 2.5rem;
        }
        .gear {
            font-size: 5rem;
            display: block;
            animation: gearSpin 8s linear infinite;
            filter: drop-shadow(0 0 20px rgba(99,102,241,0.3));
        }
        @keyframes gearSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Pulsing ring */
        .gear-container::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 140px;
            height: 140px;
            transform: translate(-50%, -50%);
            border: 2px solid rgba(99,102,241,0.15);
            border-radius: 50%;
            animation: ringPulse 3s ease-in-out infinite;
        }
        @keyframes ringPulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            50% { transform: translate(-50%, -50%) scale(1.15); opacity: 0.3; }
        }

        h1 {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, #818cf8, #a78bfa, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .subtitle {
            font-size: 1.05rem;
            color: #9ca3af;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Safety badge */
        .safety-card {
            background: rgba(16,185,129,0.06);
            border: 1px solid rgba(16,185,129,0.15);
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
        }
        .safety-card .icon { font-size: 1.5rem; margin-bottom: 0.5rem; display: block; }
        .safety-card h3 {
            color: #34d399;
            font-size: 0.875rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .safety-card p {
            color: #6ee7b7;
            font-size: 0.75rem;
            opacity: 0.8;
        }

        /* Info cards */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }
        .info-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 0.75rem;
            padding: 1rem;
        }
        .info-item .ii-icon { font-size: 1.25rem; margin-bottom: 0.5rem; display: block; }
        .info-item .ii-label { font-size: 0.65rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.1em; }
        .info-item .ii-value { font-size: 0.8rem; color: #d1d5db; font-weight: 600; margin-top: 0.15rem; }

        /* Footer */
        .footer-text {
            font-size: 0.7rem;
            color: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .dot {
            width: 6px;
            height: 6px;
            background: #6366f1;
            border-radius: 50%;
            animation: dotPulse 2s ease-in-out infinite;
        }
        @keyframes dotPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(0.6); }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(99,102,241,0.3);
            border-radius: 50%;
            animation: particleFloat 15s linear infinite;
        }
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 18s; }
        .particle:nth-child(2) { left: 25%; animation-delay: 3s; animation-duration: 22s; }
        .particle:nth-child(3) { left: 50%; animation-delay: 7s; animation-duration: 16s; }
        .particle:nth-child(4) { left: 75%; animation-delay: 5s; animation-duration: 20s; }
        .particle:nth-child(5) { left: 90%; animation-delay: 2s; animation-duration: 25s; }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; transform: translateY(90vh) scale(1); }
            90% { opacity: 0.5; }
            100% { transform: translateY(-10vh) scale(0); opacity: 0; }
        }

        @media (max-width: 480px) {
            h1 { font-size: 1.5rem; }
            .subtitle { font-size: 0.9rem; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Particles -->
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>

    <div class="container">
        <!-- Spinning gear -->
        <div class="gear-container">
            <span class="gear">⚙️</span>
        </div>

        <h1>Texnik ishlar olib borilmoqda</h1>
        <p class="subtitle">
            Platformamizni yanada yaxshilash uchun texnik ishlar olib borilmoqda. 
            Tez orada qaytamiz!
        </p>

        <!-- Safety assurance -->
        <div class="safety-card">
            <span class="icon">🛡️</span>
            <h3>Tangalaringiz va to'lovlaringiz xavfsiz!</h3>
            <p>Barcha ma'lumotlar himoyalangan. Texnik ishlar tugagach, hamma narsa avvalgidek ishlaydi.</p>
        </div>

        <!-- Info cards -->
        <div class="info-grid">
            <div class="info-item">
                <span class="ii-icon">💰</span>
                <div class="ii-label">Balans</div>
                <div class="ii-value">Saqlanmoqda</div>
            </div>
            <div class="info-item">
                <span class="ii-icon">🎨</span>
                <div class="ii-label">Rasmlar</div>
                <div class="ii-value">Xavfsiz</div>
            </div>
            <div class="info-item">
                <span class="ii-icon">💳</span>
                <div class="ii-label">To'lovlar</div>
                <div class="ii-value">Himoyalangan</div>
            </div>
            <div class="info-item">
                <span class="ii-icon">⏱️</span>
                <div class="ii-label">Kutish vaqti</div>
                <div class="ii-value">Tez kunda</div>
            </div>
        </div>

        <div class="footer-text">
            <span class="dot"></span>
            Tiba AI jamoasi ishlamoqda
            <span class="dot" style="animation-delay: 0.3s"></span>
        </div>
    </div>
</body>
</html>
