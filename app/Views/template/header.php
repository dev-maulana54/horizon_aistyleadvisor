<!doctype html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Style Advisor</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script src="/_sdk/element_sdk.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="<?= base_url('assets/css/style.css') ?>"
        rel="stylesheet" />
</head>

<body class="h-full light-mode">
    <div id="app" class="h-full flex flex-col lg:flex-row overflow-hidden">
        <!-- Desktop Sidebar -->
        <aside
            class="desktop-sidebar hidden lg:flex flex-col w-72 h-full primary-bg text-white p-6">
            <div class="flex items-center gap-3 mb-10">
                <div
                    class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <!-- AI Style Advisor Icon (inline SVG) -->
                    <svg
                        class="w-5 h-5 text-white"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <!-- AI Sparkle (LEFT - moved higher) -->
                        <path
                            d="M6 4l.5 1.5L8 6l-1.5.5L6 8l-.5-1.5L4 6l1.5-.5L6 4z"
                            fill="currentColor"
                            opacity="0.9" />

                        <!-- AI Sparkle (RIGHT) -->
                        <path
                            d="M19 2l.6 1.8L21.4 4.5l-1.8.6L19 7l-.6-1.9-1.8-.6 1.8-.7L19 2z"
                            fill="currentColor"
                            opacity="0.9" />

                        <!-- Head -->
                        <circle
                            cx="12"
                            cy="6"
                            r="2.5"
                            stroke="currentColor"
                            stroke-width="1.8" />

                        <!-- Body -->
                        <path
                            d="M6.5 20c.5-4 3-6 5.5-6s5 2 5.5 6"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round" />

                        <!-- Arms -->
                        <path
                            d="M4.5 13c2-1 4-2 7.5-2s5.5 1 7.5 2"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold">AI Style</h1>
                    <p class="text-xs opacity-80">Advisor</p>
                </div>
            </div>
            <nav class="flex-1 space-y-2">
                <button
                    onclick="showPage('home')"
                    class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/10  <?= ($menu == 'home') ? 'bg-white/20 text-white' : 'text-gray-400' ?>"
                    data-page="home">
                    <i class="fas fa-home text-lg flex-shrink-0"></i>
                    <div class="text-left">
                        <span class="font-medium block">Home</span><span class="text-xs opacity-70">Dashboard</span>
                    </div>
                </button>
                <button
                    onclick="showPage('chat')"
                    class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/10  <?= ($menu == 'chat') ? 'bg-white/20 text-white' : '' ?>"
                    data-page="chat">
                    <i class="fas fa-comments text-lg flex-shrink-0"></i>
                    <div class="text-left">
                        <span class="font-medium block">Chat with AI</span><span class="text-xs opacity-70">Cari referensi dengan AI</span>
                    </div>
                </button>
                <button
                    onclick="showPage('settings')"
                    class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/10  <?= ($menu == 'settings') ? 'bg-white/20 text-white' : '' ?>"
                    data-page="settings">
                    <i class="fas fa-gear text-lg flex-shrink-0"></i>
                    <div class="text-left">
                        <span class="font-medium block">Settings</span><span class="text-xs opacity-70">Aplikasi dan Akun</span>
                    </div>
                </button>
            </nav>
            <div class="mt-auto pt-6 border-t border-white/20">
                <div class="flex items-center gap-3">
                    <img
                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&amp;h=100&amp;fit=crop&amp;crop=face"
                        alt="Profile"
                        class="w-10 h-10 rounded-full object-cover"
                        loading="lazy"
                        onerror="
                this.style.background = '#9B3545';
                this.alt = 'User';
              " />
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm truncate" id="sidebar-username">
                            Maulana Saepul Akbar
                        </p>
                        <p class="text-xs opacity-70">Premium Member</p>
                    </div>
                </div>
            </div>
        </aside>
        <!-- Main Content Area -->