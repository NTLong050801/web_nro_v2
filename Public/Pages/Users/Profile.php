<?php
require_once '../../Controllers/Header.php';
?>
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <div class="row text-center justify-content-center row-cols-3 row-cols-lg-6 g-1 g-lg-1">
                <div class="col"><a class="btn btn-sm btn-warning w-100 fw-semibold" href="/Users/Profile" style="background-color: rgb(255, 180, 115);">Tài khoản</a></div>
                <div class="col"><a class="btn btn-sm btn-warning w-100 fw-semibold" href="/Users/History" style="background-color: rgb(255, 180, 115);">Lịch sử GD</a></div>
            </div>
        </div>
        <hr>
        <table class="table">
            <tbody>
                <tr class="fw-semibold">
                    <td>Tài khoản</td>
                    <td><?= $_Username ?></td>
                </tr>
                <tr class="fw-semibold">
                    <td>Nhân vật</td>
                    <td><?= $_Char ?></td>
                </tr>
                <?php if ($_Admin == 1) { ?>
                    <tr class="fw-semibold">
                        <td>Quản Trị Viên</td>
                        <td><a href="/Users/Admin">Truy Cập</a></td>
                    </tr>
                <?php } ?>
                <tr class="fw-semibold">
                    <td>Mật khẩu</td>
                    <td><?= HideString($_Password, 'Password') ?> (<a href="/Users/Password">Đổi mật khẩu</a>)</td>
                </tr>
                <tr class="fw-semibold">
                    <td>Số dư</td>
                    <td><?= formatMoney($_Coins) ?></td>
                </tr>
                <tr class="fw-semibold">
                    <td>Email</td>
                    <td><?= HideString($_Email, 'Email') ?></td>
                </tr>
                <tr class="fw-semibold">
                    <td>Thành Viên</td>
                    <td style="color: rgb(0, 0, 0);"><?= $_Status == 1 ? 'Đã kích hoạt' : 'Chưa Kích Hoạt'; ?></td>
                </tr>
                <tr class="fw-semibold">
                    <td>Trạng thái</td>
                    <td class="text-success fw-bold">Hoạt động</td>
                </tr>
                <tr class="fw-semibold">
                    <td>Ngày tham gia</td>
                    <td><?= $_CreateTime ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
require_once '../../Controllers/Footer.php';
?>