<?php
// Chỉ hiển thị giao diện demo cho client, không xử lý nghiệp vụ.
$demoRoute = $_GET['route'] ?? '/';
if (!is_string($demoRoute)) $demoRoute = '/404';
$GLOBALS['prefix'] = '';
require __DIR__ . '/demo/render.php';
