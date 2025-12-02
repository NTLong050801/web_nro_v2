<?php
require_once '../../Controllers/Header.php';
if ($_Login === null || $_Admin == 0) {
    die('<script>window.location.href = "/Users/Profile";</script>');
}

$TAccounts = "SELECT COUNT(*) AS taccounts FROM `{$ImSGame}`.users";
$RAccounts = $Connect->query($TAccounts)->fetch(PDO::FETCH_ASSOC);
$Id = $RAccounts['taccounts'];
$TChar = "SELECT COUNT(*) AS `Char` FROM `{$ImSOption}`.options WHERE cName > 0";
$RChar = $Connect->query($TChar)->fetch(PDO::FETCH_ASSOC);
$TNinja = $RChar['Char'];
$TBans = "SELECT COUNT(*) AS banned FROM `{$ImSGame}`.users WHERE isLock = 1";
$RBans = $Connect->query($TBans)->fetch(PDO::FETCH_ASSOC);
$TBan = $RBans['banned'];
$currentMonth = date('m');
$query = "SELECT user_nap, SUM(amount) AS tongnap FROM (
            SELECT `{$ImSGame}`.napthe.user_nap, `{$ImSGame}`.napthe.amount, `{$ImSGame}`.napthe.created_at FROM `{$ImSGame}`.napthe WHERE `{$ImSGame}`.napthe.status = 1
            UNION ALL
            SELECT `{$ImSGame}`.payments.name, `{$ImSGame}`.payments.amount, STR_TO_DATE(date, '%d/%m/%Y %H:%i:%s') AS created_at FROM `{$ImSGame}`.payments WHERE `{$ImSGame}`.payments.status = 'Thành Công'
          ) AS transactions WHERE MONTH(created_at) = $currentMonth GROUP BY user_nap ORDER BY tongnap DESC";
$results = $Connect->query($query)->fetchAll();
$tongtienthangnay = array_sum(array_column($results, 'tongnap'));
$tienthangnay = is_numeric($tongtienthangnay) ? number_format($tongtienthangnay) : 0;
?>

<div class="card">
    <div class="card-body d-flex align-items-start">
        <div class="profile-section me-4" style="flex: 1;">
            <hr>
            <h4>THÔNG TIN MÁY CHỦ</h4>
            <div class="flex-container mb-3">
                <div class="flex-item" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="info-item">
                        <strong>Tổng Tài Khoản:</strong> <?= $Id; ?>
                    </div>
                    <div class="info-item">
                        <strong>Tổng Nhân Vật:</strong> <?= $TNinja; ?>
                    </div>
                    <div class="info-item">
                        <strong>Tài Khoản Vi Phạm:</strong> <?= $TBan; ?>
                    </div>
                </div>
            </div>
            <br>
            <div class="flex-container mb-3">
                <div class="flex-item" style="display: flex; flex-direction: column; justify-content: center;">
                    <?php if (!empty($results)): ?>
                        <ol class='list-unstyled'>
                            <?php foreach (array_slice($results, 0, 3) as $index => $row): ?>
                                <strong>
                                    <li class='mb-1'>TOP <?= $index + 1 ?>
                                </strong>: <?= $row['user_nap'] ?> - <strong>Tổng nạp</strong>: <span class='amount'><?= formatMoney($row['tongnap']) ?></span></li>
                            <?php endforeach; ?>
                        </ol>
                    <?php else: ?>
                        <p>Không có dữ liệu nạp tháng này.</p>
                    <?php endif; ?>
                    <span class='mb-3'>-<strong> Tổng doanh thu tháng này</strong>: <?= formatMoney($tienthangnay) ?></span>
                </div>
            </div>
            <br>
            <hr>
            <table class="table">
                <tbody>
                    <tr>
                        <td>
                            <button class="btn btn-secondary action-btn" data-action="addVnd" data-target="#addVndModal">Cộng Tiền</button>
                            <button class="btn btn-secondary action-btn" data-action="activateMember" data-target="#activeMember">Kích Hoạt Thành Viên</button>
                            <button class="btn btn-secondary action-btn" data-action="banMember" data-target="#banMember">Khoá/Mở Tài Khoản</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="addVndModal" tabindex="-1" aria-labelledby="addVndModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVndModalLabel">Cộng Vnd</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="username">Username:</label>
                <input type="text" id="username" class="form-control">
                <label for="coinAmount" class="mt-2">Số Vnd:</label>
                <input type="number" id="coinAmount" class="form-control" placeholder="Nhập số Vnd">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addVndBtn">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="activeMember" tabindex="-1" aria-labelledby="activeMemberLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activeMemberLabel">Kích hoạt tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="activeForm">
                    <div class="mb-3">
                        <label for="activeUsername" class="form-label">Tên tài khoản</label>
                        <input type="text" class="form-control" id="activeUsername" name="username" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="banMember" tabindex="-1" aria-labelledby="banMemberLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="banMemberLabel">Khoá/Mở Tài Khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="banForm">
                    <div class="mb-3">
                        <label for="banUsername" class="form-label">Tên tài khoản</label>
                        <input type="text" class="form-control" id="banUsername" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="banAction" class="form-label">Hành động</label>
                        <select class="form-select" id="banAction" name="action">
                            <option value="ban">Khoá tài khoản</option>
                            <option value="unban">Mở khoá tài khoản</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hàm xử lý chung
        const handleAction = (action, data) => {
            $.post('/Api/Users/Admin', data, function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            });
        };

        // Sự kiện cho các nút modal
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const modalTarget = btn.dataset.target;
                if (modalTarget) $(modalTarget).modal('show');
            });
        });

        // Xử lý các hành động cụ thể
        document.getElementById('addVndBtn').addEventListener('click', function() {
            const username = document.getElementById('username').value;
            const coinAmount = document.getElementById('coinAmount').value;

            if (username && coinAmount) {
                handleAction('addVnd', {
                    action: 'addVnd',
                    username,
                    coinAmount
                });
            } else {
                toastr.error('Vui lòng nhập đầy đủ thông tin!');
            }
        });

        document.getElementById('exchangeThoiVangBtn').addEventListener('click', function() {
            const ThoiVangAmount = document.getElementById('ThoiVangAmountExchange').value;

            if (ThoiVangAmount >= 1 && ThoiVangAmount <= 1000) {
                handleAction('exchangeThoiVang', {
                    action: 'exchangeThoiVang',
                    ThoiVangAmount
                });
            } else {
                toastr.error('Số lượng phải nằm trong phạm vi từ 1 đến 1000!');
            }
        });

        document.getElementById('banForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const username = document.getElementById('banUsername').value;
            const action = document.getElementById('banAction').value;

            if (username && action) {
                handleAction(action, {
                    action,
                    username
                });
            } else {
                toastr.error('Vui lòng chọn tài khoản và hành động!');
            }
        });
    });
</script>

<?php
require_once '../../Controllers/Footer.php';
?>