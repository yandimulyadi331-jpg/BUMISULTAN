<?php
/**
 * File delivery from storage
 * URL: /file.php?path=yayasan_masar/251206001.jpg
 */

$path = isset($_GET['path']) ? $_GET['path'] : '';

if (empty($path)) {
    http_response_code(400);
    die('No path specified');
}

// Security: prevent directory traversal
$path = str_replace(['../', '..\\'], '', $path);

$file = __DIR__ . '/../storage/app/public/' . $path;

// Check if file exists
if (!file_exists($file) || !is_file($file)) {
    http_response_code(404);
    // Try to serve placeholder
    $placeholder = __DIR__ . '/assets/img/avatars/No_Image_Available.jpg';
    if (file_exists($placeholder)) {
        header('Content-Type: image/jpeg');
        readfile($placeholder);
    }
    die('File not found');
}

// Determine MIME type
$mime_types = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'pdf'  => 'application/pdf',
    'doc'  => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xls'  => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
];

$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$mime = $mime_types[$ext] ?? 'application/octet-stream';

// Set headers
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($file));
header('Cache-Control: public, max-age=86400');
header('Pragma: public');

// Send file
readfile($file);
exit;
