<?php
$path = "resources/views/admin/couriers/index.blade.php";
$content = file_get_contents($path);
$content = str_replace(['???', '?,?'], '?', $content);
file_put_contents($path, $content);
