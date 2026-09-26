<?php
$file = __DIR__ . '/app/Models/Rate.php';
$content = file_get_contents($file);

$content = str_replace("'zone_type',", "'zone_type',\n        'courier_id',", $content);

$rel = <<<'PHP'

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }
}
PHP;

$content = preg_replace('/}\s*$/', $rel, $content);
file_put_contents($file, $content);
echo "Updated Rate model.\n";
