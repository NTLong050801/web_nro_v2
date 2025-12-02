<?php
require_once '../../Controllers/Configs.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'addVnd':
            $username = $_POST['username'] ?? '';
            $vndAmount = $_POST['coinAmount'] ?? 0;

            if ($username && is_numeric($vndAmount)) {
                try {
                    $stmt = $Connect->prepare("UPDATE `{$ImSGame}`.users SET `{$ImSGame}`.users.money = `{$ImSGame}`.users.money + :cash WHERE `{$ImSGame}`.users.username = :username");
                    $stmt->execute([':cash' => $vndAmount, ':username' => $username]);
                    sendResponse('success', 'Đã cộng tiền thành công cho người chơi!');
                } catch (PDOException $e) {
                    echo 'Lỗi SQL: ' . $e->getMessage();
                    sendResponse('error', 'Lỗi SQL: ' . $e->getMessage());
                }
            } else {
                sendResponse('error', 'Dữ liệu nhập không hợp lệ');
            }
            break;
        case 'activeAccount':
            $username = $_POST['username'] ?? '';
            if ($username) {
                try {
                    $stmt = $Connect->prepare("UPDATE `{$ImSGame}`.users SET `{$ImSOption}`.options.isCan = 1, `{$ImSGame}`.users.isLock = 0 WHERE `{$ImSGame}`.users.username = :username");
                    $stmt->execute([':username' => $username]);
                    sendResponse('success', 'Tài khoản đã được kích hoạt thành công!');
                } catch (PDOException $e) {
                    echo 'Lỗi SQL: ' . $e->getMessage();
                    sendResponse('error', 'Lỗi SQL: ' . $e->getMessage());
                }
            } else {
                sendResponse('error', 'Tên người dùng không hợp lệ');
            }
            break;

        case 'ban':
            $username = $_POST['username'] ?? '';
            $banAction = $_POST['action'] ?? '';

            if ($username && in_array($banAction, ['ban', 'unban'])) {
                try {
                    $banStatus = $banAction === 'ban' ? 1 : 0;
                    $stmt = $Connect->prepare("UPDATE `{$ImSGame}`.users SET `{$ImSGame}`.users.isLock = :ban WHERE `{$ImSGame}`.users.username = :username");
                    $stmt->execute([':ban' => $banStatus, ':username' => $username]);

                    $message = $banAction === 'ban' ? 'Khoá tài khoản thành công!' : 'Mở khoá tài khoản thành công!';
                    sendResponse('success', $message);
                } catch (PDOException $e) {
                    echo 'Lỗi SQL: ' . $e->getMessage();
                    sendResponse('error', 'Lỗi SQL: ' . $e->getMessage());
                }
            } else {
                sendResponse('error', 'Dữ liệu nhập không hợp lệ');
            }
            break;

        default:
            sendResponse('error', 'Hành động không hợp lệ');
    }
} else {
    sendResponse('error', 'Yêu cầu không hợp lệ');
}
