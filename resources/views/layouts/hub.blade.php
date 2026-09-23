<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hub Management - OneStall Cargo</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-[Nunito]">
    <div class="min-h-screen flex flex-col">
        <!-- Top Nav -->
        <header class="bg-[#1e293b] text-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-[#FFD700] text-gray-900 flex items-center justify-center font-bold">O</div>
                        <div>
                            <div class="font-bold text-lg leading-tight">OneStall Hub Center</div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-widest">{{ Auth::user()->name ?? 'Hub Manager' }}</div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-sm font-bold text-gray-300 hover:text-white px-3 py-2 rounded border border-gray-600 hover:border-gray-400 transition"><i class="fa-solid fa-power-off"></i> Exit</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl font-bold bg-green-50 border border-green-500 text-green-700"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl font-bold bg-red-50 border border-red-500 text-red-700"><i class="fa-solid fa-times-circle"></i> {{ session('error') }}</div>
            @endif
            
            @yield('content')
        </main>
    </div>
</body>
</html>

