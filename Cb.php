<?php
function writeToLog($message)
{
    $logMessage = $message . " | " . date("Y-m-d H:i:s") . "\n";
    file_put_contents("nduckien.log", $logMessage, FILE_APPEND);
}

try {
    $Connect = new PDO("mysql:host=localhost;dbname=ngocrong_game;charset=utf8", "root", "");
    $Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $jsonBody = json_decode(file_get_contents('php://input'));
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON received');
    }
    
    writeToLog("Nhận dữ liệu: " . json_encode($jsonBody));

    if (!isset($jsonBody->callback_sign, $jsonBody->code, $jsonBody->serial, $jsonBody->status)) {
        throw new Exception("Dữ liệu không đầy đủ");
    }
    
    if ($jsonBody->callback_sign === md5(PARTNER_KEY . $jsonBody->code . $jsonBody->serial)) {
        $code = $jsonBody->code;
        $serial = $jsonBody->serial;
        
        $Connect->beginTransaction();
        
        $stmt = $Connect->prepare("SELECT * FROM napthe WHERE code = :code AND serial = :serial");
        $stmt->execute([':code' => $code, ':serial' => $serial]);
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_nap = $row['user_nap'];
            $amount = $row['amount'];
            $price = $amount;
            
            writeToLog("Tìm thấy thẻ: Code: $code, Serial: $serial, User: $user_nap, Amount: $amount");
            
            $Status_Update = $Connect->prepare("UPDATE napthe SET status = :status WHERE code = :code AND serial = :serial");
            if ($Status_Update->execute([':status' => $jsonBody->status, ':code' => $code, ':serial' => $serial])) {
                writeToLog("Cập nhật trạng thái thẻ thành công: Code: $code, Serial: $serial, Status: {$jsonBody->status}");
                
                if ($jsonBody->status == 1) {
                    $Account_Update = $Connect->prepare("UPDATE users SET money = money + :price, danap = danap + :price WHERE username = :user_nap");
                    if ($Account_Update->execute([':price' => $price, ':user_nap' => $user_nap])) {
                        writeToLog("Cập nhật số dư thành công cho user: $user_nap, Số tiền: $price");
                    } else {
                        throw new Exception("Lỗi cập nhật account: " . implode(" ", $Account_Update->errorInfo()));
                    }
                } else {
                    writeToLog("Thẻ có trạng thái khác 1, không cập nhật số dư: Status: {$jsonBody->status}");
                }
            } else {
                throw new Exception("Không thể cập nhật trạng thái cho code: $code, serial: $serial");
            }
            
            $Connect->commit();
            $message = "Thành Công (User: $user_nap | vnd: " . number_format($price) . ", tongnap: " . number_format($price) . ")";
        } else {
            throw new Exception("Không tìm thấy thẻ code: $code, serial: $serial");
        }
    } else {
        throw new Exception("Key của bạn không hợp lệ hoặc bị khoá");
    }
} catch (Exception $e) {
    if ($Connect->inTransaction()) {
        $Connect->rollBack();
    }
    $message = $e->getMessage();
    writeToLog("Lỗi: " . $message);
}

writeToLog($message);
?>
