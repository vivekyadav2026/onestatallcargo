<?php
$file = __DIR__ . '/resources/views/layouts/seller.blade.php';
$content = file_get_contents($file);

// Replace body
$content = str_replace('<body class="h-screen flex overflow-hidden bg-[#f3f5f9]" x-data="{ sidebarHover: false, isPinned: false }">', '<body class="h-screen flex overflow-hidden bg-[#f3f5f9]" x-data="{ sidebarHover: false, isPinned: false, mobileSidebarOpen: false }">', $content);

// Update sidebar classes for mobile
$searchSidebar = '<aside 
        @mouseenter="sidebarHover = true" 
        @mouseleave="sidebarHover = false"
        :class="(sidebarHover || isPinned) ? \'w-64 shadow-2xl z-40\' : \'w-20 z-30\'" 
        class="bg-white border-r border-gray-200 flex flex-col py-5 shrink-0 transition-all duration-300 ease-in-out relative group">';
        
$replaceSidebar = '<div x-show="mobileSidebarOpen" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 md:hidden" @click="mobileSidebarOpen = false" style="display: none;"></div>
    <aside 
        @mouseenter="sidebarHover = true" 
        @mouseleave="sidebarHover = false"
        :class="[
            (sidebarHover || isPinned) ? \'w-64 shadow-2xl\' : \'w-20\',
            mobileSidebarOpen ? \'translate-x-0 absolute h-full z-50\' : \'-translate-x-full absolute md:relative md:translate-x-0 z-30\'
        ]" 
        class="bg-white border-r border-gray-200 flex flex-col py-5 shrink-0 transition-all duration-300 ease-in-out relative group">';

$content = str_replace($searchSidebar, $replaceSidebar, $content);

// Add hamburger button to header
$hamburger = '<!-- Mobile Menu Toggle -->
            <button @click="mobileSidebarOpen = true" class="md:hidden mr-4 text-gray-500 hover:text-[#4338ca] focus:outline-none">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <!-- Left: Global Search -->';
$content = str_replace('<!-- Left: Global Search -->', $hamburger, $content);

file_put_contents($file, $content);
echo "Done.\n";
