<?php
$file = __DIR__ . '/resources/views/layouts/seller.blade.php';
$content = file_get_contents($file);

$newDropdown = <<<'HTML'
                    <div x-show="openProfile" @click.away="openProfile = false" class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-2xl shadow-xl py-2 z-50 text-left" style="display: none;" x-transition>
                        
                        <!-- Header inside dropdown -->
                        <div class="px-4 py-3 border-b border-gray-100 mb-2">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-[10px] text-gray-500 truncate mt-0.5">{{ Auth::user()->email ?? '' }}</p>
                        </div>

                        <a href="{{ route('seller.settings') }}?view=company" class="flex items-center px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:text-[#4338ca] transition">
                            <i class="fa-solid fa-user-tie w-5 text-gray-400"></i> Manage Profile
                        </a>
                        <a href="{{ route('seller.settings') }}" class="flex items-center px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 hover:text-[#4338ca] transition mb-2">
                            <i class="fa-solid fa-gear w-5 text-gray-400"></i> Global Settings
                        </a>
                        
                        <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-100 pt-2">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 hover:text-red-700 transition outline-none flex items-center">
                                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-red-400"></i> Logout
                            </button>
                        </form>
                    </div>
HTML;

$content = preg_replace('/<div x-show="openProfile" @click\.away="openProfile = false"[^>]*>.*?<\/form>\s*<\/div>/is', ltrim($newDropdown), $content);
file_put_contents($file, $content);
echo "Fixed.\n";
