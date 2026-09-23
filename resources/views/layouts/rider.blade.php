<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Rider App - OneStall Cargo</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #f1f5f9; -webkit-tap-highlight-color: transparent; }
    </style>
</head>
<body class="pb-20">
    <!-- Mobile Header -->
    <header class="bg-[#1e293b] text-white p-4 sticky top-0 z-50 shadow-md flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-[#FFD700] rounded-full text-black font-bold flex items-center justify-center">R</div>
            <div class="font-bold leading-tight">
                <div>OneStall Rider App</div>
                <div class="text-[10px] text-green-400"><i class="fa-solid fa-circle text-[8px]"></i> Online</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">@csrf <button class="text-white opacity-70"><i class="fa-solid fa-sign-out-alt"></i></button></form>
    </header>

    <main class="p-4">
        @if(session('success'))
            <div class="mb-4 p-3 rounded-lg text-sm font-bold bg-green-100 text-green-800"><i class="fa-solid fa-check"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 rounded-lg text-sm font-bold bg-red-100 text-red-800"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <!-- Bottom Nav -->
    <nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 flex justify-around p-3 pb-safe z-50">
        <a href="#" class="flex flex-col items-center text-[#D4AF37]"><i class="fa-solid fa-list-check text-xl mb-1"></i><span class="text-[10px] font-bold">Tasks</span></a>
        <a href="#" class="flex flex-col items-center text-gray-400"><i class="fa-solid fa-camera text-xl mb-1"></i><span class="text-[10px] font-bold">Scan</span></a>
        <a href="#" class="flex flex-col items-center text-gray-400"><i class="fa-solid fa-wallet text-xl mb-1"></i><span class="text-[10px] font-bold">COD</span></a>
        <a href="#" class="flex flex-col items-center text-gray-400"><i class="fa-solid fa-user text-xl mb-1"></i><span class="text-[10px] font-bold">Profile</span></a>
    </nav>
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
