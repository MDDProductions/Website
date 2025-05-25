<?php
header('Content-Type: application/json');

$allowedExtensions = ['mp4', 'webm', 'ogg'];

if (!isset($_GET['folder'])) {
    echo json_encode([]);
    exit;
}

$folder = $_GET['folder'];
$baseDir = __DIR__;

// Prevent directory traversal
$sanitizedFolder = str_replace(['..', './', '\\'], '', $folder);
$fullPath = realpath($baseDir . '/' . $sanitizedFolder);

// Ensure the requested folder is inside your site directory
if (!$fullPath || strpos($fullPath, realpath($baseDir)) !== 0) {
    echo json_encode([]);
    exit;
}

$videos = [];

foreach (glob($fullPath . "/*") as $file) {
    if (is_file($file)) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExtensions)) {
            $videos[] = basename($file);
        }
    }
}

echo json_encode($videos);
