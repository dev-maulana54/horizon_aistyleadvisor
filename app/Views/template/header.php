<!doctype html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Style Advisor</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/modal.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/css/notify_ai.css') ?>">
    <style>
        #edit-profile-modal .text-muted {
            color: rgb(107 114 128);
        }

        .dark #edit-profile-modal .text-muted,
        .dark-mode #edit-profile-modal .text-muted {
            color: rgb(156 163 175);
        }

        .wardrobe-category-btn {
            background: #fff;
            color: var(--primary);
            border-color: var(--primary);
        }

        .wardrobe-category-btn.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .empty-image-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin: 0 auto 16px;
        }

        .empty-image-item {
            position: relative;
            width: 110px;
            height: 110px;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            border: 2px solid #e5e7eb;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }

        .empty-image-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12);
        }

        .empty-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .empty-image-remove {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #7B1E2B;
            /* maroon */
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .empty-image-remove:hover {
            background: #5a1420;
            transform: scale(1.1);
        }
    </style>
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
                    <h1 class="text-xl font-bold">FitMatch AI</h1>
                    <!-- <p class="text-xs opacity-80">Advisor</p> -->
                </div>
            </div>
            <nav class="flex-1 space-y-2">
                <button
                    onclick="showPage('home')"
                    class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/10  <?= ($menu == 'home') ? 'bg-white/20 text-white' : '' ?>"
                    data-page="home">
                    <i class="fas fa-home text-lg flex-shrink-0"></i>
                    <div class="text-left">
                        <span class="font-medium block">Home</span><span class="text-xs opacity-70">Dashboard</span>
                    </div>
                </button>
                <button
                    onclick="showPage('chat')"
                    class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/10  <?= ($menu == 'ai') ? 'bg-white/20 text-white' : '' ?>"
                    data-page="chat">
                    <i class="fas fa-comments text-lg flex-shrink-0"></i>
                    <div class="text-left">
                        <span class="font-medium block">Chat with AI</span><span class="text-xs opacity-70">Cari referensi dengan AI</span>
                    </div>
                </button>
                <button
                    onclick="showPage('settings')"
                    class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/10  <?= ($menu == 'settings_profile') ? 'bg-white/20 text-white' : '' ?>"
                    data-page="settings">
                    <i class="fas fa-gear text-lg flex-shrink-0"></i>
                    <div class="text-left">
                        <span class="font-medium block">Settings</span><span class="text-xs opacity-70">Aplikasi dan Akun</span>
                    </div>
                </button>
                <?php if (session()->get('isLoggedIn')): ?>


                    <button
                        onclick="showPage('logout')"
                        class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/10"
                        data-page="logout">
                        <i class="fas fa-right-from-bracket text-lg flex-shrink-0"></i>
                        <div class="text-left">
                            <span class="font-medium block">Logout</span><span class="text-xs opacity-70">Keluar Halaman</span>
                        </div>
                    </button>
                <?php endif; ?>
            </nav>
            <div class="mt-auto pt-6 border-t border-white/20">
                <div class="flex items-center gap-3">
                    <img
                        src="<?= base_url('assets/img/aiimg.png') ?>"
                        alt="Profile"
                        class="w-10 h-10 rounded-full object-cover"
                        loading="lazy"
                        onerror="
                this.style.background = '#9B3545';
                this.alt = 'User';
              " />
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm truncate" id="sidebar-username">
                            <?= $nama_user ?>
                        </p>
                        <p class="text-xs opacity-70">Premium Member</p>
                    </div>
                </div>
            </div>
        </aside>
        <!-- Main Content Area -->