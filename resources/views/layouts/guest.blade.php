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
                --hse-red: {{ config('app.primary_color', '#C0392B') }};
                --hse-red-light: color-mix(in srgb, var(--hse-red) 80%, white);
                --hse-red-glow: color-mix(in srgb, var(--hse-red) 40%, transparent);
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
                background-image: linear-gradient(
                    color-mix(in srgb, var(--hse-red) 45%, transparent), 
                    color-mix(in srgb, color-mix(in srgb, var(--hse-red) 70%, black) 45%, transparent)
                ), url('{{ str_contains(config('app.login_bg', 'images/auth/welcome-bg.png'), 'images/auth') ? asset(config('app.login_bg', 'images/auth/welcome-bg.png')) : asset('storage/' . config('app.login_bg')) }}');
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

            /* ================================================ */
            /* CENTERED CARD STYLE (PORTAL GLASSMORPHISM)        */
            /* ================================================ */
            body.login-style-centered-card {
                overflow-y: auto !important;
            }
            body.login-style-centered-card .mesh-bg {
                background: url('{{ str_contains(config("app.login_bg", "images/auth/welcome-bg.png"), "images/auth") ? asset(config("app.login_bg", "images/auth/welcome-bg.png")) : asset("storage/" . config("app.login_bg")) }}') center/cover no-repeat !important;
                filter: blur(25px) brightness(0.55) saturate(1.3);
                transform: scale(1.15);
            }
            body.login-style-centered-card .grid-overlay { display: none !important; }
            body.login-style-centered-card .geo-shape { display: none !important; }
            body.login-style-centered-card .auth-brand-panel { display: none !important; }

            body.login-style-centered-card .auth-wrapper {
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 2rem;
            }
            body.login-style-centered-card .auth-form-panel {
                width: 100%;
                max-width: 480px;
                min-width: auto;
                background: rgba(255, 255, 255, 0.10) !important;
                backdrop-filter: blur(30px) saturate(170%) !important;
                -webkit-backdrop-filter: blur(30px) saturate(170%) !important;
                border: 1px solid rgba(255, 255, 255, 0.18) !important;
                border-radius: 28px !important;
                box-shadow: 0 30px 70px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255,255,255,0.05) inset !important;
                padding: 2.5rem 2.5rem 2rem !important;
            }

            /* Text overrides for dark glass */
            body.login-style-centered-card .portal-title { display: block !important; }
            body.login-style-centered-card .portal-illustration { display: block !important; }
            body.login-style-centered-card .status-bar { display: none !important; }
            body.login-style-centered-card .separator-line { display: none !important; }

            body.login-style-centered-card .form-header {
                text-align: center;
            }
            body.login-style-centered-card .form-header h2 {
                color: #ffffff !important;
                font-size: 1.45rem !important;
                text-shadow: 0 2px 12px rgba(0,0,0,0.2);
            }
            body.login-style-centered-card .form-header p {
                color: rgba(255,255,255,0.65) !important;
                font-size: 0.82rem !important;
            }
            body.login-style-centered-card .modern-input-group label {
                display: none !important;
            }
            body.login-style-centered-card .modern-input-wrapper {
                background: rgba(255, 255, 255, 0.08) !important;
                border: 1.5px solid rgba(255, 255, 255, 0.15) !important;
                border-radius: 12px !important;
            }
            body.login-style-centered-card .modern-input-wrapper:hover {
                border-color: rgba(255, 255, 255, 0.25) !important;
                background: rgba(255, 255, 255, 0.12) !important;
            }
            body.login-style-centered-card .modern-input-wrapper:focus-within {
                border-color: #818cf8 !important;
                box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.2) !important;
                background: rgba(255, 255, 255, 0.12) !important;
            }
            body.login-style-centered-card .modern-input-wrapper input {
                color: #ffffff !important;
                padding: 12px 14px 12px 0 !important;
            }
            body.login-style-centered-card .modern-input-wrapper input::placeholder {
                color: rgba(255, 255, 255, 0.4) !important;
            }
            body.login-style-centered-card .modern-input-wrapper .input-icon {
                color: rgba(255, 255, 255, 0.45) !important;
            }
            body.login-style-centered-card .modern-input-wrapper:focus-within .input-icon {
                color: #a78bfa !important;
            }
            body.login-style-centered-card .password-toggle-btn {
                color: rgba(255,255,255,0.4) !important;
            }
            body.login-style-centered-card .password-toggle-btn:hover {
                color: #a78bfa !important;
            }
            body.login-style-centered-card .btn-signin {
                background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%) !important;
                box-shadow: 0 6px 25px rgba(99, 102, 241, 0.4) !important;
                letter-spacing: 1px !important;
                border-radius: 12px !important;
                padding: 14px !important;
            }
            body.login-style-centered-card .btn-signin:hover {
                box-shadow: 0 10px 35px rgba(99, 102, 241, 0.5) !important;
            }
            body.login-style-centered-card .auth-options .form-check-label {
                color: rgba(255,255,255,0.6) !important;
            }
            body.login-style-centered-card .auth-options .form-check-input {
                background-color: rgba(255,255,255,0.08);
                border-color: rgba(255,255,255,0.2);
            }
            body.login-style-centered-card .auth-options .form-check-input:checked {
                background-color: #8b5cf6;
                border-color: #8b5cf6;
            }
            body.login-style-centered-card .auth-options a {
                color: #a78bfa !important;
            }
            body.login-style-centered-card .auth-options a:hover {
                color: #c4b5fd !important;
            }
            body.login-style-centered-card .auth-footer {
                border-top-color: rgba(255,255,255,0.08) !important;
                margin-top: 1.5rem !important;
                padding-top: 1rem !important;
            }
            body.login-style-centered-card .auth-footer span {
                color: rgba(255,255,255,0.4) !important;
            }
            body.login-style-centered-card .input-error {
                color: #fca5a5 !important;
            }

            /* Portal Title (only visible in centered-card) */
            .portal-title {
                display: none;
                text-align: center;
                margin-bottom: 0.25rem;
            }
            .portal-title h3 {
                font-family: 'Outfit', sans-serif;
                font-size: 1.15rem;
                font-weight: 400;
                color: rgba(255,255,255,0.9);
                letter-spacing: 0.5px;
            }
            .portal-title h3 strong {
                font-weight: 700;
                color: #ffffff;
            }

            /* Portal Illustration (only visible in centered-card) */
            .portal-illustration {
                display: none;
                text-align: center;
                margin: 0.75rem 0 0.5rem;
            }
            .portal-illustration img {
                max-width: 250px;
                width: 100%;
                height: auto;
                border-radius: 16px;
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
                border: 1px solid rgba(255, 255, 255, 0.22) !important;
                background: rgba(255, 255, 255, 0.95) !important;
                padding: 10px !important;
                transition: all 0.3s ease;
            }
            .portal-illustration img:hover {
                transform: translateY(-2px);
                box-shadow: 0 16px 35px rgba(0, 0, 0, 0.3);
            }

            /* Floating Icon Cards */
            .floating-icon-card {
                display: none;
                position: fixed;
                align-items: center;
                justify-content: center;
                width: 60px;
                height: 60px;
                background: rgba(255, 255, 255, 0.12) !important;
                backdrop-filter: blur(15px) !important;
                -webkit-backdrop-filter: blur(15px) !important;
                border: 1px solid rgba(255, 255, 255, 0.22) !important;
                border-radius: 16px !important;
                z-index: 5;
                pointer-events: none;
                transition: all 0.3s ease;
            }
            .floating-icon-card i {
                font-size: 1.6rem;
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                -webkit-background-clip: text !important;
                -webkit-text-fill-color: transparent !important;
                background-clip: text !important;
            }

            /* Custom Premium Colors and Shadows matching the screenshot */
            .floating-icon-card.icon-server {
                box-shadow: 0 10px 30px rgba(0, 242, 254, 0.22), 0 0 0 1px rgba(255,255,255,0.1) inset !important;
            }
            .floating-icon-card.icon-server i {
                background-image: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%) !important;
            }

            .floating-icon-card.icon-key {
                box-shadow: 0 10px 30px rgba(118, 75, 162, 0.22), 0 0 0 1px rgba(255,255,255,0.1) inset !important;
            }
            .floating-icon-card.icon-key i {
                background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            }

            .floating-icon-card.icon-database {
                box-shadow: 0 10px 30px rgba(245, 87, 108, 0.22), 0 0 0 1px rgba(255,255,255,0.1) inset !important;
            }
            .floating-icon-card.icon-database i {
                background-image: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
            }

            .floating-icon-card.icon-barcode {
                box-shadow: 0 10px 30px rgba(148, 163, 184, 0.18), 0 0 0 1px rgba(255,255,255,0.1) inset !important;
            }
            .floating-icon-card.icon-barcode i {
                background-image: linear-gradient(135deg, #cfd9df 0%, #e2ebf0 100%) !important;
            }

            .floating-icon-card.icon-map {
                box-shadow: 0 10px 30px rgba(253, 160, 133, 0.22), 0 0 0 1px rgba(255,255,255,0.1) inset !important;
            }
            .floating-icon-card.icon-map i {
                background-image: linear-gradient(135deg, #f6d365 0%, #fda085 100%) !important;
            }

            .floating-icon-card.icon-sync {
                box-shadow: 0 10px 30px rgba(132, 250, 176, 0.22), 0 0 0 1px rgba(255,255,255,0.1) inset !important;
            }
            .floating-icon-card.icon-sync i {
                background-image: linear-gradient(135deg, #8fd3f4 0%, #84fab0 100%) !important;
            }

            .floating-icon-card.icon-tools {
                box-shadow: 0 10px 30px rgba(56, 239, 125, 0.22), 0 0 0 1px rgba(255,255,255,0.1) inset !important;
            }
            .floating-icon-card.icon-tools i {
                background-image: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
            }

            .floating-icon-card.icon-laptop {
                box-shadow: 0 10px 30px rgba(142, 197, 252, 0.22), 0 0 0 1px rgba(255,255,255,0.1) inset !important;
            }
            .floating-icon-card.icon-laptop i {
                background-image: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%) !important;
            }

            .floating-icon-card.icon-cpu {
                box-shadow: 0 10px 30px rgba(255, 8, 68, 0.22), 0 0 0 1px rgba(255,255,255,0.1) inset !important;
            }
            .floating-icon-card.icon-cpu i {
                background-image: linear-gradient(135deg, #ff0844 0%, #ffb199 100%) !important;
            }

            .floating-icon-card.icon-shield {
                box-shadow: 0 10px 30px rgba(109, 213, 237, 0.22), 0 0 0 1px rgba(255,255,255,0.1) inset !important;
            }
            .floating-icon-card.icon-shield i {
                background-image: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%) !important;
            }

            body.login-style-centered-card .floating-icon-card {
                display: flex;
            }

            .floating-icons-container .floating-icon-card:nth-child(1) { top: 10%; left: 8%; animation: floatPath1 16s ease-in-out infinite; }
            .floating-icons-container .floating-icon-card:nth-child(2) { top: 28%; left: 4%; animation: floatPath2 18s ease-in-out infinite; }
            .floating-icons-container .floating-icon-card:nth-child(3) { top: 48%; left: 9%; animation: floatPath3 17s ease-in-out infinite; }
            .floating-icons-container .floating-icon-card:nth-child(4) { top: 66%; left: 5%; animation: floatPath4 15s ease-in-out infinite; }
            .floating-icons-container .floating-icon-card:nth-child(5) { top: 84%; left: 11%; animation: floatPath1 20s ease-in-out infinite; }

            .floating-icons-container .floating-icon-card:nth-child(6) { top: 12%; right: 8%; left: auto; animation: floatPath3 14s ease-in-out infinite; }
            .floating-icons-container .floating-icon-card:nth-child(7) { top: 30%; right: 4%; left: auto; animation: floatPath4 19s ease-in-out infinite; }
            .floating-icons-container .floating-icon-card:nth-child(8) { top: 50%; right: 10%; left: auto; animation: floatPath1 15s ease-in-out infinite; }
            .floating-icons-container .floating-icon-card:nth-child(9) { top: 68%; right: 6%; left: auto; animation: floatPath2 16s ease-in-out infinite; }
            .floating-icons-container .floating-icon-card:nth-child(10) { top: 86%; right: 11%; left: auto; animation: floatPath3 18s ease-in-out infinite; }

            @keyframes floatPath1 {
                0% { transform: translate(0, 0) rotate(0deg); }
                33% { transform: translate(15px, -18px) rotate(4deg); }
                66% { transform: translate(-12px, 12px) rotate(-3deg); }
                100% { transform: translate(0, 0) rotate(0deg); }
            }

            @keyframes floatPath2 {
                0% { transform: translate(0, 0) rotate(0deg); }
                50% { transform: translate(-18px, -12px) rotate(-6deg); }
                100% { transform: translate(0, 0) rotate(0deg); }
            }

            @keyframes floatPath3 {
                0% { transform: translate(0, 0) rotate(0deg); }
                40% { transform: translate(16px, 10px) rotate(5deg); }
                80% { transform: translate(-10px, -16px) rotate(-4deg); }
                100% { transform: translate(0, 0) rotate(0deg); }
            }

            @keyframes floatPath4 {
                0% { transform: translate(0, 0) rotate(0deg); }
                50% { transform: translate(10px, -20px) rotate(3deg); }
                100% { transform: translate(0, 0) rotate(0deg); }
            }

            /* Centered-card responsive */
            @media (max-width: 991px) {
                body.login-style-centered-card .floating-icon-card { display: none !important; }
                body.login-style-centered-card .auth-form-panel {
                    padding: 2rem 1.5rem 1.5rem !important;
                }
            }
        </style>
    </head>
    <body class="auth-page login-style-{{ config('app.login_style', 'split') }}">
        <!-- Background layers -->
        <div class="mesh-bg"></div>
        <div class="grid-overlay"></div>

        <!-- Floating geometric shapes (split style) -->
        <div class="geo-shape"></div>
        <div class="geo-shape"></div>
        <div class="geo-shape"></div>
        <div class="geo-shape"></div>
        <div class="geo-shape"></div>

        <div class="floating-icons-container">
            <!-- Left Side Icons -->
            <div class="floating-icon-card icon-server"><i class="bi bi-hdd-rack"></i></div>
            <div class="floating-icon-card icon-key"><i class="bi bi-key"></i></div>
            <div class="floating-icon-card icon-tools"><i class="bi bi-tools"></i></div>
            <div class="floating-icon-card icon-database"><i class="bi bi-database"></i></div>
            <div class="floating-icon-card icon-laptop"><i class="bi bi-laptop"></i></div>

            <!-- Right Side Icons -->
            <div class="floating-icon-card icon-barcode"><i class="bi bi-qr-code-scan"></i></div>
            <div class="floating-icon-card icon-map"><i class="bi bi-geo-alt"></i></div>
            <div class="floating-icon-card icon-cpu"><i class="bi bi-cpu"></i></div>
            <div class="floating-icon-card icon-sync"><i class="bi bi-arrow-repeat"></i></div>
            <div class="floating-icon-card icon-shield"><i class="bi bi-shield-check"></i></div>
        </div>

        <div class="auth-wrapper">
            <!-- Left: Branding Panel (PREMIUM RED) -->
            <div class="auth-brand-panel">
                <div class="brand-glow"></div>

                <div class="brand-logo-wrapper">
                    <!-- Updated SVG logo to use White fill -->
                    <x-application-logo style="width: 140px; height: 140px; fill: #ffffff;" />
                    <div class="brand-title">{{ config('app.name', 'HSE ASSET') }}</div>
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
                    <span>{{ config('app.copyright_text', '© ' . date('Y') . ' HSE Guard Corp — v1.0') }}</span>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
