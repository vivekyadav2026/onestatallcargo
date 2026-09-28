<?php
$content = file_get_contents('test_conc.php');
$content = str_replace('clone $hubId', '$hubId', $content);
file_put_contents('test_conc.php', $content);
