<?php
$content = file_get_contents("resources/views/layouts/hub.blade.php");
$target = '<a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-motorcycle w-5"></i> Fleet Management
            </a>';
$replacement = '<a href="{{ route(\'hub.fleet.index\') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-bold transition">
                <i class="fa-solid fa-motorcycle w-5"></i> Fleet Management
            </a>';
$content = str_replace($target, $replacement, $content);
file_put_contents("resources/views/layouts/hub.blade.php", $content);
