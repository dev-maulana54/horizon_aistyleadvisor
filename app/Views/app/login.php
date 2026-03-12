<!doctype html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth Portal</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/notify_ai.css') ?>">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .auth-container {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(-30px);
            }
        }

        .slide-in {
            animation: slideIn 0.4s ease-out forwards;
        }

        .slide-out {
            animation: slideOut 0.3s ease-out forwards;
        }

        .input-group {
            position: relative;
        }

        .input-group input {
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            transform: translateY(-2px);
        }

        .input-icon {
            transition: all 0.3s ease;
        }

        .input-group input:focus+.input-icon,
        .input-group input:focus~.input-icon {
            color: var(--primary-color);
        }

        .btn-primary {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(128, 0, 32, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::after {
            width: 300px;
            height: 300px;
        }

        .password-toggle {
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            transform: scale(1.1);
        }

        .error-message {
            animation: shake 0.4s ease-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .success-message {
            animation: bounceIn 0.5s ease-out;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .link-hover {
            position: relative;
        }

        .link-hover::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: currentColor;
            transition: width 0.3s ease;
        }

        .link-hover:hover::after {
            width: 100%;
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 20s infinite ease-in-out;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -50px;
            animation-delay: -5s;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            top: 40%;
            right: 10%;
            animation-delay: -10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            25% {
                transform: translate(10px, -10px) rotate(5deg);
            }

            50% {
                transform: translate(-5px, 15px) rotate(-5deg);
            }

            75% {
                transform: translate(-15px, -5px) rotate(3deg);
            }
        }

        @keyframes orbitSlow {
            0% {
                transform: rotate(0deg) translateX(80px) rotate(0deg);
            }

            100% {
                transform: rotate(360deg) translateX(80px) rotate(-360deg);
            }
        }

        @keyframes orbitFast {
            0% {
                transform: rotate(0deg) translateX(120px) rotate(0deg);
            }

            100% {
                transform: rotate(360deg) translateX(120px) rotate(-360deg);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                opacity: 0.4;
            }

            50% {
                opacity: 0.8;
            }
        }

        @keyframes scan-line {
            0% {
                top: -100%;
            }

            100% {
                top: 100%;
            }
        }

        @keyframes data-flow {
            0% {
                transform: translateY(100%);
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100%);
                opacity: 0;
            }
        }

        @keyframes neural-pulse {

            0%,
            100% {
                box-shadow: 0 0 10px rgba(160, 32, 42, 0.3),
                    inset 0 0 10px rgba(160, 32, 42, 0.1);
            }

            50% {
                box-shadow: 0 0 30px rgba(160, 32, 42, 0.6),
                    inset 0 0 20px rgba(160, 32, 42, 0.3);
            }
        }

        .ai-orbit {
            position: absolute;
            width: 200px;
            height: 200px;
            opacity: 0.08;
        }

        .ai-orbit-1 {
            top: 10%;
            left: 5%;
            animation: orbitSlow 20s linear infinite;
        }

        .ai-orbit-2 {
            bottom: 15%;
            right: 5%;
            animation: orbitFast 15s linear infinite reverse;
        }

        .ai-dot {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #a8324a;
            border-radius: 50%;
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .ai-dot-1 {
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .ai-dot-2 {
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            animation-delay: 0.5s;
        }

        .ai-dot-3 {
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            animation-delay: 1s;
        }

        .ai-dot-4 {
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            animation-delay: 1.5s;
        }

        .data-stream {
            position: absolute;
            width: 2px;
            height: 100px;
            background: linear-gradient(to bottom, transparent, #a8324a, transparent);
            opacity: 0.6;
            animation: data-flow 3s ease-in-out infinite;
        }

        .data-stream-1 {
            top: 0;
            left: 20%;
            animation-delay: 0s;
        }

        .data-stream-2 {
            top: 0;
            left: 50%;
            animation-delay: 1s;
        }

        .data-stream-3 {
            top: 0;
            right: 20%;
            animation-delay: 2s;
        }

        .scan-effect {
            position: absolute;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #a8324a, transparent);
            top: 0;
            animation: scan-line 8s linear infinite;
            opacity: 0.3;
        }

        .neural-node {
            position: absolute;
            width: 12px;
            height: 12px;
            background: radial-gradient(circle, #a8324a, transparent);
            border: 1px solid rgba(168, 50, 74, 0.5);
            border-radius: 50%;
            animation: neural-pulse 3s ease-in-out infinite;
        }

        .node-1 {
            top: 15%;
            left: 10%;
            animation-delay: 0s;
        }

        .node-2 {
            top: 30%;
            right: 15%;
            animation-delay: 0.5s;
        }

        .node-3 {
            bottom: 20%;
            left: 25%;
            animation-delay: 1s;
        }

        .node-4 {
            bottom: 10%;
            right: 10%;
            animation-delay: 1.5s;
        }

        .grid-pattern {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(0deg, transparent 24%, rgba(168, 50, 74, 0.08) 25%, rgba(168, 50, 74, 0.08) 26%, transparent 27%, transparent 74%, rgba(168, 50, 74, 0.08) 75%, rgba(168, 50, 74, 0.08) 76%, transparent 77%, transparent),
                linear-gradient(90deg, transparent 24%, rgba(168, 50, 74, 0.08) 25%, rgba(168, 50, 74, 0.08) 26%, transparent 27%, transparent 74%, rgba(168, 50, 74, 0.08) 75%, rgba(168, 50, 74, 0.08) 76%, transparent 77%, transparent);
            background-size: 50px 50px;
            opacity: 0.5;
            animation: fadeIn 1s ease-out;
        }

        :root {
            --primary-color: #800020;
            --primary-hover: #5c0017;
            --secondary-color: #a8324a;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            animation: fadeIn 0.3s ease-out;
            backdrop-filter: blur(4px);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.4s ease-out;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            background: white;
        }

        .modal-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #9ca3af;
            cursor: pointer;
            transition: color 0.2s ease;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: #1f2937;
        }

        .modal-body {
            padding: 24px;
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
        }

        .modal-body h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin: 16px 0 8px 0;
        }

        .modal-body p {
            margin: 0 0 12px 0;
        }

        .modal-body ul,
        .modal-body ol {
            margin: 12px 0;
            padding-left: 20px;
        }

        .modal-body li {
            margin: 6px 0;
        }

        @media (max-width: 640px) {
            .modal-content {
                max-height: 90vh;
                border-radius: 16px;
            }

            .modal-header {
                padding: 16px;
            }

            .modal-body {
                padding: 16px;
            }
        }
    </style>
    <style>
        body {
            box-sizing: border-box;
        }
    </style>
</head>

<body class="h-full overflow-auto">
    <div id="app" class="min-h-full w-full flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden" style="background: linear-gradient(135deg, #1a0008 0%, #800020 50%, #2d0a12 100%);">
        <!-- AI Grid Pattern Background -->
        <div class="grid-pattern"></div><!-- Floating Background Shapes -->
        <div class="floating-shapes">
            <div class="shape shape-1" style="background: #fff;"></div>
            <div class="shape shape-2" style="background: #fff;"></div>
            <div class="shape shape-3" style="background: #fff;"></div>
        </div><!-- AI Orbital Elements -->
        <div class="ai-orbit ai-orbit-1">
            <svg class="w-full h-full" viewbox="0 0 200 200">
                <circle cx="100" cy="100" r="80" fill="none" stroke="rgba(168, 50, 74, 0.15)" stroke-width="1" stroke-dasharray="5,5" />
            </svg>
            <div class="ai-dot ai-dot-1"></div>
            <div class="ai-dot ai-dot-2"></div>
            <div class="ai-dot ai-dot-3"></div>
            <div class="ai-dot ai-dot-4"></div>
        </div>
        <div class="ai-orbit ai-orbit-2">
            <svg class="w-full h-full" viewbox="0 0 200 200">
                <circle cx="100" cy="100" r="100" fill="none" stroke="rgba(168, 50, 74, 0.1)" stroke-width="1" stroke-dasharray="8,4" />
            </svg>
        </div><!-- Data Stream Elements -->
        <div class="data-stream data-stream-1"></div>
        <div class="data-stream data-stream-2"></div>
        <div class="data-stream data-stream-3"></div><!-- Scan Line Effect -->
        <div class="scan-effect"></div><!-- Neural Network Nodes -->
        <div class="neural-node node-1"></div>
        <div class="neural-node node-2"></div>
        <div class="neural-node node-3"></div>
        <div class="neural-node node-4"></div><!-- Main Container -->
        <div class="auth-container w-full max-w-md relative z-10">
            <!-- Brand Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4 shadow-2xl" style="background: linear-gradient(135deg, #800020, #a8324a);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <!-- chatbot head -->
                        <rect x="5" y="6" width="14" height="10" rx="3" ry="3" stroke-width="2" />

                        <!-- antenna -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v3" />
                        <circle cx="12" cy="2" r="1" fill="currentColor" stroke="none" />

                        <!-- eyes -->
                        <circle cx="9" cy="11" r="1" fill="currentColor" stroke="none" />
                        <circle cx="15" cy="11" r="1" fill="currentColor" stroke="none" />

                        <!-- smile -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14c1 .8 2 .8 3 .8s2 0 3-.8" />

                        <!-- chat bubble tail -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 16l-2 3 4-2" />

                        <!-- sparkle for style/advice -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 5l.5 1.5L21 7l-1.5.5L19 9l-.5-1.5L17 7l1.5-.5L19 5z" />
                    </svg>
                </div>
                <h1 id="brand-name" class="text-2xl font-bold text-white tracking-tight">AuthPortal</h1>
            </div><!-- Card Container -->
            <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 sm:p-10">
                <!-- Login Form -->
                <div id="login-form" class="form-section">
                    <div class="text-center mb-8">
                        <h2 id="login-title" class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
                        <p class="text-gray-500 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
                    </div>
                    <form id="login" class="space-y-5" onsubmit="return false;">
                        <!-- Email Input -->
                        <div class="input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email atau Username</label>
                            <div class="relative">
                                <input type="text" id="login-email" class="w-full px-4 py-3.5 pl-12 bg-gray-50 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-[#800020] focus:bg-white text-gray-800 placeholder-gray-400" placeholder="nama@email.com">
                                <svg class="input-icon absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <p id="login-email-error" class="text-red-500 text-xs mt-1 hidden error-message"></p>
                        </div><!-- Password Input -->
                        <div class="input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <input type="password" id="login-password" class="w-full px-4 py-3.5 pl-12 pr-12 bg-gray-50 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-[#800020] focus:bg-white text-gray-800 placeholder-gray-400" placeholder="••••••••">
                                <svg class="input-icon absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <button type="button" onclick="togglePassword('login-password', this)" class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg></button>
                            </div>
                            <p id="login-password-error" class="text-red-500 text-xs mt-1 hidden error-message"></p>
                        </div>
                        <!-- Submit Button -->
                        <button type="button" id="login-btn" class="btn-primary w-full py-4 px-6 rounded-xl text-white font-semibold text-base shadow-lg" style="background: linear-gradient(135deg, #800020, #a8324a);"> Masuk </button> <!-- Success/Error Message -->
                        <div id="login-message" class="hidden text-center py-3 px-4 rounded-xl text-sm font-medium"></div>
                    </form>
                    <!-- Register Link -->
                    <p class="text-center text-gray-600 mt-8 text-sm">
                        Belum punya akun?
                        <button onclick="showRegister()" class="text-[#800020] hover:text-[#5c0017] font-semibold link-hover transition-colors">Daftar sekarang</button>
                    </p>
                </div><!-- Register Form -->
                <div id="register-form" class="form-section hidden">
                    <div class="text-center mb-8">
                        <h2 id="register-title" class="text-2xl font-bold text-gray-800 mb-2">Buat Akun Baru</h2>
                        <p class="text-gray-500 text-sm">Daftar untuk memulai perjalanan Anda</p>
                    </div>
                    <form id="register-form" class="space-y-4">

                        <!-- Full Name Input -->
                        <div class="input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <input type="text" id="register-name" autocomplete="off" class="w-full px-4 py-3.5 pl-12 bg-gray-50 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-[#800020] focus:bg-white text-gray-800 placeholder-gray-400" placeholder="John Doe">
                                <svg class="input-icon absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <p id="register-name-error" class="text-red-500 text-xs mt-1 hidden error-message"></p>
                        </div><!-- Email Input -->
                        <div class="input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <input type="email" id="register-email" autocomplete="off" class="w-full px-4 py-3.5 pl-12 bg-gray-50 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-[#800020] focus:bg-white text-gray-800 placeholder-gray-400" placeholder="nama@email.com">
                                <svg class="input-icon absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p id="register-email-error" class="text-red-500 text-xs mt-1 hidden error-message"></p>
                        </div><!-- Password Input -->
                        <div class="input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <input type="password" id="register-password" class="w-full px-4 py-3.5 pl-12 pr-12 bg-gray-50 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-[#800020] focus:bg-white text-gray-800 placeholder-gray-400" placeholder="Minimal 8 karakter">
                                <svg class="input-icon absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <button type="button" onclick="togglePassword('register-password', this)" class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <p id="register-password-error" class="text-red-500 text-xs mt-1 hidden error-message"></p>
                        </div><!-- Confirm Password Input -->
                        <div class="input-group">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <div class="relative">
                                <input type="password" id="register-confirm" class="w-full px-4 py-3.5 pl-12 pr-12 bg-gray-50 border-2 border-gray-100 rounded-xl focus:outline-none focus:border-[#800020] focus:bg-white text-gray-800 placeholder-gray-400" placeholder="Ulangi password">
                                <svg class="input-icon absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <button type="button" onclick="togglePassword('register-confirm', this)" class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <p id="register-confirm-error" class="text-red-500 text-xs mt-1 hidden error-message"></p>
                        </div><!-- Terms Checkbox -->
                        <div class="flex items-start gap-3 pt-2">
                            <input type="checkbox" id="terms" class="w-4 h-4 mt-0.5 rounded border-gray-300 text-[#800020] focus:ring-[#800020] focus:ring-offset-0">
                            <label for="terms" class="text-sm text-gray-600 leading-tight"> Saya setuju dengan
                                <button type="button" onclick="openModal('terms-modal')" class="text-[#800020] hover:text-[#5c0017] font-medium underline">Syarat &amp; Ketentuan</button>
                                dan <button type="button" onclick="openModal('privacy-modal')" class="text-[#800020] hover:text-[#5c0017] font-medium underline">Kebijakan Privasi</button>
                            </label>
                        </div>
                        <p id="terms-error" class="text-red-500 text-xs hidden error-message"></p>
                        <!-- Submit Button -->
                        <button type="button" id="register-btn" class="btn-primary w-full py-4 px-6 rounded-xl text-white font-semibold text-base shadow-lg mt-2" style="background: linear-gradient(135deg, #800020, #a8324a);"> Buat Akun </button>
                        <div id="register-message" class="hidden text-center py-3 px-4 rounded-xl text-sm font-medium"></div>
                    </form>
                    <!-- Login Link -->
                    <p class="text-center text-gray-600 mt-8 text-sm">Sudah punya akun?
                        <button onclick="showLogin()" class="text-[#800020] hover:text-[#5c0017] font-semibold link-hover transition-colors">Masuk sekarang</button>
                    </p>
                </div>
            </div><!-- Footer -->
            <p class="text-center text-white/60 text-xs mt-6">© <?= date('Y')  ?> AIStyle Advisor. All rights reserved.</p>
        </div><!-- Terms & Conditions Modal -->
        <div id="terms-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Syarat &amp; Ketentuan</h2><button type="button" class="modal-close" onclick="closeModal('terms-modal')">×</button>
                </div>
                <div class="modal-body">
                    <h3>1. Penerimaan Persyaratan</h3>
                    <p>Dengan menggunakan layanan AuthPortal, Anda menyetujui untuk terikat oleh persyaratan dan ketentuan ini. Jika Anda tidak setuju dengan persyaratan apa pun, mohon jangan menggunakan layanan kami.</p>
                    <h3>2. Penggunaan Layanan</h3>
                    <p>Anda setuju untuk menggunakan layanan ini hanya untuk tujuan yang sah dan dengan cara yang tidak melanggar hak orang lain atau membatasi penggunaan layanan oleh orang lain. Perilaku yang dilarang termasuk mengganggu, menguntit, mengancam, memalsu, atau dengan cara lain melecehkan atau menyebabkan rasa tidak enak kepada siapa pun.</p>
                    <h3>3. Akun Pengguna</h3>
                    <p>Ketika Anda membuat akun dengan kami, Anda harus memberikan informasi yang akurat, lengkap, dan terkini. Anda bertanggung jawab untuk menjaga kerahasiaan kata sandi Anda dan bertanggung jawab atas semua aktivitas yang terjadi di bawah akun Anda.</p>
                    <h3>4. Konten Pengguna</h3>
                    <p>Anda mempertahankan semua hak ke konten apa pun yang Anda kirimkan, posting, atau menampilkan di atau melalui layanan. Dengan mengirimkan, memposting, atau menampilkan konten, Anda memberikan kepada kami lisensi dunia tanpa royalti untuk menggunakan, menyalin, mereproduksi, memproses, beradaptasi, memodifikasi, menerbitkan, mentransmisikan, menampilkan, dan mendistribusikan konten tersebut dalam media apa pun.</p>
                    <h3>5. Batasan Tanggung Jawab</h3>
                    <p>Layanan ini disediakan "sebagaimana adanya" tanpa jaminan apa pun, tersurat maupun tersirat. Kami tidak menjamin bahwa layanan akan memenuhi kebutuhan Anda, bahwa layanan akan tidak terputus, tepat waktu, aman, atau bebas kesalahan.</p>
                    <h3>6. Perubahan Persyaratan</h3>
                    <p>Kami berhak untuk memodifikasi persyaratan ini kapan saja. Jika ada perubahan signifikan, kami akan memberitahu Anda melalui layanan atau melalui email. Penggunaan layanan yang berkelanjutan setelah modifikasi berarti penerimaan persyaratan yang diubah.</p>
                    <h3>7. Penghentian</h3>
                    <p>Kami dapat menghentikan atau menangguhkan akun Anda dan akses ke layanan kapan saja, tanpa pemberitahuan sebelumnya, karena pelanggaran persyaratan ini atau untuk alasan lain apa pun.</p>
                    <h3>8. Hukum yang Berlaku</h3>
                    <p>Persyaratan dan ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum yurisdiksi tempat AuthPortal beroperasi, tanpa memperhatikan prinsip-prinsip konflik hukumnya.</p>
                </div>
            </div>
        </div><!-- Privacy Policy Modal -->
        <div id="privacy-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Kebijakan Privasi</h2><button type="button" class="modal-close" onclick="closeModal('privacy-modal')">×</button>
                </div>
                <div class="modal-body">
                    <h3>1. Pengantar</h3>
                    <p>AuthPortal ("kami", "kami", atau "kami") mengoperasikan aplikasi web AuthPortal (selanjutnya disebut "Layanan"). Halaman ini menginformasikan Anda tentang kebijakan privasi kami mengenai pengumpulan, penggunaan, dan pengungkapan data pribadi ketika Anda menggunakan Layanan kami.</p>
                    <h3>2. Definisi dan Interpretasi</h3>
                    <p><strong>Akun:</strong> Akun unik yang dibuat oleh Anda untuk mengakses Layanan atau bagian dari Layanan kami.</p>
                    <p><strong>Data Pribadi:</strong> Informasi apa pun yang berkaitan dengan individu yang dapat diidentifikasi secara langsung atau tidak langsung.</p>
                    <p><strong>Layanan:</strong> Mengacu pada aplikasi web AuthPortal.</p>
                    <h3>3. Pengumpulan dan Penggunaan Data</h3>
                    <p>Kami mengumpulkan berbagai jenis informasi untuk berbagai tujuan untuk memberikan dan meningkatkan Layanan kami kepada Anda.</p>
                    <h4>Jenis Data yang Dikumpulkan:</h4>
                    <ul>
                        <li><strong>Data Akun:</strong> Nama, alamat email, kata sandi, nomor telepon</li>
                        <li><strong>Data Penggunaan:</strong> Informasi tentang cara Anda mengakses dan menggunakan Layanan</li>
                        <li><strong>Data Perangkat:</strong> Jenis perangkat, sistem operasi, pengidentifikasi unik perangkat</li>
                        <li><strong>Data Lokasi:</strong> Informasi lokasi geografis (jika diberikan)</li>
                    </ul>
                    <h3>4. Keamanan Data</h3>
                    <p>Keamanan data Anda penting bagi kami, tetapi ingat bahwa tidak ada metode transmisi melalui Internet atau metode penyimpanan elektronik 100% aman. Meskipun kami berusaha untuk menggunakan cara yang dapat diterima secara komersial untuk melindungi data pribadi Anda, kami tidak dapat menjamin keamanannya secara mutlak.</p>
                    <h3>5. Penyimpanan Data</h3>
                    <p>Data pribadi Anda akan disimpan hanya selama diperlukan untuk tujuan yang diuraikan dalam Kebijakan Privasi ini. Kami akan menyimpan dan menggunakan data pribadi Anda sejauh diperlukan untuk mematuhi kewajiban hukum kami.</p>
                    <h3>6. Pengungkapan Data</h3>
                    <p>Dalam keadaan tertentu, AuthPortal mungkin diminta untuk mengungkapkan data pribadi Anda jika diwajibkan oleh hukum atau sebagai respons terhadap permintaan yang valid dari otoritas publik.</p>
                    <h3>7. Koneksi Eksternal</h3>
                    <p>Layanan kami mungkin berisi tautan ke situs web eksternal yang tidak dioperasikan oleh kami. Kebijakan Privasi ini tidak berlaku untuk situs web pihak ketiga, dan kami tidak bertanggung jawab atas konten, keakuratan, atau praktik mereka.</p>
                    <h3>8. Perubahan Kebijakan Privasi</h3>
                    <p>Kami dapat memperbarui Kebijakan Privasi kami dari waktu ke waktu. Kami akan memberitahu Anda tentang perubahan apa pun dengan memposting Kebijakan Privasi baru di halaman ini dan memperbarui tanggal "Terakhir Diperbarui" di bagian atas Kebijakan Privasi ini.</p>
                    <h3>9. Hubungi Kami</h3>
                    <p>Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, silakan hubungi kami di:<br>
                        Email: privacy@authportal.com<br>
                        Alamat: Jakarta, Indonesia</p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?= base_url('assets/js/notify_ai.js') ?>"></script>
    <script>
        $(document).ready(function() {
            $('#register-btn').on('click', function(e) {
                e.preventDefault();

                $('.error-text').text('');
                $('#register-message').addClass('hidden').removeClass('text-red-500 text-green-600');

                $.ajax({
                    url: "<?= base_url('auth/register') ?>",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        name: $('#register-name').val().trim(),
                        email: $('#register-email').val().trim(),
                        password: $('#register-password').val(),
                        password_confirm: $('#register-confirm').val(),
                        terms: $('#terms').is(':checked') ? 1 : '',

                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Notify.fire({
                                type: "success",
                                title: "Berhasil!",
                                text: response.message || "Akun berhasil dibuat. Silakan masuk.",
                            });

                            // Delay 1000 milidetik (1 detik)
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else if (response.status === 'validation_error') {
                            if (response.errors.name) {
                                $('#register-name-error').text(response.errors.name).removeClass('hidden');
                            } else {
                                $('#register-name-error').text('').addClass('hidden');
                            }
                            if (response.errors.email) {
                                $('#register-email-error').text(response.errors.email).removeClass('hidden');
                            } else {
                                $('#register-email-error').text('').addClass('hidden');
                            }
                            if (response.errors.password) {
                                $('#register-password-error').text(response.errors.password).removeClass('hidden');
                            } else {
                                $('#register-password-error').text('').addClass('hidden');
                            }
                            if (response.errors.password_confirm) {
                                $('#register-confirm-error').text(response.errors.password_confirm).removeClass('hidden');
                            } else {
                                $('#register-confirm-error').text('').addClass('hidden');
                            }
                            if (response.errors.terms) {
                                $('#terms-error').text(response.errors.terms).removeClass('hidden');
                            } else {
                                $('#terms-error').text('').addClass('hidden');
                            }
                        }

                        // update token baru kalau regenerate aktif

                    },
                    error: function(xhr) {
                        let res = xhr.responseJSON;



                        $('#register-message')
                            .removeClass('hidden')
                            .addClass('text-red-500')
                            .text(res?.message || 'Terjadi kesalahan server.');
                    }
                });
            });

            $('#login-btn').on('click', function(e) {
                e.preventDefault();

                // Reset semua error message
                $('#login-email-error').text('').addClass('hidden');
                $('#login-password-error').text('').addClass('hidden');
                $('#login-message').addClass('hidden').removeClass('text-red-500 text-green-600');

                $.ajax({
                    url: "<?= base_url('/auth/process_login') ?>",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        email: $('#login-email').val().trim(),
                        password: $('#login-password').val(),
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Notify.fire({
                                type: "success",
                                title: "Berhasil!",
                                text: response.message || "Login berhasil. Mengalihkan...",
                            });

                            setTimeout(function() {
                                window.location.href = response.redirect ?? "<?= base_url('/summary') ?>";
                            }, 1000);

                        } else if (response.status === 'validation_error') {
                            if (response.errors.email) {
                                $('#login-email-error').text(response.errors.email).removeClass('hidden');
                            } else {
                                $('#login-email-error').text('').addClass('hidden');
                            }

                            if (response.errors.password) {
                                $('#login-password-error').text(response.errors.password).removeClass('hidden');
                            } else {
                                $('#login-password-error').text('').addClass('hidden');
                            }

                        } else if (response.status === 'error') {
                            $('#login-message')
                            Notify.fire({
                                type: "error",
                                title: "Gagal!",
                                text: response.message || "Login gagal. Silakan coba lagi.",
                            });
                        }
                    },
                    error: function(xhr) {
                        let res = xhr.responseJSON;

                        $('#login-message')
                            .removeClass('hidden')
                            .addClass('text-red-500')
                            .text(res?.message || 'Terjadi kesalahan server.');
                    }
                });
            });
        });


        // Modal functions
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal when clicking outside content
        document.addEventListener('DOMContentLoaded', function() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this.id);
                    }
                });
            });
        });

        // Default configuration
        const defaultConfig = {
            brand_name: 'AIStyle_Advisor',
            login_title: 'Selamat Datang',
            register_title: 'Buat Akun Baru',
            login_button_text: 'Masuk',
            register_button_text: 'Buat Akun',
            primary_color: '#800020',
            secondary_color: '#a8324a',
            text_color: '#1f2937',
            background_color: '#ffffff',
            accent_color: '#f9fafb'
        };

        // Initialize Element SDK
        if (window.elementSdk) {
            window.elementSdk.init({
                defaultConfig,
                onConfigChange: async (config) => {
                    // Update brand name
                    const brandEl = document.getElementById('brand-name');
                    if (brandEl) brandEl.textContent = config.brand_name || defaultConfig.brand_name;

                    // Update login title
                    const loginTitleEl = document.getElementById('login-title');
                    if (loginTitleEl) loginTitleEl.textContent = config.login_title || defaultConfig.login_title;

                    // Update register title
                    const registerTitleEl = document.getElementById('register-title');
                    if (registerTitleEl) registerTitleEl.textContent = config.register_title || defaultConfig.register_title;

                    // Update button texts
                    const loginBtn = document.getElementById('login-btn');
                    if (loginBtn) loginBtn.textContent = config.login_button_text || defaultConfig.login_button_text;

                    const registerBtn = document.getElementById('register-btn');
                    if (registerBtn) registerBtn.textContent = config.register_button_text || defaultConfig.register_button_text;

                    // Update colors
                    const primaryColor = config.primary_color || defaultConfig.primary_color;
                    const secondaryColor = config.secondary_color || defaultConfig.secondary_color;

                    document.documentElement.style.setProperty('--primary-color', primaryColor);

                    // Update gradient backgrounds
                    const appBg = document.getElementById('app');
                    if (appBg) {
                        appBg.style.background = `linear-gradient(135deg, ${adjustColor(primaryColor, -60)} 0%, ${primaryColor} 50%, ${adjustColor(primaryColor, -40)} 100%)`;
                    }

                    // Update buttons
                    const buttons = document.querySelectorAll('.btn-primary');
                    buttons.forEach(btn => {
                        btn.style.background = `linear-gradient(135deg, ${primaryColor}, ${secondaryColor})`;
                    });

                    // Update focus colors
                    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
                    inputs.forEach(input => {
                        input.style.setProperty('--focus-color', primaryColor);
                    });
                },
                mapToCapabilities: (config) => ({
                    recolorables: [{
                            get: () => config.primary_color || defaultConfig.primary_color,
                            set: (value) => {
                                config.primary_color = value;
                                window.elementSdk.setConfig({
                                    primary_color: value
                                });
                            }
                        },
                        {
                            get: () => config.secondary_color || defaultConfig.secondary_color,
                            set: (value) => {
                                config.secondary_color = value;
                                window.elementSdk.setConfig({
                                    secondary_color: value
                                });
                            }
                        },
                        {
                            get: () => config.text_color || defaultConfig.text_color,
                            set: (value) => {
                                config.text_color = value;
                                window.elementSdk.setConfig({
                                    text_color: value
                                });
                            }
                        },
                        {
                            get: () => config.background_color || defaultConfig.background_color,
                            set: (value) => {
                                config.background_color = value;
                                window.elementSdk.setConfig({
                                    background_color: value
                                });
                            }
                        },
                        {
                            get: () => config.accent_color || defaultConfig.accent_color,
                            set: (value) => {
                                config.accent_color = value;
                                window.elementSdk.setConfig({
                                    accent_color: value
                                });
                            }
                        }
                    ],
                    borderables: [],
                    fontEditable: undefined,
                    fontSizeable: undefined
                }),
                mapToEditPanelValues: (config) => new Map([
                    ['brand_name', config.brand_name || defaultConfig.brand_name],
                    ['login_title', config.login_title || defaultConfig.login_title],
                    ['register_title', config.register_title || defaultConfig.register_title],
                    ['login_button_text', config.login_button_text || defaultConfig.login_button_text],
                    ['register_button_text', config.register_button_text || defaultConfig.register_button_text]
                ])
            });
        }

        // Helper function to adjust color brightness
        function adjustColor(hex, percent) {
            const num = parseInt(hex.replace('#', ''), 16);
            const amt = Math.round(2.55 * percent);
            const R = Math.max(0, Math.min(255, (num >> 16) + amt));
            const G = Math.max(0, Math.min(255, ((num >> 8) & 0x00FF) + amt));
            const B = Math.max(0, Math.min(255, (num & 0x0000FF) + amt));
            return '#' + (0x1000000 + R * 0x10000 + G * 0x100 + B).toString(16).slice(1);
        }

        // Toggle password visibility
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // Show/Hide forms with animation
        function showRegister() {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            loginForm.classList.add('slide-out');
            setTimeout(() => {
                loginForm.classList.add('hidden');
                loginForm.classList.remove('slide-out');
                registerForm.classList.remove('hidden');
                registerForm.classList.add('slide-in');
                setTimeout(() => registerForm.classList.remove('slide-in'), 400);
            }, 300);
        }

        function showLogin() {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            registerForm.classList.add('slide-out');
            setTimeout(() => {
                registerForm.classList.add('hidden');
                registerForm.classList.remove('slide-out');
                loginForm.classList.remove('hidden');
                loginForm.classList.add('slide-in');
                setTimeout(() => loginForm.classList.remove('slide-in'), 400);
            }, 300);
        }

        // Validation helpers
        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        function showError(elementId, message) {
            const errorEl = document.getElementById(elementId);
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }

        function hideError(elementId) {
            const errorEl = document.getElementById(elementId);
            errorEl.classList.add('hidden');
        }

        function showMessage(elementId, message, isSuccess) {
            const msgEl = document.getElementById(elementId);
            msgEl.textContent = message;
            msgEl.classList.remove('hidden');
            msgEl.className = `${isSuccess ? 'success-message bg-green-100 text-green-700' : 'error-message bg-red-100 text-red-700'} text-center py-3 px-4 rounded-xl text-sm font-medium`;

            setTimeout(() => {
                msgEl.classList.add('hidden');
            }, 4000);
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }

        function updateStrengthIndicator(strength) {
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
            const texts = ['Sangat Lemah', 'Lemah', 'Cukup Kuat', 'Kuat'];

            for (let i = 1; i <= 4; i++) {
                const bar = document.getElementById(`strength-${i}`);
                bar.className = `h-1 flex-1 rounded-full transition-all duration-300 ${i <= strength ? colors[strength - 1] : 'bg-gray-200'}`;
            }

            const textEl = document.getElementById('strength-text');
            textEl.textContent = strength > 0 ? texts[strength - 1] : '';
            textEl.className = `text-xs mt-1 ${strength > 0 ? colors[strength - 1].replace('bg-', 'text-') : 'text-gray-400'}`;
        }

        // Password input listener for strength indicator
        document.getElementById('register-password').addEventListener('input', function(e) {
            const strength = checkPasswordStrength(e.target.value);
            updateStrengthIndicator(strength);
        });

        // Handle Login
        function handleLogin(event) {
            event.preventDefault();

            const email = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value;

            let isValid = true;

            // Validate email
            hideError('login-email-error');
            hideError('login-password-error');

            if (!email) {
                showError('login-email-error', 'Email atau username tidak boleh kosong');
                isValid = false;
            } else if (email.includes('@') && !isValidEmail(email)) {
                showError('login-email-error', 'Format email tidak valid');
                isValid = false;
            }

            // Validate password
            if (!password) {
                showError('login-password-error', 'Password tidak boleh kosong');
                isValid = false;
            } else if (password.length < 8) {
                showError('login-password-error', 'Password minimal 8 karakter');
                isValid = false;
            }

            if (isValid) {
                // Simulate login success
                const btn = document.getElementById('login-btn');
                btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                btn.disabled = true;

                setTimeout(() => {
                    btn.innerHTML = window.elementSdk?.config?.login_button_text || defaultConfig.login_button_text;
                    btn.disabled = false;
                    showMessage('login-message', '🎉 Login berhasil! Mengalihkan...', true);
                }, 1500);
            }
        }

        // Handle Register
        // function handleRegister(event) {
        //     event.preventDefault();

        //     const name = document.getElementById('register-name').value.trim();
        //     const email = document.getElementById('register-email').value.trim();
        //     const password = document.getElementById('register-password').value;
        //     const confirm = document.getElementById('register-confirm').value;
        //     const terms = document.getElementById('terms').checked;

        //     let isValid = true;

        //     // Clear previous errors
        //     hideError('register-name-error');
        //     hideError('register-email-error');
        //     hideError('register-password-error');
        //     hideError('register-confirm-error');
        //     hideError('terms-error');

        //     // Validate name
        //     if (!name) {
        //         showError('register-name-error', 'Nama lengkap tidak boleh kosong');
        //         isValid = false;
        //     } else if (name.length < 3) {
        //         showError('register-name-error', 'Nama minimal 3 karakter');
        //         isValid = false;
        //     }

        //     // Validate email
        //     if (!email) {
        //         showError('register-email-error', 'Email tidak boleh kosong');
        //         isValid = false;
        //     } else if (!isValidEmail(email)) {
        //         showError('register-email-error', 'Format email tidak valid');
        //         isValid = false;
        //     }

        //     // Validate password
        //     if (!password) {
        //         showError('register-password-error', 'Password tidak boleh kosong');
        //         isValid = false;
        //     } else if (password.length < 8) {
        //         showError('register-password-error', 'Password minimal 8 karakter');
        //         isValid = false;
        //     }

        //     // Validate confirm password
        //     if (!confirm) {
        //         showError('register-confirm-error', 'Konfirmasi password tidak boleh kosong');
        //         isValid = false;
        //     } else if (password !== confirm) {
        //         showError('register-confirm-error', 'Password tidak cocok');
        //         isValid = false;
        //     }

        //     // Validate terms
        //     if (!terms) {
        //         showError('terms-error', 'Anda harus menyetujui syarat dan ketentuan');
        //         isValid = false;
        //     }

        //     if (isValid) {
        //         // Simulate registration success
        //         const btn = document.getElementById('register-btn');
        //         btn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        //         btn.disabled = true;

        //         setTimeout(() => {
        //             btn.innerHTML = window.elementSdk?.config?.register_button_text || defaultConfig.register_button_text;
        //             btn.disabled = false;
        //             showMessage('register-message', '🎉 Registrasi berhasil! Silakan login.', true);

        //             // Clear form
        //             document.getElementById('register').reset();
        //             updateStrengthIndicator(0);

        //             // Switch to login after 2 seconds
        //             setTimeout(() => showLogin(), 2000);
        //         }, 1500);
        //     }
        // }

        // Add focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>

</body>

</html>