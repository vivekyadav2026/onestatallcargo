<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50 theme-navy">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Student Portal - Testwise')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>

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
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, .dash-head h1, .display {
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -.01em;
            font-weight: 700;
        }

        nav a {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
        }

        /* Forms Layout & Labels styling */
        form label:not(.inline-flex):not(.flex-row),
        .field label:not(.inline-flex):not(.flex-row) {
            display: flex;
            justify-content: space-between;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 7px;
            font-family: 'Inter', sans-serif;
        }

        /* Global inputs & form elements styling overrides */
        input[type="text"], input[type="number"], input[type="email"], input[type="password"], input[type="date"], input[type="time"], input[type="search"], select, textarea {
            background-color: var(--bg-card);
            border: 1px solid var(--border-hard);
            border-radius: 10px;
            padding: 9px 13px;
            font-size: 13px;
            color: var(--text-main);
            box-shadow: none;
            transition: all 0.2s ease;
            width: 100%;
        }

        input[type="text"]:focus, input[type="number"]:focus, input[type="email"]:focus, input[type="password"]:focus, input[type="date"]:focus, input[type="time"]:focus, input[type="search"]:focus, select:focus, textarea:focus {
            border-color: var(--gold);
            outline: none;
            box-shadow: 0 0 0 3px rgba(217, 154, 43, 0.15);
            background-color: #FFFFFF;
        }

        /* Buttons styling */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 13px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-gold, .btn-primary {
            background-color: var(--gold) !important;
            color: #17233F !important;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .btn-gold:hover, .btn-primary:hover {
            background-color: var(--gold-deep) !important;
            color: #ffffff !important;
        }

        .btn-secondary {
            background-color: #FFFFFF;
            color: var(--text-main);
            border: 1px solid var(--border-hard);
        }
        .btn-secondary:hover {
            background-color: var(--bg-main);
            border-color: var(--text-muted);
        }

        /* Table & Panels styling */
        .panel, .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .sidebar-expanded { width: 16rem !important; }
        .sidebar-collapsed { width: 4.5rem !important; }

        .main-expanded { padding-left: 16rem !important; }
        .main-collapsed { padding-left: 4.5rem !important; }
        
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--theme-text);
            font-size: 14px;
            transition: all 0.2s;
        }
        .sidebar-item:hover {
            background-color: var(--theme-hover);
            color: var(--theme-hover-text);
        }
        .sidebar-item.active {
            background-color: var(--theme-hover);
            color: var(--theme-active);
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .main-expanded, .main-collapsed { padding-left: 0 !important; }
            .sidebar-expanded, .sidebar-collapsed { width: 16rem !important; }
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-gray-900" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    <!-- Mobile sidebar off-canvas -->
    <div x-show="sidebarOpen" class="relative z-40 md:hidden" role="dialog" aria-modal="true" style="display: none;">
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>

        <div class="fixed inset-0 z-40 flex">
            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex w-full max-w-xs flex-1 flex-col pb-4 pt-5" style="background-color: var(--theme-bg);">
                
                <div class="absolute right-0 top-0 -mr-12 pt-2">
                    <button type="button" @click="sidebarOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <i class="fa-solid fa-xmark text-white text-xl"></i>
                    </button>
                </div>

                <div class="flex flex-shrink-0 items-center justify-between px-4 mb-6">
                    <div class="text-white flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center p-1 shadow-sm shrink-0">
                            <div class="w-full h-full bg-[var(--gold)] rounded-md flex items-center justify-center text-[var(--theme-bg)] font-bold">T</div>
                        </div>
                        <div class="flex flex-col">
                            <span class="truncate font-extrabold text-base tracking-tight leading-tight" style="color: white !important;">Testwise</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider" style="color: var(--theme-active) !important;">Student Portal</span>
                        </div>
                    </div>
                </div>

                <div class="mt-2 h-0 flex-1 overflow-y-auto">
                    <nav class="space-y-1 px-2">
                        <a href="{{ route('student.my-courses') }}" class="sidebar-item {{ request()->routeIs('student.my-courses') ? 'active' : '' }}">
                            <i class="fa-solid fa-layer-group w-4 text-center"></i>
                            <span>My Courses</span>
                        </a>
                        <a href="{{ route('student.dashboard') }}" class="sidebar-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                            <span>Course Overview</span>
                        </a>
                        <a href="{{ route('student.chapter-tests') }}" class="sidebar-item {{ request()->routeIs('student.chapter-tests') || request()->routeIs('student.course') ? 'active' : '' }}">
                            <i class="fa-solid fa-book-open w-4 text-center"></i>
                            <span>Syllabus & Notes</span>
                        </a>
                        <a href="{{ route('student.mock-tests') }}" class="sidebar-item {{ request()->routeIs('student.mock-tests') ? 'active' : '' }}">
                            <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i>
                            <span>Mock Tests</span>
                        </a>

                        <div class="px-2 mt-6 mb-2">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-[var(--theme-text)]">Analysis</div>
                        </div>

                        <a href="{{ route('student.mistakes') }}" class="sidebar-item {{ request()->routeIs('student.mistakes') ? 'active' : '' }}">
                            <i class="fa-solid fa-triangle-exclamation w-4 text-center text-[var(--rose)]"></i>
                            <span>Error Workbook</span>
                        </a>
                        <a href="{{ route('student.weak-topics') }}" class="sidebar-item {{ request()->routeIs('student.weak-topics') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-line w-4 text-center text-[var(--gold)]"></i>
                            <span>Weak Topics</span>
                        </a>
                        <a href="{{ route('student.performance') }}" class="sidebar-item {{ request()->routeIs('student.performance') ? 'active' : '' }}">
                            <i class="fa-solid fa-award w-4 text-center text-[var(--teal)]"></i>
                            <span>Analytics</span>
                        </a>
                        <a href="{{ route('student.certificate') }}" class="sidebar-item {{ request()->routeIs('student.certificate') ? 'active' : '' }}">
                            <i class="fa-solid fa-certificate w-4 text-center"></i>
                            <span>Certificate</span>
                        </a>
                        
                        <div class="px-4 mb-2 mt-4">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-[var(--theme-text)]">Account</div>
                        </div>
                        
                        <a href="{{ route('student.profile') }}" class="sidebar-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-pen w-4 text-center"></i>
                            <span>My Profile</span>
                        </a>
                    </nav>
                </div>
                
                <div class="flex flex-shrink-0 border-t border-white/10 p-4">
                    <div class="flex items-center">
                        <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[var(--theme-active)] text-[var(--theme-active-text)] font-bold shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white truncate max-w-[130px]" style="color: white !important;">{{ $user->name }}</p>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-medium text-[var(--theme-text)] hover:text-white transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Static sidebar -->
    <div :class="sidebarCollapsed ? 'sidebar-collapsed' : 'sidebar-expanded'" class="sidebar-expanded md:w-64 hidden md:flex md:fixed md:inset-y-0 md:flex-col transition-all duration-300 z-30">
        <div class="flex min-h-0 flex-1 flex-col" style="background-color: var(--theme-bg);">
            <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
                <div class="flex flex-shrink-0 items-center justify-between px-4 mb-6">
                    <div class="text-white flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center p-1 shadow-sm shrink-0">
                            <div class="w-full h-full bg-[var(--gold)] rounded-md flex items-center justify-center text-[var(--theme-bg)] font-bold">T</div>
                        </div>
                        <div x-show="!sidebarCollapsed" class="flex flex-col">
                            <span class="truncate font-extrabold text-base tracking-tight leading-tight" style="color: white !important;">Testwise</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider" style="color: var(--theme-active) !important;">Student Portal</span>
                        </div>
                    </div>
                </div>

                <div class="px-4 mb-2">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-[var(--theme-text)]">Main Navigation</div>
                </div>

                <nav class="mt-2 flex-1 space-y-1 px-2">
                    <a href="{{ route('student.my-courses') }}" class="sidebar-item {{ request()->routeIs('student.my-courses') ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group w-4 text-center"></i>
                        <span>My Courses</span>
                    </a>
                    <a href="{{ route('student.dashboard') }}" class="sidebar-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                        <span>Course Overview</span>
                    </a>
                    <a href="{{ route('student.chapter-tests') }}" class="sidebar-item {{ request()->routeIs('student.chapter-tests') || request()->routeIs('student.course') ? 'active' : '' }}">
                        <i class="fa-solid fa-book-open w-4 text-center"></i>
                        <span>Syllabus & Notes</span>
                    </a>
                    <a href="{{ route('student.mock-tests') }}" class="sidebar-item {{ request()->routeIs('student.mock-tests') ? 'active' : '' }}">
                        <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i>
                        <span>Mock Tests</span>
                    </a>

                    <div class="px-2 mt-6 mb-2">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-[var(--theme-text)]">Analysis</div>
                    </div>

                    <a href="{{ route('student.mistakes') }}" class="sidebar-item {{ request()->routeIs('student.mistakes') ? 'active' : '' }}">
                        <i class="fa-solid fa-triangle-exclamation w-4 text-center text-[var(--rose)]"></i>
                        <span>Error Workbook</span>
                    </a>
                    <a href="{{ route('student.weak-topics') }}" class="sidebar-item {{ request()->routeIs('student.weak-topics') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line w-4 text-center text-[var(--gold)]"></i>
                        <span>Weak Topics</span>
                    </a>
                    <a href="{{ route('student.performance') }}" class="sidebar-item {{ request()->routeIs('student.performance') ? 'active' : '' }}">
                        <i class="fa-solid fa-award w-4 text-center text-[var(--teal)]"></i>
                        <span>Analytics</span>
                    </a>
                    <a href="{{ route('student.certificate') }}" class="sidebar-item {{ request()->routeIs('student.certificate') ? 'active' : '' }}">
                        <i class="fa-solid fa-certificate w-4 text-center"></i>
                        <span>Certificate</span>
                    </a>
                    
                    <div class="px-4 mb-2 mt-4">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-[var(--theme-text)]">Account</div>
                    </div>
                    
                    <a href="{{ route('student.profile') }}" class="sidebar-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-pen w-4 text-center"></i>
                        <span>My Profile</span>
                    </a>
                </nav>
            </div>
            
            <div class="flex flex-shrink-0 border-t border-white/10 p-4">
                <div class="group block w-full flex-shrink-0">
                    <div class="flex items-center" :class="sidebarCollapsed ? 'justify-center' : ''">
                        <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[var(--theme-active)] text-[var(--theme-active-text)] font-bold shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div x-show="!sidebarCollapsed" class="ml-3">
                            <p class="text-sm font-medium text-white truncate max-w-[130px]" style="color: white !important;">{{ $user->name }}</p>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-medium text-[var(--theme-text)] hover:text-white transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div :class="sidebarCollapsed ? 'main-collapsed' : 'main-expanded'" class="main-expanded md:pl-64 flex flex-1 flex-col transition-all duration-300">
        <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white shadow-sm border-b border-gray-200">
            <button type="button" @click="sidebarOpen = true" class="border-r border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <div class="flex flex-1 justify-between px-4 items-center">
                <div class="flex items-center gap-3">
                    @php
                        $activeCourses = \App\Models\Course::where('is_active', true)->get();
                        $currentCourseId = session('current_course_id') ?? ($user->enrollments()->first()->course_id ?? 1);
                    @endphp
                    
                    <form action="{{ route('student.switch-course') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <span class="text-xs font-bold text-gray-500 hidden sm:inline"><i class="fa-solid fa-graduation-cap text-[var(--gold-deep)]"></i> Active Exam:</span>
                        <select name="course_id" onchange="this.form.submit()" class="text-xs font-extrabold py-1.5 px-3 rounded-xl border border-gray-300 bg-gray-50 text-gray-800 focus:outline-none cursor-pointer shadow-sm">
                            @foreach($activeCourses as $ac)
                                <option value="{{ $ac->id }}" {{ $ac->id == $currentCourseId ? 'selected' : '' }}>
                                    {{ $ac->title_hi }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="flex items-center gap-3">
                    @php
                        $currentIsPro = $user->isProFor($currentCourseId);
                    @endphp
                    @if(!$currentIsPro)
                        <button type="button" onclick="payWithRazorpay()" class="btn btn-gold text-xs shadow-sm cursor-pointer">
                            <i class="fa-solid fa-bolt"></i> Unlock Pro ₹499
                        </button>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5" style="background: var(--teal-soft); color: var(--teal); border: 1px solid var(--teal);">
                            <i class="fa-solid fa-circle-check"></i> PRO UNLOCKED
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <main class="flex-1">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    @if(session('error'))
                        <div class="mb-6 p-4 rounded-xl" style="background-color: var(--rose-soft); border: 1px solid var(--rose); color: var(--rose);">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl" style="background-color: var(--teal-soft); border: 1px solid var(--teal); color: var(--teal);">
                            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                        </div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Razorpay Checkout SDK Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function payWithRazorpay() {
            fetch("{{ route('razorpay.create-order') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    course_id: "{{ $currentCourseId }}"
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    var options = {
                        "key": data.key,
                        "amount": data.amount,
                        "currency": data.currency,
                        "name": "Testwise Learning",
                        "description": "Unlock Pro Subscription - " + data.course_name,
                        "order_id": "",
                        "handler": function (response){
                            verifyRazorpayPayment(response, data.course_id);
                        },
                        "prefill": {
                            "name": data.user_name,
                            "email": data.user_email,
                            "contact": data.user_phone
                        },
                        "theme": {
                            "color": "#fbbf24"
                        }
                    };
                    var rzp = new Razorpay(options);
                    rzp.open();
                } else {
                    alert('Error creating order: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(err => {
                console.error(err);
                // Fallback simulation mode
                if (confirm('Deploying simulated payment gateway. Confirm payment of ₹499?')) {
                    verifyRazorpayPayment({
                        razorpay_payment_id: 'pay_sim_' + Date.now(),
                        razorpay_order_id: 'ORD_SIM_' + Date.now()
                    }, "{{ $currentCourseId }}");
                }
            });
        }

        function verifyRazorpayPayment(response, courseId) {
            fetch("{{ route('razorpay.verify-payment') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature || '',
                    course_id: courseId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.href = data.redirect_url;
                } else {
                    alert('Payment verification failed.');
                }
            });
        }
    </script>

    <!-- Alpine JS for sidebar state -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
