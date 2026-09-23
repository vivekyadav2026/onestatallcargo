<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50 theme-navy">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Testwise - MP Police Constable GD 2026 Batch & Exam Engine')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            /* Theme bases from BussinessManagement style guide */
            --bg-main: #F3F5F3;
            --bg-card: #FFFFFF;
            --border-color: #EBECE6;
            --border-hard: #DFE1DA;
            --text-main: #17233F;
            --text-muted: #4B5670;
            --text-faint: #8991A5;

            --gold: #D99A2B;
            --gold-deep: #B87F1B;
            --teal: #146356;
            --teal-soft: #E4F0EC;
            --rose: #AE3B34;
            --rose-soft: #F5E6E4;
            --radius: 14px;
            --shadow: 0 1px 2px rgba(23,35,63,.04), 0 8px 24px rgba(23,35,63,.06);

            /* Sidebar custom variables */
            --theme-bg: #17233F; /* Deep Navy Ink */
            --theme-text: #8991A5;
            --theme-active: #D99A2B; /* Khatabook Gold */
            --theme-active-text: #17233F;
            --theme-hover: rgba(217, 154, 43, 0.12);
            --theme-hover-text: #ffffff;
        }

        body {
            background-color: var(--bg-main) !important;
            color: var(--text-main) !important;
            font-family: 'Inter', sans-serif !important;
        }

        h1, h2, h3, h4, .dash-head h1, .display {
            font-family: 'Space Grotesk', sans-serif !important;
            letter-spacing: -.01em !important;
            font-weight: 700 !important;
        }

        nav a {
            font-family: 'Space Grotesk', sans-serif !important;
            font-weight: 600 !important;
        }

        /* Forms Layout & Labels styling */
        form label:not(.inline-flex):not(.flex-row),
        .field label:not(.inline-flex):not(.flex-row) {
            display: flex !important;
            justify-content: space-between !important;
            font-size: 12.5px !important;
            font-weight: 600 !important;
            color: var(--text-main) !important;
            margin-bottom: 7px !important;
            font-family: 'Inter', sans-serif !important;
        }

        /* Global inputs & form elements styling overrides */
        input[type="text"], input[type="number"], input[type="email"], input[type="password"], input[type="date"], input[type="time"], input[type="search"], select, textarea {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-hard) !important;
            border-radius: 10px !important;
            padding: 9px 13px !important;
            font-size: 13px !important;
            color: var(--text-main) !important;
            box-shadow: none !important;
            transition: all 0.2s ease !important;
            width: 100%;
        }

        input[type="text"]:focus, input[type="number"]:focus, input[type="email"]:focus, input[type="password"]:focus, input[type="date"]:focus, input[type="time"]:focus, input[type="search"]:focus, select:focus, textarea:focus {
            border-color: var(--gold) !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(217, 154, 43, 0.15) !important;
            background-color: #FFFFFF !important;
        }

        /* Buttons styling */
        .btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 7px !important;
            font-family: 'Space Grotesk', sans-serif !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
            padding: 9px 18px !important;
            font-size: 13px !important;
            transition: all 0.2s ease !important;
            cursor: pointer !important;
        }

        .btn-gold, .btn-primary {
            background-color: var(--gold) !important;
            color: #17233F !important;
            border: none !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
        }
        .btn-gold:hover, .btn-primary:hover {
            background-color: var(--gold-deep) !important;
            color: #ffffff !important;
        }

        .btn-secondary {
            background-color: #FFFFFF !important;
            color: var(--text-main) !important;
            border: 1px solid var(--border-hard) !important;
        }
        .btn-secondary:hover {
            background-color: var(--bg-main) !important;
            border-color: var(--text-muted) !important;
        }

        /* Table & Panels styling */
        .panel, .card, div[class*="bg-white rounded"], div[class*="bg-slate-900"], div[class*="bg-slate-800"] {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow) !important;
            color: var(--text-main) !important;
        }
        
        /* overriding specific classes used in existing views */
        .text-slate-300, .text-slate-400 { color: var(--text-muted) !important; }
        .text-slate-100, .text-slate-200 { color: var(--text-main) !important; }
        .border-slate-800 { border-color: var(--border-color) !important; }
    </style>
</head>
<body class="min-h-screen font-sans antialiased flex flex-col">

    <!-- Top Announcement Bar & Quick Role Switcher -->
    <div class="bg-gray-100 border-b border-gray-200 px-4 py-2 text-xs font-medium hidden sm:block" style="background-color: var(--theme-bg); color: var(--theme-text);">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold" style="background-color: var(--theme-active); color: var(--theme-active-text);">
                    BILINGUAL LIVE BATCH
                </span>
                <span style="color: white !important;">MP Police Constable GD 2026 - New Pattern & Chapter Notes Live</span>
            </div>

            <div class="flex items-center gap-2">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-2.5 py-1 rounded transition flex items-center gap-1 hover:opacity-80" style="background-color: var(--theme-active); color: var(--theme-active-text);">
                            <i class="fa-solid fa-user-shield"></i> Go to Admin Panel
                        </a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="px-2.5 py-1 rounded transition flex items-center gap-1 hover:opacity-80" style="background-color: var(--theme-active); color: var(--theme-active-text);">
                            <i class="fa-solid fa-graduation-cap"></i> Go to Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-2.5 py-1 rounded transition flex items-center gap-1 hover:opacity-80 border" style="background-color: transparent; border-color: var(--theme-text); color: white;">
                        <i class="fa-solid fa-right-to-bracket"></i> Login
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white shadow-sm border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-xl shadow-sm transition" style="background-color: var(--gold); color: var(--theme-bg);">
                    T
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-lg tracking-tight" style="color: var(--text-main) !important;">Testwise</span>
                        <span class="hidden sm:inline-block text-[10px] font-bold px-1.5 py-0.2 rounded border" style="background-color: var(--teal-soft); color: var(--teal); border-color: var(--teal);">GD 2026</span>
                    </div>
                    <p class="hidden sm:block text-[10px] -mt-1 font-medium" style="color: var(--text-muted);">MP Police Exam Portal</p>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('home') }}" class="transition hover:text-[var(--gold-deep)] {{ request()->routeIs('home') ? 'font-bold' : '' }}" style="color: var(--text-main) !important;">Home</a>
                <a href="{{ route('courses') }}" class="transition hover:text-[var(--gold-deep)] {{ request()->routeIs('courses') ? 'font-bold' : '' }}" style="color: var(--text-main) !important;">Courses</a>
                <a href="{{ route('free-content') }}" class="transition hover:text-[var(--gold-deep)] {{ request()->routeIs('free-content') ? 'font-bold' : '' }}" style="color: var(--text-main) !important;">Free Content</a>
                <a href="{{ route('exam-info') }}" class="transition hover:text-[var(--gold-deep)] {{ request()->routeIs('exam-info') ? 'font-bold' : '' }}" style="color: var(--text-main) !important;">Exam Info</a>
            </nav>

            <!-- Desktop Action Buttons -->
            <div class="hidden md:flex items-center gap-3 shrink-0">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary text-sm">
                            <i class="fa-solid fa-gauge"></i> Admin Console
                        </a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary text-sm">
                            <i class="fa-solid fa-table-columns"></i> My Dashboard
                        </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm p-2 hover:text-[var(--rose)]" style="color: var(--text-muted);" title="Logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium hover:text-[var(--gold-deep)]" style="color: var(--text-main);">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-gold text-sm">
                        Enroll ₹499
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex md:hidden items-center">
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-500 focus:outline-none">
                    <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark text-2xl' : 'fa-bars text-xl'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" class="md:hidden border-t border-gray-200" style="background-color: var(--bg-card); display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'bg-gray-100 font-bold' : '' }}" style="color: var(--text-main) !important;">Home</a>
                <a href="{{ route('courses') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('courses') ? 'bg-gray-100 font-bold' : '' }}" style="color: var(--text-main) !important;">Courses</a>
                <a href="{{ route('free-content') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('free-content') ? 'bg-gray-100 font-bold' : '' }}" style="color: var(--text-main) !important;">Free Content</a>
                <a href="{{ route('exam-info') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('exam-info') ? 'bg-gray-100 font-bold' : '' }}" style="color: var(--text-main) !important;">Exam Info</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200 px-5 space-y-3">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block w-full text-center btn btn-secondary text-sm">
                            <i class="fa-solid fa-gauge"></i> Admin Console
                        </a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="block w-full text-center btn btn-secondary text-sm">
                            <i class="fa-solid fa-table-columns"></i> My Dashboard
                        </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-center btn text-sm border shadow-sm" style="background-color: white; color: var(--rose); border-color: var(--rose-soft);">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center btn btn-secondary text-sm">Log in</a>
                    <a href="{{ route('register') }}" class="block w-full text-center btn btn-gold text-sm">Enroll Now ₹499</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-4">
                <div class="p-4 rounded-xl flex items-center justify-between" style="background-color: var(--teal-soft); border: 1px solid var(--teal); color: var(--teal);">
                    <div class="flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-16 py-12 text-sm" style="background-color: var(--bg-card); border-top: 1px solid var(--border-color);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-extrabold text-lg" style="background-color: var(--gold); color: var(--theme-bg);">T</div>
                    <span class="font-bold text-lg tracking-tight" style="color: var(--text-main) !important;">Testwise</span>
                </div>
                <p class="text-xs leading-relaxed mb-4" style="color: var(--text-muted);">
                    मध्य प्रदेश पुलिस आरक्षक (GD) 2026 भर्ती परीक्षा हेतु भारत का सर्वश्रेष्ठ डिजिटल लर्निंग प्लेटफॉर्म।
                </p>
                <div class="flex gap-3" style="color: var(--text-muted);">
                    <a href="#" class="hover:text-[var(--gold-deep)]"><i class="fa-brands fa-youtube text-lg"></i></a>
                    <a href="#" class="hover:text-[var(--gold-deep)]"><i class="fa-brands fa-telegram text-lg"></i></a>
                    <a href="#" class="hover:text-[var(--gold-deep)]"><i class="fa-brands fa-whatsapp text-lg"></i></a>
                </div>
            </div>

            <div>
                <h4 class="font-semibold mb-3 text-sm" style="color: var(--text-main) !important;">त्वरित लिंक्स</h4>
                <ul class="space-y-2 text-xs" style="color: var(--text-muted);">
                    <li><a href="{{ route('courses') }}" class="hover:text-[var(--gold-deep)]">MP Police GD 2026 कोर्स</a></li>
                    <li><a href="{{ route('free-content') }}" class="hover:text-[var(--gold-deep)]">निःशुल्क ट्रायल (First 3 Chapters)</a></li>
                    <li><a href="{{ route('verify-certificate') }}" class="hover:text-[var(--gold-deep)]">सर्टिफिकेट सत्यापन (Verify Certificate)</a></li>
                    <li><a href="{{ route('exam-info') }}" class="hover:text-[var(--gold-deep)]">परीक्षा पैटर्न व सिलेबस</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-3 text-sm" style="color: var(--text-main) !important;">पोर्टल एक्सेस</h4>
                <ul class="space-y-2 text-xs" style="color: var(--text-muted);">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-[var(--gold-deep)]">एडमिन कंसोल</a></li>
                        @else
                            <li><a href="{{ route('student.dashboard') }}" class="hover:text-[var(--gold-deep)]">स्टूडेंट डैशबोर्ड</a></li>
                        @endif
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="hover:text-[var(--gold-deep)] text-left">लॉगआउट (Logout)</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-[var(--gold-deep)]">लॉगिन (Log In)</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-[var(--gold-deep)]">रजिस्ट्रेशन (Register)</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-3 text-sm" style="color: var(--text-main) !important;">संपर्क एवं सहायता</h4>
                <p class="text-xs mb-2" style="color: var(--text-muted);"><i class="fa-solid fa-envelope mr-1.5"></i> support@testwise.in</p>
                <p class="text-xs mb-2" style="color: var(--text-muted);"><i class="fa-solid fa-phone mr-1.5"></i> +91 98765 43210</p>
                <p class="text-xs" style="color: var(--text-muted);"><i class="fa-solid fa-location-dot mr-1.5"></i> Bhopal, Madhya Pradesh</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 mt-8 pt-8 border-t flex flex-col sm:flex-row justify-between items-center text-xs gap-2" style="border-color: var(--border-color); color: var(--text-muted);">
            <p>© 2026 Testwise EdTech Platform. All rights reserved.</p>
            <p>Designed for MP Police Constable GD 2026 Batch Systems</p>
        </div>
    </footer>
</body>
</html>
