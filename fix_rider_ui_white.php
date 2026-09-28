<?php
// Fix COD View CSS
$cod = file_get_contents("resources/views/rider/cod.blade.php");
$cod = str_replace('bg-[var(--gold)]', 'bg-[#D4AF37]', $cod);
file_put_contents("resources/views/rider/cod.blade.php", $cod);

// Fix Rider Layout Header
$layout = file_get_contents("resources/views/layouts/rider.blade.php");
$targetHeader = '<div class="font-bold leading-tight ml-2">
                <div class="text-[10px] text-green-400"><i class="fa-solid fa-circle text-[8px]"></i> Online</div>
            </div>';
$replacementHeader = '<div class="font-bold leading-tight ml-2">
                <div class="text-sm">{{ Auth::user()->name ?? \'Rider\' }}</div>
                <div class="text-[10px] text-green-400"><i class="fa-solid fa-circle text-[8px]"></i> Online</div>
            </div>';
$layout = str_replace($targetHeader, $replacementHeader, $layout);
file_put_contents("resources/views/layouts/rider.blade.php", $layout);
