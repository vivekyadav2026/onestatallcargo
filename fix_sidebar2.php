<?php
$content = file_get_contents("resources/views/layouts/hub.blade.php");
$content = preg_replace('/href="#" class="([^"]*)">\s*<i class="fa-solid fa-truck-pickup w-5"><\/i>\s*Pickups/s', 'href="{{ route(\'hub.assignments.pickups\') }}" class="$1"> <i class="fa-solid fa-truck-pickup w-5"></i> Pickups', $content);
$content = preg_replace('/href="#" class="([^"]*)">\s*<i class="fa-solid fa-box-open w-5"><\/i>\s*Deliveries/s', 'href="{{ route(\'hub.assignments.deliveries\') }}" class="$1"> <i class="fa-solid fa-box-open w-5"></i> Deliveries', $content);
$content = preg_replace('/href="#" class="([^"]*)">\s*<i class="fa-solid fa-rotate-left w-5"><\/i>\s*NDR \& RTO/s', 'href="{{ route(\'hub.ndr.index\') }}" class="$1"> <i class="fa-solid fa-rotate-left w-5"></i> NDR & RTO', $content);
$content = preg_replace('/href="#" class="([^"]*)">\s*<i class="fa-solid fa-wallet w-5"><\/i>\s*Wallet \& Payout/s', 'href="{{ route(\'hub.wallet.index\') }}" class="$1"> <i class="fa-solid fa-wallet w-5"></i> Wallet & Payout', $content);
file_put_contents("resources/views/layouts/hub.blade.php", $content);
