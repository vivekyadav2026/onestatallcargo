<?php
$file = __DIR__ . '/resources/views/admin/banners/index.blade.php';
$content = file_get_contents($file);

$errorsHtml = <<<'HTML'
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Seller Dashboard Banner Manager</h1>
            <p class="text-xs text-gray-500 mt-1">Configure live promotional banners displayed to sellers on their dashboard</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 text-sm mb-4">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 text-red-700 font-bold rounded-xl border border-red-200 text-sm mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
HTML;

$content = preg_replace('/<!-- Page Header -->.*?<\/div>\s*<\/div>/is', ltrim($errorsHtml), $content);
file_put_contents($file, $content);
echo "Added error display.\n";
