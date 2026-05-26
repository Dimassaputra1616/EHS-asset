<style>
    .modern-input-wrapper {
        border: 1.5px solid #e5e7eb !important;
        border-radius: 12px !important;
        background: #fcfcfc !important;
        transition: all 0.2s ease-in-out !important;
    }
    .modern-input-wrapper:hover {
        border-color: #cbd5e1 !important;
    }
    .modern-input-wrapper:focus-within {
        border-color: var(--hse-red) !important;
        box-shadow: 0 0 0 4px rgba(192, 57, 43, 0.12) !important;
        background: #ffffff !important;
    }
    .modern-input-wrapper input {
        font-family: 'Outfit', sans-serif !important;
        font-size: 0.95rem !important;
        font-weight: 500 !important;
        letter-spacing: 0.5px !important;
        color: var(--text-dark) !important;
    }
    .modern-input-wrapper input::placeholder {
        font-family: 'Outfit', sans-serif !important;
        color: #94a3b8 !important;
        font-weight: 400 !important;
        letter-spacing: 0.5px !important;
    }
    .password-toggle-btn {
        background: transparent;
        border: none;
        outline: none;
        color: #9ca3af;
        cursor: pointer;
        padding: 0 16px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .password-toggle-btn:hover {
        color: var(--hse-red);
        transform: scale(1.1);
    }
</style>

<x-guest-layout>
    <!-- Status bar dots -->
    <div class="status-bar fade-in-up">
        <div class="status-dot active"></div>
        <div class="status-dot"></div>
        <div class="status-dot"></div>
    </div>

    <div class="form-header fade-in-up">
        <h2>Welcome Back</h2>
        <p>Sign in to your HSE Asset Management portal</p>
    </div>

    <div class="separator-line fade-in-up">
        <span>authorized access</span>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3 text-success small fw-bold" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="modern-input-group fade-in-up">
            <label>Email Address</label>
            <div class="modern-input-wrapper">
                <span class="input-icon"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@hse.com">
            </div>
            @error('email')
                <div class="input-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="modern-input-group fade-in-up">
            <label>Password</label>
            <div class="modern-input-wrapper">
                <span class="input-icon"><i class="bi bi-shield-lock"></i></span>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility()">
                    <i class="bi bi-eye" id="toggle-icon"></i>
                </button>
            </div>
            @error('password')
                <div class="input-error"><i class="bi bi-exclamation-circle-fill"></i> {{ $message }}</div>
            @enderror
        </div>

        <script>
            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('password');
                const toggleIcon = document.getElementById('toggle-icon');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                }
            }
        </script>

        <!-- Remember & Forgot -->
        <div class="auth-options fade-in-up">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">Keep me signed in</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <!-- Submit -->
        <div class="fade-in-up">
            <button type="submit" class="btn-signin">
                SECURE LOGIN <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
