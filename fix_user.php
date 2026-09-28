<?php
$content = file_get_contents('app/Models/User.php');
if (strpos($content, 'function rider()') === false) {
    $content = preg_replace('/}\s*$/', "\n    public function rider()\n    {\n        return \$this->hasOne(Rider::class, 'user_id');\n    }\n}\n", $content);
    file_put_contents('app/Models/User.php', $content);
}
