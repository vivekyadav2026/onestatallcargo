<?php
$content = file_get_contents("resources/views/layouts/hub.blade.php");

$target = '<div class="p-4 border-t border-gray-700/50">
            <form action="{{ route(\'logout\') }}" method="POST">';
            
$replacement = '<a href="{{ route(\'hub.profile\') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-user-gear w-5"></i> Profile & Settings
            </a>
        </div>
        
        <div class="p-4 border-t border-gray-700/50">
            <form action="{{ route(\'logout\') }}" method="POST">';

$content = str_replace($target, $replacement, $content);
file_put_contents("resources/views/layouts/hub.blade.php", $content);
