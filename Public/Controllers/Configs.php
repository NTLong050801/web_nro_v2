<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

try {
    $Connect = new PDO('mysql:host=157.10.45.89', 'dev', '123456');
    $Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Kết nối thất bại: ' . $e->getMessage());
}

$ImSPlayer = 'ngocrong_player';
$ImSGame = 'ngocrong_game';
$ImSOption = 'ngocrong_option';
$ImSData = 'ngocrong_data';

// Cấu hình Website
define('LOGO', 'Logo.gif');
define('SERVER_NAME', 'Vũ Trụ Ngọc Rồng');
define('DOMAIN', 'vutrungocrong.com');
define("NGame", "Vũ Trụ Ngọc Rồng");
define('TITLE', 'Vũ Trụ Ngọc Rồng - Trang Chủ');
define('DESCRIPTION', 'Website chính thức của Vũ Trụ Ngọc Rồng – Game Dragon Ball Mobile nhập vai trực tuyến trên máy tính và điện thoại về Dragon ball phiêu lưu hấp dẫn nhất hiện nay!');
define('KEYWORD', 'Nro, Nro Lậu, Ngọc Rồng, Vũ Trụ Ngọc Rồng, Chú Bé Rồng');

// Cấu hình Trang
define('RECAPTCHA_SECRET_KEY', '6Le1Yd0qAAAAAIZr-NrLLgHoQaBW76K7z997ZfHZ');
define('RECAPTCHA_SITE_KEY', '6Le1Yd0qAAAAAMDdcYPYYBT3yzfvukTPK3mzocV_');

// Cấu hình API
define('API_CARD_URL', 'https://doithe1s.vn/');
define('PARTNER_KEY', '666012cc90d22afcc2293b02baab068d');
define('PARTNER_ID', '55877943155');

// Cấu hình Mạng Xã Hội
define('FANPAGE_URL', 'https://www.facebook.com/vutrungocrong.official');
define('GROUP_URL', 'https://www.facebook.com/vutrungocrong.official');
define('ZALO_URL', 'https://zalo.me/g/npwtwc000');

// Cấu hình Downloads
$Downloads = [
    'Android' => ['/Public/download.php?file=android'],
    'Iphone'  => ['/Public/download.php?file=iphone'],
    'Windows' => ['/Public/download.php?file=windows'],
    'Java'    => ['/Public/download.php?file=java']
];

// Lấy giftcode từ CSDL 9nro_player (dùng tên đầy đủ: database.table)
$Giftcode = $Connect->query("SELECT * FROM `{$ImSPlayer}`.giftcode")->fetchAll(PDO::FETCH_ASSOC);

// ATM - Vcb
$AChauBank = [
    'Username' => '0372665345',
    'Password' => 'Mtdview@1701',
    'Account'  => '9372665345',
    'Name'     => 'MAI TIEN DUNG',
];

// Session Login
$_Login = null;
$_Users = $_SESSION['ImSynZx_Login'] ?? null;
$_Ip = $_SERVER['REMOTE_ADDR'];

// Cấu hình Nạp Thẻ và các dịch vụ khác
$_TrangThai       = 0;
$_StatusAtm       = 0;
$_StatusExchange  = 1;
$_StatusShop      = 1;
$_ActiveTV        = 10000;
$rechargeOptions  = [
    ['amount' => 10000,   'bonus' => 0],
    ['amount' => 20000,   'bonus' => 0],
    ['amount' => 50000,   'bonus' => 0],
    ['amount' => 100000,  'bonus' => 0],
    ['amount' => 200000,  'bonus' => 0],
    ['amount' => 500000,  'bonus' => 0],
    ['amount' => 1000000, 'bonus' => 0],
    ['amount' => 2000000, 'bonus' => 0],
    ['amount' => 5000000, 'bonus' => 0],
    ['amount' => 10000000, 'bonus' => 0],
];

foreach ($rechargeOptions as &$option) {
    $coins = $option['amount'];
    $bonus = $option['bonus'];
    $totalCoins = $coins + ($coins * $bonus / 100);
    $option['displayCoins']  = number_format($totalCoins, 0, ',', '.') . ' Coin';
    $option['displayAmount'] = number_format($option['amount'], 0, ',', '.') . ' đ';
    $option['displayBonus']  = $bonus > 0 ? '+' . $bonus . '%' : '0%';
}
unset($option);

function sendResponse($status, $message)
{
    header('Content-Type: application/json');
    echo json_encode([
        'status'  => $status,
        'message' => $message
    ]);
    exit();
}

function getTelcoImage($telco)
{
    switch (strtoupper($telco)) {
        case 'VIETTEL':
            return 'Viettel.png';
        case 'MOBIFONE':
            return 'Mobifone.png';
        case 'VINAPHONE':
            return 'Vinaphone.png';
        case 'ZING':
            return 'Zing.png';
        case 'GARENA':
            return 'Garena.png';
        default:
            return 'Logo.gif';
    }
}

function getStatusLabel($status)
{
    $status = (int)$status;
    switch ($status) {
        case 1:
            return '<span class="status active">Thẻ đúng</span>';
        case 2:
            return '<span class="status error">Thẻ sai</span>';
        case 3:
            return '<span class="status error">Thẻ lỗi</span>';
        default:
            return '<span class="status disabled">Chờ Duyệt</span>';
    }
}

function fetchUserData($Connect, $username, $ImSGame)
{
    return fetchData($Connect, "SELECT * FROM `{$ImSGame}`.users WHERE `{$ImSGame}`.users.username = :username", [':username' => $username]);
}

function fetchPlayersData($Connect, $_PlayerId, $ImSOption)
{
    return fetchData($Connect, "SELECT * FROM `{$ImSOption}`.options WHERE `{$ImSOption}`.options.playerId = :id", [':id' => $_PlayerId]);
}

function fetchData($Connect, $query, $params)
{
    $stmt = $Connect->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function formatMoney($number)
{
    if (!is_numeric($number) || $number === null) {
        return '0';
    }
    if ($number >= 1e9) {
        return number_format($number / 1e9, 2, '.', ',') . ' Tỷ';
    } elseif ($number >= 1e6) {
        return number_format($number / 1e6, 2, '.', ',') . ' Triệu';
    } elseif ($number >= 1e3) {
        return number_format($number / 1e3, 2, '.', ',') . ' Nghìn';
    }
    return number_format($number, 0, '.', ',') . ' VNĐ';
}

function getTimeAgo($datetime)
{
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return 'Vừa xong';
    if ($diff < 3600) return floor($diff / 60) . ' phút trước';
    if ($diff < 86400) return floor($diff / 3600) . ' giờ trước';
    if ($diff < 604800) return floor($diff / 86400) . ' ngày trước';
    if ($diff < 2592000) return floor($diff / 604800) . ' tuần trước';
    if ($diff < 31536000) return floor($diff / 2592000) . ' tháng trước';
    return floor($diff / 31536000) . ' năm trước';
}

if ($_Users !== null) {
    $_Login = "on";
    $user_data = fetchUserData($Connect, $_Users, $ImSGame);
    if ($user_data) {
        $_Id         = htmlspecialchars($user_data['id'] ?? '');
        $_Username   = htmlspecialchars($user_data['username'] ?? '');
        $_Password   = htmlspecialchars($user_data['password'] ?? '');
        $_Admin      = htmlspecialchars($user_data['isAdmin'] ?? '');
        $_Coins      = htmlspecialchars($user_data['money'] ?? '');
        $_TCoins     = htmlspecialchars($user_data['danap'] ?? '');
        $_Email      = htmlspecialchars($user_data['lastIP'] ?? '');
        $_CreateTime = htmlspecialchars($user_data['time'] ?? '');
        $_PlayerId   = htmlspecialchars($user_data['playerId'] ?? '');
    } else {
        $_Id = $_Username = $_Password = $_Admin = $_Coins = $_TCoins = $_Email = $_CreateTime = $_PlayerId = '';
    }

    $player_data = ($_PlayerId !== 'Null') ? fetchPlayersData($Connect, $_PlayerId, $ImSOption) : null;
    if ($player_data) {
        $_Char        = htmlspecialchars($player_data['cName'] ?? '');
        $_CPower      = htmlspecialchars($player_data['cPower'] ?? '');
        $_CTotalGold  = htmlspecialchars($player_data['totalGold'] ?? '');
        $_Status      = htmlspecialchars($player_data['isCan'] ?? '');
    } else {
        $_Char = $_CPower = $_CTotalGold = $_Status = '';
    }
}


function HideString($string, $type = 'email')
{
    switch ($type) {
        case 'Email':
            $atPos = strpos($string, '@');
            return $atPos === false
                ? $string
                : substr($string, 0, 1) . str_repeat('*', $atPos - 1) . substr($string, $atPos);
        case 'Password':
            return strlen($string) > 1
                ? substr($string, 0, 1) . str_repeat('*', strlen($string) - 1)
                : $string;
        default:
            return $string;
    }
}
