<?php
$content = file_get_contents("resources/views/layouts/admin.blade.php");

$target = '<a href="{{ route(\'admin.hubs.index\') }}" class="sidebar-item {{ request()->routeIs(\'admin.hubs.*\') ? \'active\' : \'\' }}"><i class="fa-solid fa-building w-4 text-center"></i> <span>Hubs / Franchise</span></a>';

$replacement = '<a href="{{ route(\'admin.hubs.index\') }}" class="sidebar-item {{ request()->routeIs(\'admin.hubs.*\') ? \'active\' : \'\' }}"><i class="fa-solid fa-building w-4 text-center"></i> <span>Hubs / Franchise</span></a>
                <a href="{{ route(\'admin.pincodes.index\') }}" class="sidebar-item {{ request()->routeIs(\'admin.pincodes.*\') ? \'active\' : \'\' }}"><i class="fa-solid fa-map-location-dot w-4 text-center"></i> <span>Pincode Mapping</span></a>';

$content = str_replace($target, $replacement, $content);
file_put_contents("resources/views/layouts/admin.blade.php", $content);
