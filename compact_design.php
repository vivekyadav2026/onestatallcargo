<?php
$base_path = __DIR__;
$files_to_process = [];

// Recursive function to get all blade files
function getBladeFiles($dir, &$results = []) {
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (str_ends_with($path, '.blade.php')) {
                $results[] = $path;
            }
        } else if ($value != "." && $value != "..") {
            getBladeFiles($path, $results);
        }
    }
    return $results;
}

$files = getBladeFiles($base_path . '/resources/views');

$replacements = [
    'py-24' => 'py-12',
    'py-20' => 'py-10',
    'py-16' => 'py-8',
    'pt-24' => 'pt-12',
    'pb-24' => 'pb-12',
    'pt-20' => 'pt-10',
    'pb-20' => 'pb-10',
    'mb-16' => 'mb-8',
    'mb-12' => 'mb-6',
    'gap-16' => 'gap-8',
    'gap-12' => 'gap-6',
    'space-y-24' => 'space-y-12',
    'space-y-16' => 'space-y-8',
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $original_content = $content;
    
    // Replace spacings
    foreach ($replacements as $old => $new) {
        // Use word boundaries to avoid replacing parts of other classes
        $content = preg_replace("/\b" . preg_quote($old, '/') . "\b/", $new, $content);
    }
    
    if (strpos($file, 'welcome.blade.php') !== false) {
        // Change hero section to use the image
        $hero_pattern = '/<section class="relative bg-brand-navy overflow-hidden">.*?<\/section>/s';
        
        $new_hero = <<<EOD
<section class="relative bg-brand-navy overflow-hidden">
    <!-- Hero Image Background -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/logistics_hero_banner.jpg') }}" alt="OneStall Cargo Logistics Hub" class="w-full h-full object-cover object-center opacity-40">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-navy via-brand-navy/90 to-transparent"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-16 pb-20 lg:pt-24 lg:pb-24 flex flex-col lg:flex-row items-center">
        <!-- Hero Text -->
        <div class="w-full lg:w-3/5 lg:pr-12 text-center lg:text-left">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                One Platform. <br>
                <span class="text-brand-yellow drop-shadow-md">Every Shipment.</span>
            </h1>
            <p class="mt-6 text-lg text-gray-200 max-w-2xl mx-auto lg:mx-0 font-medium leading-relaxed drop-shadow">
                B2C shipping, B2B cargo, international logistics, and technology-powered delivery through a single unified infrastructure.
            </p>
            
            <div class="mt-8 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3 rounded-lg bg-brand-yellow text-brand-navy font-bold text-lg hover:bg-brand-yellowHover transition shadow-lg text-center">
                    Start Shipping
                </a>
                <a href="{{ route('docs') }}" class="px-8 py-3 rounded-lg bg-white/10 backdrop-blur-sm border-2 border-white/50 text-white font-bold text-lg hover:bg-white/20 transition text-center">
                    View API Docs
                </a>
            </div>

            <!-- Quick Track -->
            <div class="mt-10 bg-white/10 p-2 rounded-xl backdrop-blur-md max-w-md mx-auto lg:mx-0 border border-white/30 shadow-2xl">
                <form action="{{ route('track.post') }}" method="POST" class="flex items-center">
                    @csrf
                    <div class="pl-4 pr-2 text-brand-yellow"><i class="fa-solid fa-cube"></i></div>
                    <input type="text" name="awb" placeholder="Enter AWB / Shipment ID" class="w-full bg-transparent border-none text-white placeholder-gray-300 focus:outline-none focus:ring-0 py-2 font-medium" required>
                    <button type="submit" class="bg-brand-blue text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-800 transition shadow">Track</button>
                </form>
            </div>
        </div>

        <!-- Right Side UI Overlay (Compact) -->
        <div class="w-full lg:w-2/5 mt-12 lg:mt-0 relative hidden md:block">
            <!-- Simulated UI -->
            <div class="bg-white/95 backdrop-blur rounded-xl shadow-2xl overflow-hidden border border-gray-200/50 transform lg:rotate-2 hover:rotate-0 transition duration-500 scale-95 origin-right">
                <div class="bg-gray-100/80 px-4 py-2 border-b border-gray-200 flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                    <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                    <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                    <div class="ml-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Live Status</div>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">AWB Number</p>
                            <h3 class="text-lg font-extrabold text-brand-navy">OSC92847163</h3>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded">In Transit</span>
                    </div>

                    <!-- Timeline Visual -->
                    <div class="relative pl-5 border-l-2 border-gray-200 space-y-4">
                        <div class="relative">
                            <div class="absolute -left-[27px] w-3 h-3 bg-brand-navy rounded-full border-2 border-white"></div>
                            <p class="text-[10px] font-bold text-gray-400">09:42 AM</p>
                            <p class="text-xs font-bold text-gray-800">Picked Up</p>
                        </div>
                        <div class="relative">
                            <div class="absolute -left-[27px] w-3 h-3 bg-brand-navy rounded-full border-2 border-white"></div>
                            <p class="text-[10px] font-bold text-gray-400">02:15 PM</p>
                            <p class="text-xs font-bold text-gray-800">Origin Hub Scan</p>
                        </div>
                        <div class="relative">
                            <div class="absolute -left-[27px] w-3 h-3 bg-brand-yellow rounded-full border-2 border-white animate-pulse"></div>
                            <p class="text-[10px] font-bold text-brand-yellow">Now</p>
                            <p class="text-xs font-bold text-gray-800">In Transit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
EOD;

        $content = preg_replace($hero_pattern, $new_hero, $content);
    }
    
    if ($content !== $original_content) {
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
