<?php
require_once '../../Controllers/Configs.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postData = json_decode(file_get_contents('php://input'), true);
    if (isset($postData["vnd_amount"]) && isset($postData['gold_amount']) && isset($_SESSION['ImSynZx_Login'])) {
        $vnd_amount = $postData["vnd_amount"];
        $gold_amount = $postData["gold_amount"];
        $username = $_SESSION['ImSynZx_Login'];

        try {
            $Vnd_New = $Connect->prepare("SELECT `{$ImSGame}`.users.money FROM `{$ImSGame}`.users WHERE `{$ImSGame}`.users.username = :username");
            $Vnd_New->execute(['username' => $username]);
            $Vnd_Old = $Vnd_New->fetchColumn();
            if ($Vnd_Old >= $vnd_amount) {
                $Update_Gold = $Connect->prepare("UPDATE `{$ImSGame}`.users SET `{$ImSGame}`.users.totalGold = `{$ImSGame}`.users.totalGold + :thoi_vang WHERE `{$ImSGame}`.users.username = :username");
                $Update_Gold->execute(['thoi_vang' => $gold_amount, 'username' => $username]);

                $Update_Vnd = $Connect->prepare("UPDATE `{$ImSGame}`.users SET `{$ImSGame}`.users.money = `{$ImSGame}`.users.money - :vnd_amount WHERE `{$ImSGame}`.users.username = :username");
                $Update_Vnd->execute(['vnd_amount' => $vnd_amount, 'username' => $username]);
                sendResponse('success', 'Đổi thành công ' . $gold_amount . ' Thỏi.');
            } else {
                sendResponse('success', 'Số dư không đủ để thực hiện giao dịch.');
            }
        } catch (PDOException $e) {
            error_log("PDOException: " . $e->getMessage(), 0);
            sendResponse('success', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
        }
    } else {
        sendResponse('success', 'Dữ liệu không đủ hoặc không hợp lệ.');
    }
} else {
    sendResponse('success', 'Yêu cầu không hợp lệ.');
}
