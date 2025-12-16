<?php
// File xử lý download đơn giản
$files = [
    'android' => 'Assets/Download/NroMPhu_1.apk',
    'iphone'  => 'Assets/Download/NROMPHU (2).ipa',
    'windows' => 'Assets/Download/NroMPhu_V2 (1).rar',
    'java'    => 'Assets/Download/Nro (1).jar'
];

$type = isset($_GET['file']) ? $_GET['file'] : '';

if (isset($files[$type])) {
    $file = __DIR__ . '/' . $files[$type];

    if (file_exists($file)) {
        // Lấy tên file gốc
        $filename = basename($file);

        // Xóa mọi output trước đó
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: public');

        // Đọc và xuất file
        readfile($file);
        exit;
    }
}

// Nếu không tìm thấy file
header('HTTP/1.0 404 Not Found');
echo 'File not found';
exit;

