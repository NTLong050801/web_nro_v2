<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once "vendor/autoload.php";
require_once "cors.php";
require_once "VietCombank.php";
require_once '../../Controllers/Configs.php';

$app = new VietCombank($AChauBank['Username'], $AChauBank['Password'], $AChauBank['Account']);
$type = isset($_GET['type']) ? (int) $_GET['type'] : 0;

function saveTransaction($Connect, $ImSGame, $name, $refNo, $date, $amount, $status, $bank)
{
        $stmt_check = $Connect->prepare("SELECT COUNT(*) FROM `{$ImSGame}`.payments WHERE refNo = ?");
        $stmt_check->execute([$refNo]);
        $exists = $stmt_check->fetchColumn();

        if ($exists > 0) {
            return;
        }

        if (!$Connect->inTransaction()) {
            $Connect->beginTransaction();
        }

        $stmt_insert = $Connect->prepare("INSERT INTO `{$ImSGame}`.payments (name, refNo, date, amount, status, bank) VALUES (?, ?, ?, ?, ?, ?)");
        $result = $stmt_insert->execute([$name, $refNo, $date, $amount, $status, $bank]);

        if ($result) {
            $Connect->commit();
            updateAccountBalance($Connect, $ImSGame, $name, $amount);
        } else {
            throw new Exception("Lỗi khi thêm dữ liệu");
        }
}

function updateAccountBalance($Connect, $ImSGame, $name, $amount)
{
  try {
    $stmt = $Connect->prepare("UPDATE `{$ImSGame}`.users SET money = money + ?, danap = danap + ? WHERE id = ?");
    $stmt->execute([$amount, $amount, $name]);
  } catch (PDOException $e) {
    echo "Lỗi khi cập nhật số dư: " . $e->getMessage();
  }
}

if ($type === 1) {
  $result = $app->doLogin($AChauBank['Username'], $AChauBank['Password']);
  if (isset($result['success']) && $result['success']) {
    $username = htmlspecialchars($result['username'] ?? '');
    echo "Đăng nhập thành công. Chào mừng " . $username . "!";
  } else {
    echo "Đăng nhập thất bại: " . htmlspecialchars($result['message'] ?? '');
  }
} elseif ($type === 2) {
  $otp = htmlspecialchars($_GET['otp'] ?? '');
  $result = $app->submitOtpLogin($otp);

  if ($result['success']) {
    echo "OTP xác thực thành công!";
  } else {
    echo "OTP xác thực thất bại: " . htmlspecialchars($result['message'] ?? '');
  }
} elseif ($type === 3) {
  $from = date("d/m/Y");
$yesterday = date("d/m/Y", strtotime("-1 day"));

$retryCount = 0;
$maxRetries = 3;
$loginRequired = false;

do {
    if ($loginRequired) {
        $app->doLogin($AChauBank['Username'], $AChauBank['Password']);
    }
    $result = $app->getHistories($yesterday, $from, $AChauBank['Account'], 0);
   
    $ImSynZx_Json = json_encode($result);

    $loginRequired = strpos($ImSynZx_Json, "đang được truy cập trên thiết bị") !== false ||
      strpos($ImSynZx_Json, "đăng nhập đã hết hiệu lực") !== false;

    $retryCount++;
  } while ($loginRequired && $retryCount < $maxRetries);

  if ($retryCount >= $maxRetries) {
    echo json_encode(['error' => 'Không thể đăng nhập sau nhiều lần thử']);
  } else {
    $ImSynZx_Json = str_replace(["Amount", "Reference", "Description"], ["amount", "transactionID", "description"], $ImSynZx_Json);
    $transactions = json_decode($ImSynZx_Json, true);

   foreach ($transactions['transactions'] as $transaction) {
    if (isset($transaction['description']) && preg_match('/\bNAPTIEN\s*(\d+)/i', $transaction['description'], $matches)) {
        $name = trim($matches[1]);

        $refNo = $transaction['transactionID'];
        $date = $transaction['TransactionDate'];
        $amount = str_replace(',', '', $transaction['amount']);
        $status = "1";
        $bank = "VietcomBank";

        saveTransaction($Connect, $ImSGame, $name, $refNo, $date, $amount, $status, $bank);
    }
}

    echo json_encode($result);
  }
} else {
  echo "Yêu cầu không hợp lệ";
}