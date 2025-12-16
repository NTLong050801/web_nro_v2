<?php
// File xử lý download
$allowedFiles = [
    'android' => 'NroMPhu_1.apk',
    'iphone' => 'NROMPHU (2).ipa',
    'windows' => 'NroMPhu_V2 (1).rar',
    'java' => 'Nro (1).jar'
];

if (isset($_GET['type']) && array_key_exists($_GET['type'], $allowedFiles)) {
    $fileType = $_GET['type'];
    $fileName = $allowedFiles[$fileType];
    $filePath = __DIR__ . '/../Assets/Download/' . $fileName;

    if (file_exists($filePath)) {
        // Clear any previous output
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers để download file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // Đọc file và gửi về browser
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        die('File không tồn tại: ' . htmlspecialchars($fileName));
    }
} else {
    http_response_code(400);
    die('Yêu cầu không hợp lệ');
}

