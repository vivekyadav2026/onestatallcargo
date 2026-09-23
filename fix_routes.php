<?php
$files = [
    'resources/views/admin/dashboard.blade.php',
    'resources/views/layouts/admin.blade.php',
    'resources/views/layouts/app.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Replace all admin routes except dashboard
        $content = preg_replace_callback('/\{\{\s*route\(\'admin\.([a-zA-Z0-9\-]+)\'\)\s*\}\}/', function($matches) {
            if ($matches[1] === 'dashboard') {
                return "{{ route('admin.dashboard') }}";
            }
            return "#";
        }, $content);
        
        // Let's also do student routes since those will crash too if any exist
        $content = preg_replace('/\{\{\s*route\(\'student\.[a-zA-Z0-9\-]+\'\)\s*\}\}/', '#', $content);

        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
