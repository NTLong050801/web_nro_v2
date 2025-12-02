<?php
require '../../Controllers/Configs.php';
header('Content-Type: application/json');
if (!isset($_SESSION['ImSynZx_Login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn chưa đăng nhập.']);
    exit;
}

$username = $_SESSION['ImSynZx_Login'];

try {
    $Connect->beginTransaction();

    $stmt = $Connect->prepare("SELECT money, username FROM `{$ImSGame}`.users WHERE {$ImSGame}.users.username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy người dùng.']);
        exit;
    }

    $currentBalance = (int)$user['money'];
    if ($currentBalance < $_ActiveTV) {
        echo json_encode(['status' => 'error', 'message' => 'Số dư không đủ để kích hoạt tài khoản.']);
        exit;
    }

    $stmt1 = $Connect->prepare("UPDATE `{$ImSGame}`.users SET money = money - :cash  WHERE username = :user_id");
    $stmt1->execute([':cash' => $_ActiveTV, ':user_id' => $username ]);

    $stmt2 = $Connect->prepare("UPDATE `{$ImSOption}`.options SET isCan = '1'  WHERE playerId = :user_id");
    $stmt2->execute([ ':user_id' => $_PlayerId ]);

    $Connect->commit();
    echo json_encode(['status' => 'success', 'message' => 'Tài khoản đã được kích hoạt thành công.']);
} catch (PDOException $e) {
    if ($Connect->inTransaction()) {
        $Connect->rollBack();
    }
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()
    ]);
}
