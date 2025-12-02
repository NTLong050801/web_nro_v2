<?php
require '../Controllers/Configs.php';

function verifyRecaptcha($token, $secret)
{
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$token");
    $result = json_decode($response, true);
    return isset($result['success']) && $result['success'];
}

function getUserIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags($input));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $recaptchaToken = $_POST['recaptcha_token'] ?? '';
    $userIP = getUserIP();

    if (!verifyRecaptcha($recaptchaToken, RECAPTCHA_SECRET_KEY)) {
         sendResponse('error', 'Xác thực reCAPTCHA không thành công.');
     }

    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? ''; // Không dùng sanitizeInput để tránh mất ký tự đặc biệt của hash
    $repassword = $_POST['repassword'] ?? '';

    if (empty($username) || empty($password)) {
        sendResponse('error', 'Vui lòng nhập đầy đủ thông tin!');
    }

    if ($action === 'login') {
        $stmt = $Connect->prepare("SELECT username, password, isLock FROM `{$ImSGame}`.users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['isLock'] == 1) {
                sendResponse('error', 'Tài khoản của bạn bị khóa.');
            }

            if ($password === $user['password']) {
                $_SESSION['ImSynZx_Login'] = $user['username'];
                sendResponse('Success', 'Đăng nhập thành công.');
            } else {
                sendResponse('error', 'Tên người dùng hoặc mật khẩu không đúng.');
            }
        } else {
            sendResponse('error', 'Người dùng không tồn tại.');
        }
    } elseif ($action === 'register') {
        if ($password !== $repassword) {
            sendResponse('error', 'Mật khẩu xác nhận không khớp.');
        }

        $stmt = $Connect->prepare("SELECT COUNT(*) FROM `{$ImSGame}`.users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            sendResponse('error', 'Tên người dùng đã tồn tại.');
        }

        $stmt = $Connect->prepare("INSERT INTO `{$ImSGame}`.users (username, password) VALUES (:username, :password)");
        $stmt->execute(['username' => $username, 'password' => $password]);
        $userId = $Connect->lastInsertId();
        sendResponse('Success', 'Đăng ký thành công.', $userId);
    } else {
        sendResponse('error', 'Yêu cầu không hợp lệ.');
    }
} else {
    sendResponse('error', 'Phương thức yêu cầu không hợp lệ.');
}