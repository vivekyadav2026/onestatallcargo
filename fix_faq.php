<?php
$file = __DIR__ . '/resources/views/public/faq.blade.php';
$content = file_get_contents($file);

// Replace the hardcoded JS array with dynamic DB data
$search = "        faqs: [";
$endSearch = "        ]\n    }\n}\n</script>";

$startPos = strpos($content, $search);
$endPos = strpos($content, $endSearch, $startPos) + strlen($endSearch);

$dynamicJS = "        faqs: [
            @foreach(\$faqs as \$faq)
            {
                category: 'general',
                q: `{!! addslashes(\$faq->question) !!}`,
                a: `{!! addslashes(\$faq->answer) !!}`
            },
            @endforeach
        ]
    }
}
</script>";

if ($startPos !== false) {
    $content = substr_replace($content, $dynamicJS, $startPos, $endPos - $startPos);
    file_put_contents($file, $content);
    echo "Done.\n";
} else {
    echo "Not found.\n";
}
