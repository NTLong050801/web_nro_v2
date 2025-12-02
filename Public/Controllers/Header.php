<?php
require_once 'Configs.php';
if (($_Login === null && strpos($_SERVER['REQUEST_URI'], '/Users/') !== false) || ($_Login == true && strpos($_SERVER['REQUEST_URI'], '/Auth/') !== false)) {
    die('<script>window.location.href = "/";</script>');
}

// if (!isset($_SERVER['HTTP_HOST']) || $_SERVER['HTTP_HOST'] !== 'apt-killdeer-plainly.ngrok-free.app') {
// header('HTTP/1.0 403 Forbidden');
// exit('NGUYEN DUC KIEN DEP TRAI VCL | Github: @ImSynZx <3');
// }
?>

<head>
    <meta charset="utf-8">
    <link rel="icon" href="/Public/Assets/Images/<?= LOGO ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="theme-color" content="#000000">
    <meta name="title" content="<?= TITLE ?>">
    <meta name="description" content="<?= DESCRIPTION ?>">
    <meta name="keywords" content="<?= KEYWORD ?>">
    <meta name="author" content="haitacplus">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://<?= SERVER_NAME ?>/">
    <meta property="og:title" content="<?= DESCRIPTION ?>">
    <meta property="og:description" content="<?= DESCRIPTION ?>">
    <meta property="og:image" content="/Public/Assets/Images/<?= LOGO ?>">
    <meta property="og:image:alt" content="<?= SERVER_NAME ?>">
    <link rel="apple-touch-icon" href="/Public/Assets/Images/<?= LOGO ?>">
    <title><?= TITLE ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <link href="/Public/Assets/Css/Main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <script src="/Public/Assets/Js/Main.js?v=1.1"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= RECAPTCHA_SITE_KEY ?>"></script>
    <style>
        .logo {
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<div class="modal fade" id="serverModal" tabindex="-1" aria-labelledby="serverModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="my-2">
                    <div class="text-center">
                        <img alt="Logo" src="/Public/Assets/Images/<?= LOGO ?>" class="logo">
                    </div>
                </div>
                <div class="text-center fw-semibold">
                    <div class="mb-2" style="font-size: 14px;">THÔNG BÁO</div>
                    <div class="mb-2" style="font-size: 11px;">Chào mừng đến với máy chủ <strong>Vũ Trụ Ngọc Rồng</strong>
                    </div>
                    <div class="mt-2">
                        <a href="<?= ZALO_URL ?>" id="serverTeamButton" target="_blank" class="btn-rounded btn btn-primary btn-sm" style="padding: 10px;">Nhóm Chat Zalo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="root">
    <div class="container">
        <div class="main">
            <div class="text-center card">
                <div class="card-body">
                    <div class="">
                        <a href="/">
                            <img alt="Logo" src="/Public/Assets/Images/<?= LOGO ?>" class="logo">
                        </a>
                    </div>
                    <div class="mt-3">
                        <?php if ($_Login == null) { ?>
                            <div class="mt-3">
                                <span class="mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer" id="openModalButton">Đăng nhập ngay?</span>
                            </div>
                            <div class="modal fade" id="unifiedModal" tabindex="-1" aria-labelledby="unifiedModalLabel" aria-hidden="true" data-bs-backdrop="static">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <ul class="nav nav-tabs" id="modalTabs" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="fw-semibold text-secondary rounded-2 link-success cursor-pointer nav-link active" id="login-tab" data-bs-toggle="tab" href="#login" role="tab">Đăng nhập</a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="fw-semibold text-secondary rounded-2 link-success cursor-pointer nav-link" id="register-tab" data-bs-toggle="tab" href="#register" role="tab">Đăng ký</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content mt-3">
                                                <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                                                    <form class="py-3" method="post" id="login-form">
                                                        <input type="hidden" name="action" value="login">
                                                        <div class="mb-2">
                                                            <input type="text" placeholder="Tên đăng nhập" name="username" class="form-control" required>
                                                        </div>
                                                        <div class="mb-2">
                                                            <input type="password" placeholder="Mật khẩu" name="password" class="form-control" required>
                                                        </div>
                                                        <div class="text-center mt-3">
                                                            <button type="submit" class="btn btn-primary">Đăng nhập</button>
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Hủy bỏ</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                                                    <form class="py-3" method="post" id="register-form">
                                                        <input type="hidden" name="action" value="register">
                                                        <div class="mb-2">
                                                            <input type="text" placeholder="Tên đăng nhập" name="username" class="form-control" required>
                                                        </div>
                                                        <!-- <div class="mb-2">
                                                            <input type="gmail" placeholder="gmail" name="gmail" class="form-control" required>
                                                        </div> -->
                                                        <div class="mb-2">
                                                            <input type="password" placeholder="Mật khẩu" name="password" class="form-control" required>
                                                        </div>
                                                        <div class="mb-2">
                                                            <input type="password" placeholder="Xác nhận Mật khẩu" name="repassword" class="form-control" required>
                                                        </div>
                                                        <div class="text-center mt-3">
                                                            <button type="submit" class="btn btn-primary">Đăng ký</button>
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Hủy bỏ</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div>
                                <a class="mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer" href="/Users/Profile">
                                    <?= $_Username . ' - ' . formatMoney($_Coins) ?>
                                </a>
                                <a href="/Api/Logout" class="ms-1 mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer">Đăng xuất</a>
                            </div>
                            <?php if ($_Admin == 1): ?>
                                <br>
                                <a class="ms-1 mb-3 px-2 py-1 fw-semibold text-secondary bg-warning bg-opacity-25 border border-warning border-opacity-75 rounded-2 link-success cursor-pointer" href="/Users/Admin">
                                    Quản trị viên
                                </a>
                            <?php endif; ?>
                        <?php } ?>
                        <div class="mt-3">
                            <a class="mb-3 px-2 py-1 fw-semibold text-danger bg-danger bg-opacity-25 border border-danger border-opacity-50 rounded-2 cursor-pointer" id="DownloadButton">TẢI GAME <svg fill="#000000" height="800px" width="800px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 29.978 29.978" xml:space="preserve" class="download-icon">
                                    <g>
                                        <path d="M25.462,19.105v6.848H4.515v-6.848H0.489v8.861c0,1.111,0.9,2.012,2.016,2.012h24.967c1.115,0,2.016-0.9,2.016-2.012
            v-8.861H25.462z"></path>
                                        <path d="M14.62,18.426l-5.764-6.965c0,0-0.877-0.828,0.074-0.828s3.248,0,3.248,0s0-0.557,0-1.416c0-2.449,0-6.906,0-8.723
            c0,0-0.129-0.494,0.615-0.494c0.75,0,4.035,0,4.572,0c0.536,0,0.524,0.416,0.524,0.416c0,1.762,0,6.373,0,8.742
            c0,0.768,0,1.266,0,1.266s1.842,0,2.998,0c1.154,0,0.285,0.867,0.285,0.867s-4.904,6.51-5.588,7.193
            C15.092,18.979,14.62,18.426,14.62,18.426z"></path>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                        <g></g>
                                    </g>
                                </svg>
                            </a>
                        </div>
                       <div class="modal fade" id="DownloadModal" tabindex="-1" aria-labelledby="DownloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <ul class="nav nav-tabs justify-content-center" id="modalTabs" role="tablist">
                    <?php foreach (array_keys($Downloads) as $index => $platform): ?>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link<?php if ($index === 0) echo ' active'; ?>" id="<?php echo strtolower($platform); ?>-tab" data-bs-toggle="tab" href="#<?php echo strtolower($platform); ?>" role="tab">
                                <i class="fab fa-<?php echo strtolower($platform); ?> me-2"></i> <?php echo strtoupper($platform); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="tab-content mt-3">
                    <?php foreach ($Downloads as $platform => $files): ?>
                        <div class="tab-pane fade<?php if ($platform === array_key_first($Downloads)) echo ' show active'; ?>" id="<?php echo strtolower($platform); ?>" role="tabpanel" aria-labelledby="<?php echo strtolower($platform); ?>-tab">
                            <h5 class="text-center mb-4 text-primary"><?php echo strtoupper($platform); ?></h5>
                            <div class="version-container">
                                <?php foreach ($files as $index => $url): ?>
                                    <div class="version mb-3 p-3 border rounded shadow-sm">
                                        <p class="font-weight-bold">VUTRUNGOCRONG - Phiên bản <?php echo $index + 1; ?></p>
                                        <a class="btn btn-success w-100 mb-2" href="<?php echo $url; ?>" download>Tải về</a>
                                        <button class="btn btn-outline-info w-100" data-version="<?php echo $index + 1; ?>" data-platform="<?php echo $platform; ?>">Giới thiệu</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>


            <div class="mb-2">
                <div class="row text-center justify-content-center row-cols-2 row-cols-lg-6 g-2 g-lg-2 mt-1">
                    <div class="col">
                        <div class="px-2">
                            <a class="btn btn-menu btn-danger w-100 fw-semibold active" href="/">Trang chủ</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="px-2">
                            <a class="btn btn-menu btn-danger w-100 fw-semibold false" href="/Users/Payments">Nạp tiền</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="px-2">
                            <a class="btn btn-menu btn-danger w-100 fw-semibold false" id="GiftcodeButton">Xem Giftcode</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="GiftcodeModal" tabindex="-1" aria-labelledby="GiftcodeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <ul class="nav nav-tabs" id="modalTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="giftcode-tab" data-bs-toggle="tab" href="#giftcode" role="tab">Giftcodes</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3">
                                <div class="tab-pane fade show active" id="giftcode" role="tabpanel" aria-labelledby="giftcode-tab">
                                    <!-- Giftcode Grid -->
                                    <div class="giftcode-grid mt-4">
                                        <div class="row">
                                            <?php foreach ($Giftcode as $index => $code): ?>
                                                <div class="col-md-4 col-6 mb-3">
                                                    <div class="card shadow-sm border-light">
                                                        <div class="card-body text-center">
                                                            <p class="card-text mb-0"><strong>GiftCode <?php echo $index + 1; ?>:</strong> <?php echo $code['code']; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>