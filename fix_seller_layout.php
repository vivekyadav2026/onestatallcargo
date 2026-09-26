<?php
$file = __DIR__ . '/resources/views/layouts/seller.blade.php';
$content = file_get_contents($file);

// Replace the aside opening tag with a responsive one
$newAside = '
    <!-- Mobile Overlay -->
    <div x-show="mobileSidebarOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/50 z-40 md:hidden" style="display: none;" @click="mobileSidebarOpen = false"></div>

    <!-- Hover-to-Expand Left Sidebar -->
    <aside 
        @mouseenter="sidebarHover = true" 
        @mouseleave="sidebarHover = false"
        :class="{
            \'w-64 shadow-2xl z-50 translate-x-0 fixed inset-y-0 left-0 md:relative\': (sidebarHover || isPinned || mobileSidebarOpen),
            \'w-64 -translate-x-full fixed inset-y-0 left-0 md:relative md:translate-x-0 md:w-20 z-30\': !(sidebarHover || isPinned || mobileSidebarOpen)
        }"
        class="bg-white border-r border-gray-200 flex flex-col py-5 shrink-0 transition-all duration-300 ease-in-out"
        style="height: 100vh;">
';

$content = preg_replace('/<!-- Hover-to-Expand Left Sidebar -->.*?<aside[^>]*>/is', ltrim($newAside), $content);

// Ensure the pin button is hidden on mobile
$content = preg_replace('/(class="absolute -right-3 top-6)/', '$1 md:flex hidden', $content);

file_put_contents($file, $content);
echo "Fixed.\n";
