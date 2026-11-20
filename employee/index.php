<?php
// Chỉ hiển thị giao diện demo, không xử lý nghiệp vụ.
$demoRoute = $_GET['route'] ?? '/employee';
if (!is_string($demoRoute)) $demoRoute = '/404';
$GLOBALS['prefix'] = '../';
require __DIR__ . '/../demo/render.php';
