<?php
include '../../Controllers/Configs.php';
// Kiểm tra yêu cầu là POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        sendResponse('error', 'Vui lòng điền đầy đủ thông tin.');
    }

    $username = $_SESSION['ImSynZx_Login'];
    $stmt = $Connect->prepare("SELECT `{$ImSGame}`.users.password FROM `{$ImSGame}`.users WHERE `{$ImSGame}`.users.username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        sendResponse('error', 'Người dùng không tồn tại.');
    }

    if ($currentPassword === $user['password']) {
        if (strlen($newPassword) < 4) {
            sendResponse('error', 'Mật khẩu mới phải có ít nhất 4 ký tự.');
        } elseif ($newPassword !== $confirmPassword) {
            sendResponse('error', 'Mật khẩu mới và mật khẩu xác nhận không khớp.');
        } else {
            $updateStmt = $Connect->prepare("UPDATE `{$ImSGame}`.users SET `{$ImSGame}`.users.password = ? WHERE `{$ImSGame}`.users.username = ?");
            if ($updateStmt->execute([$newPassword, $username])) {
                sendResponse('success', 'Mật khẩu đã được đổi thành công!');
            } else {
                sendResponse('error', 'Đã xảy ra lỗi khi đổi mật khẩu.');
            }
        }
    } else {
        sendResponse('error', 'Mật khẩu hiện tại không chính xác.');
    }
} else {
    http_response_code(405);
    sendResponse('error', 'Phương thức yêu cầu không hợp lệ.');
}