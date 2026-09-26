<?php
$models = ["Setting", "Faq", "Service", "Team", "Testimonial"];
foreach ($models as $model) {
    $path = "app/Models/$model.php";
    $content = file_get_contents($path);
    if (!strpos($content, "\$guarded")) {
        $content = str_replace("use HasFactory;", "use HasFactory;\n    protected \$guarded = [];", $content);
        file_put_contents($path, $content);
    }
}
echo "Done\n";

