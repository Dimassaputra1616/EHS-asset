<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HSE Asset Management') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <!-- PWA Meta Tags & Manifest -->
        <meta name="theme-color" content="#C0392B">
        <link rel="apple-touch-icon" href="{{ asset('icon-192x192.png') }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --hse-red: #C0392B;
                --hse-red-light: #e74c3c;
                --hse-red-glow: rgba(192, 57, 43, 0.4);
                --light-bg: #f9fafb;
                --text-dark: #111827;
                --text-gray: #6b7280;
                --border-light: #e5e7eb;
            }

            * { margin: 0; padding: 0; box-sizing: border-box; }

            body.auth-page {
                font-family: 'Inter', sans-serif;
                background: var(--light-bg);
                min-height: 100vh;
                overflow: hidden;
                color: var(--text-dark);
            }

            /* ===== ANIMATED LIGHT MESH GRADIENT BACKGROUND ===== */
            .mesh-bg {
                position: fixed;
                inset: 0;
                z-index: 0;
                background:
                    radial-gradient(ellipse at 20% 50%, rgba(192,57,43,0.06) 0%, transparent 50%),
                    radial-gradient(ellipse at 80% 20%, rgba(192,57,43,0.03) 0%, transparent 50%),
                    radial-gradient(ellipse at 60% 80%, rgba(192,57,43,0.04) 0%, transparent 50%),
                    var(--light-bg);
            }

            /* ===== FLOATING GEOMETRIC SHAPES ===== */
            .geo-shape {
                position: absolute;
                border: 1px solid rgba(192,57,43,0.15);
                border-radius: 4px;
                animation: float-shape 20s ease-in-out infinite;
                z-index: 1;
                pointer-events: none;
            }
            .geo-shape:nth-child(1) { width: 80px; height: 80px; top: 10%; left: 5%; animation-delay: 0s; transform: rotate(45deg); }
            .geo-shape:nth-child(2) { width: 120px; height: 120px; top: 60%; left: 85%; animation-delay: -5s; border-radius: 50%; border-color: rgba(192,57,43,0.1); }
            .geo-shape:nth-child(3) { width: 60px; height: 60px; top: 80%; left: 15%; animation-delay: -10s; transform: rotate(30deg); }
            .geo-shape:nth-child(4) { width: 40px; height: 40px; top: 20%; left: 75%; animation-delay: -7s; border-radius: 50%; }
            .geo-shape:nth-child(5) { width: 100px; height: 100px; top: 45%; left: 90%; animation-delay: -3s; transform: rotate(60deg); border-color: rgba(192,57,43,0.08); }

            @keyframes float-shape {
                0%, 100% { transform: translateY(0) rotate(45deg); opacity: 0.5; }
                25% { transform: translateY(-30px) rotate(90deg); opacity: 0.8; }
                50% { transform: translateY(-15px) rotate(135deg); opacity: 0.5; }
                75% { transform: translateY(-40px) rotate(180deg); opacity: 0.7; }
            }

            /* ===== GRID LINES OVERLAY ===== */
            .grid-overlay {
                position: fixed;
                inset: 0;
                z-index: 1;
                background-image:
                    linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);
                background-size: 60px 60px;
                pointer-events: none;
            }

            /* ===== SPLIT LAYOUT ===== */
            .auth-wrapper {
                display: flex;
                min-height: 100vh;
                position: relative;
                z-index: 10;
            }

            /* Left Panel - Branding (PREMIUM RED) */
            .auth-brand-panel {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 3rem;
                position: relative;
                overflow: hidden;
                background-image: linear-gradient(rgba(192, 57, 43, 0.4), rgba(100, 20, 14, 0.45)), url('/images/auth/welcome-bg.png');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                color: white;
            }

            .brand-glow {
                position: absolute;
                width: 300px;
                height: 300px;
                background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
                border-radius: 50%;
                filter: blur(40px);
                animation: pulse-glow 4s ease-in-out infinite;
            }

            @keyframes pulse-glow {
                0%, 100% { opacity: 0.6; transform: scale(1); }
                50% { opacity: 1; transform: scale(1.1); }
            }

            .brand-logo-wrapper {
                position: relative;
                z-index: 2;
                text-align: center;
            }

            .brand-logo-wrapper svg {
                filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
                animation: logo-float 6s ease-in-out infinite;
            }

            @keyframes logo-float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }

            .brand-title {
                font-family: 'Outfit', sans-serif;
                font-weight: 800;
                font-size: 2.2rem;
                letter-spacing: 4px;
                color: #ffffff;
                text-shadow: 0 4px 15px rgba(0,0,0,0.2);
                margin-top: 1.5rem;
            }

            .brand-subtitle {
                font-family: 'JetBrains Mono', monospace;
                font-size: 0.7rem;
                letter-spacing: 6px;
                color: rgba(255,255,255,0.8);
                text-transform: uppercase;
                margin-top: 0.5rem;
            }

            .brand-features {
                margin-top: 3rem;
                position: relative;
                z-index: 2;
                background: rgba(255,255,255,0.1);
                padding: 2rem;
                border-radius: 20px;
                border: 1px solid rgba(255,255,255,0.2);
                backdrop-filter: blur(10px);
            }

            .brand-feature-item {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 1.2rem;
                color: rgba(255,255,255,0.95);
                font-size: 0.9rem;
                font-weight: 500;
            }

            .brand-feature-item:last-child {
                margin-bottom: 0;
            }

            .brand-feature-item i {
                color: var(--hse-red);
                font-size: 1.1rem;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #ffffff;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }

            /* Right Panel - Form (CLEAN LIGHT) */
            .auth-form-panel {
                width: 480px;
                min-width: 480px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 3rem;
                position: relative;
                background: #ffffff;
                box-shadow: -10px 0 40px rgba(0,0,0,0.06);
            }

            .form-header h2 {
                font-family: 'Outfit', sans-serif;
                font-weight: 700;
                font-size: 1.8rem;
                color: var(--text-dark);
            }

            .form-header p {
                color: var(--text-gray);
                font-size: 0.9rem;
                margin-top: 0.5rem;
            }

            /* ===== FORM INPUTS ===== */
            .modern-input-group {
                position: relative;
                margin-bottom: 1.5rem;
            }

            .modern-input-group label {
                font-family: 'Outfit', sans-serif;
                font-weight: 600;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 1.2px;
                color: #4b5563;
                margin-bottom: 8px;
                display: block;
            }

            .modern-input-wrapper {
                position: relative;
                display: flex;
                align-items: center;
                background: #f9fafb;
                border: 1px solid var(--border-light);
                border-radius: 14px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                overflow: hidden;
            }

            .modern-input-wrapper:hover {
                border-color: #d1d5db;
                background: #fff;
            }

            .modern-input-wrapper:focus-within {
                border-color: var(--hse-red);
                background: #fff;
                box-shadow: 0 0 0 4px rgba(192, 57, 43, 0.1);
            }

            .modern-input-wrapper .input-icon {
                padding: 0 16px;
                color: #9ca3af;
                font-size: 1.1rem;
                transition: color 0.3s ease;
            }

            .modern-input-wrapper:focus-within .input-icon {
                color: var(--hse-red);
            }

            .modern-input-wrapper input {
                flex: 1;
                background: transparent;
                border: none;
                outline: none;
                color: var(--text-dark);
                padding: 14px 16px 14px 0;
                font-family: 'Inter', sans-serif;
                font-size: 0.95rem;
                font-weight: 500;
            }

            .modern-input-wrapper input::placeholder {
                color: #9ca3af;
                font-weight: 400;
            }

            /* ===== SUBMIT BUTTON ===== */
            .btn-signin {
                width: 100%;
                padding: 16px;
                border: none;
                border-radius: 14px;
                font-family: 'Outfit', sans-serif;
                font-weight: 700;
                font-size: 0.9rem;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                color: #fff;
                background: linear-gradient(135deg, var(--hse-red) 0%, var(--hse-red-light) 100%);
                cursor: pointer;
                position: relative;
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 4px 15px rgba(192, 57, 43, 0.3);
            }

            .btn-signin:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(192, 57, 43, 0.4);
            }

            .btn-signin:active {
                transform: translateY(-1px);
            }

            .btn-signin::after {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
                transform: rotate(45deg) translateX(-100%);
                transition: transform 0.6s ease;
            }

            .btn-signin:hover::after {
                transform: rotate(45deg) translateX(100%);
            }

            /* ===== Remember & Forgot ===== */
            .auth-options {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2rem;
            }

            .auth-options .form-check-input {
                background-color: #f3f4f6;
                border-color: #d1d5db;
                border-radius: 4px;
                cursor: pointer;
            }

            .auth-options .form-check-input:checked {
                background-color: var(--hse-red);
                border-color: var(--hse-red);
            }

            .auth-options .form-check-label {
                color: var(--text-gray);
                font-size: 0.85rem;
                font-weight: 500;
                cursor: pointer;
            }

            .auth-options a {
                color: var(--hse-red);
                font-size: 0.85rem;
                font-weight: 600;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .auth-options a:hover {
                color: var(--dark-1);
            }

            /* ===== FOOTER ===== */
            .auth-footer {
                margin-top: 2.5rem;
                text-align: center;
                padding-top: 1.5rem;
                border-top: 1px solid var(--border-light);
            }

            .auth-footer span {
                color: #9ca3af;
                font-family: 'JetBrains Mono', monospace;
                font-size: 0.7rem;
                letter-spacing: 1px;
            }

            /* ===== SEPARATOR ===== */
            .separator-line {
                display: flex;
                align-items: center;
                gap: 16px;
                margin: 2rem 0;
            }

            .separator-line::before,
            .separator-line::after {
                content: '';
                flex: 1;
                height: 1px;
                background: var(--border-light);
            }

            .separator-line span {
                font-size: 0.7rem;
                color: #9ca3af;
                text-transform: uppercase;
                letter-spacing: 2px;
                font-family: 'JetBrains Mono', monospace;
                font-weight: 500;
            }

            /* ===== STATUS INDICATORS ===== */
            .status-bar {
                display: flex;
                gap: 8px;
                margin-bottom: 2rem;
            }

            .status-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #e5e7eb;
            }

            .status-dot.active {
                background: var(--hse-red);
                box-shadow: 0 0 8px rgba(192, 57, 43, 0.4);
                animation: status-pulse 2s ease-in-out infinite;
            }

            @keyframes status-pulse {
                0%, 100% { box-shadow: 0 0 8px rgba(192, 57, 43, 0.4); }
                50% { box-shadow: 0 0 16px rgba(192, 57, 43, 0.6); }
            }

            /* ===== ANIMATIONS ===== */
            .fade-in-up {
                animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
                opacity: 0;
            }

            .fade-in-up:nth-child(1) { animation-delay: 0.1s; }
            .fade-in-up:nth-child(2) { animation-delay: 0.2s; }
            .fade-in-up:nth-child(3) { animation-delay: 0.3s; }
            .fade-in-up:nth-child(4) { animation-delay: 0.4s; }
            .fade-in-up:nth-child(5) { animation-delay: 0.5s; }
            .fade-in-up:nth-child(6) { animation-delay: 0.6s; }

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* ===== ERROR STYLES ===== */
            .input-error {
                color: var(--hse-red);
                font-size: 0.75rem;
                margin-top: 6px;
                display: flex;
                align-items: center;
                gap: 4px;
                font-weight: 500;
            }

            /* ===== RESPONSIVE ===== */
            @media (max-width: 991px) {
                .auth-brand-panel { display: none; }
                .auth-form-panel {
                    width: 100%;
                    min-width: unset;
                    border-left: none;
                    max-width: 480px;
                    margin: 0 auto;
                    box-shadow: none;
                    background: transparent;
                }
                .auth-wrapper { justify-content: center; }
            }
        </style>
    </head>
    <body class="auth-page">
        <!-- Background layers -->
        <div class="mesh-bg"></div>
        <div class="grid-overlay"></div>

        <!-- Floating geometric shapes -->
        <div class="geo-shape"></div>
        <div class="geo-shape"></div>
        <div class="geo-shape"></div>
        <div class="geo-shape"></div>
        <div class="geo-shape"></div>

        <div class="auth-wrapper">
            <!-- Left: Branding Panel (PREMIUM RED) -->
            <div class="auth-brand-panel">
                <div class="brand-glow"></div>

                <div class="brand-logo-wrapper">
                    <!-- Updated SVG logo to use White fill -->
                    <x-application-logo style="width: 140px; height: 140px; fill: #ffffff;" />
                    <div class="brand-title">HSE ASSET</div>
                    <div class="brand-subtitle">Enterprise Management</div>
                </div>

                <div class="brand-features">
                    <div class="brand-feature-item">
                        <i class="bi bi-shield-check"></i>
                        <span>Role-Based Access Control</span>
                    </div>
                    <div class="brand-feature-item">
                        <i class="bi bi-box-seam"></i>
                        <span>Real-Time Asset Tracking</span>
                    </div>
                    <div class="brand-feature-item">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Advanced Analytics Dashboard</span>
                    </div>
                    <div class="brand-feature-item">
                        <i class="bi bi-gear"></i>
                        <span>Configurable Master Data</span>
                    </div>
                </div>
            </div>

            <!-- Right: Form Panel (CLEAN LIGHT) -->
            <div class="auth-form-panel">
                {{ $slot }}

                <div class="auth-footer fade-in-up">
                    <span>&copy; {{ date('Y') }} HSE Guard Corp &mdash; v1.0</span>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
