<?php

$base_path = __DIR__;
$routes_file = $base_path . "/routes/web.php";

$new_routes = <<<EOD

// Public Redesign Routes
Route::get('/solutions/b2c-shipping', function () { return view('public.solutions.b2c'); })->name('solutions.b2c');
Route::get('/solutions/b2b-cargo', function () { return view('public.solutions.b2b'); })->name('solutions.b2b');
Route::get('/solutions/international-shipping', function () { return view('public.solutions.international'); })->name('solutions.international');
Route::get('/solutions/quick-delivery', function () { return view('public.solutions.quick'); })->name('solutions.quick');
Route::get('/solutions/courier-aggregation', function () { return view('public.solutions.aggregation'); })->name('solutions.aggregation');
Route::get('/solutions/ecommerce-integration', function () { return view('public.solutions.ecommerce'); })->name('solutions.ecommerce');

Route::get('/platform/video-evidence', function () { return view('public.platform.evidence'); })->name('platform.evidence');
Route::get('/platform/ndr-management', function () { return view('public.platform.ndr'); })->name('platform.ndr');
Route::get('/platform/rto-management', function () { return view('public.platform.rto'); })->name('platform.rto');
Route::get('/platform/cod-settlement', function () { return view('public.platform.cod'); })->name('platform.cod');
Route::get('/platform/live-tracking', function () { return view('public.platform.tracking'); })->name('platform.tracking');
Route::get('/platform/awb-labels', function () { return view('public.platform.awb'); })->name('platform.awb');

Route::get('/developers', function () { return view('public.developers.index'); })->name('developers');
Route::get('/docs', function () { return view('public.developers.docs'); })->name('docs');

Route::get('/corporate', function () { return view('public.corporate'); })->name('corporate');
Route::get('/partners', function () { return view('public.partners'); })->name('partners');
Route::get('/about', function () { return view('public.about'); })->name('about');
Route::get('/faq', function () { return view('public.faq'); })->name('faq');
Route::get('/help', function () { return view('public.help'); })->name('help');
EOD;

$content = file_get_contents($routes_file);

if (strpos($content, '// Public Redesign Routes') === false) {
    $content = str_replace('// Auth Routes', $new_routes . "\n// Auth Routes", $content);
    file_put_contents($routes_file, $content);
    echo "Routes updated.\n";
} else {
    echo "Routes already updated.\n";
}

$dirs_to_create = [
    "resources/views/public",
    "resources/views/public/solutions",
    "resources/views/public/platform",
    "resources/views/public/developers"
];

foreach ($dirs_to_create as $d) {
    $path = $base_path . "/" . $d;
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
        echo "Created $d\n";
    }
}
