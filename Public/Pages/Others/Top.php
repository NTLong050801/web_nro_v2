<?php
require_once '../../Controllers/Header.php';
?>
<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="post-image d-none d-sm-block"><img src="/Public/Assets/Images/<?= LOGO ?>" alt="Bảng Xếp Hạng Đua Top Máy Chủ">
                <div class="post-author">Admin</div>
            </div>
            <div class="post-detail flex-fill">
                <div class="fw-bold text-primary">Bảng Xếp Hạng Đua Top Máy Chủ</div>
                <div class="post-date"></div>
                <div class="post-content">
                    <div class="post-content">
                        <?php
                        $currentMonth = date('m');
                        $currentYear = date('Y');

                        $vietnameseMonths = array(
                            1 => 'Tháng 1',
                            2 => 'Tháng 2',
                            3 => 'Tháng 3',
                            4 => 'Tháng 4',
                            5 => 'Tháng 5',
                            6 => 'Tháng 6',
                            7 => 'Tháng 7',
                            8 => 'Tháng 8',
                            9 => 'Tháng 9',
                            10 => 'Tháng 10',
                            11 => 'Tháng 11',
                            12 => 'Tháng 12'
                        );

                        $monthName = $vietnameseMonths[intval($currentMonth)];
                        $queryNap = "
    SELECT `{$ImSGame}`.users.username AS name, danap
    FROM `{$ImSGame}`.users
    WHERE `{$ImSGame}`.users.danap 
    ORDER BY `{$ImSGame}`.users.danap DESC
    LIMIT 10";


                        $stmtNap = $Connect->prepare($queryNap);
                        $stmtNap->execute();
                        $querySucManh = "SELECT  `{$ImSOption}`.options.cName AS name,  `{$ImSOption}`.options.cGender AS gender, `{$ImSOption}`.options.cPower AS tongdiem FROM `{$ImSOption}`.options ORDER BY tongdiem DESC LIMIT 20";
                        $dataSucManh = $Connect->query($querySucManh);
                        ?>

                        <div class="card-body py-4">
                            <div class="d-flex justify-content-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-success fw-semibold"
                                        style="border:1px solid #d63384 !important;" onclick="showTable('nap')">Top Nạp</button>
                                    <button type="button" class="btn btn-outline-success fw-semibold"
                                        style="border:1px solid #d63384 !important;" onclick="showTable('sucmanh')">Top Sức
                                        Mạnh</button>
                                </div>
                            </div>
                            <div id="nap-table" class="table-container">
                                <?php if ($stmtNap->rowCount() > 0): ?>
                                    <table class="table table-striped table-hover table-bordered table-responsive mt-3 text-center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nhân vật</th>
                                                <th>Điểm</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php $stt = 1;
                                            while ($row = $stmtNap->fetch(PDO::FETCH_ASSOC)): ?>
                                                <tr>
                                                    <td class="text-<?php echo ($stt <= 3) ? 'danger' : 'success'; ?> fw-bold"><?= $stt ?>
                                                    </td>
                                                    <td class="text-<?php echo ($stt <= 3) ? 'danger' : 'success'; ?> fw-bold">
                                                        <?= htmlspecialchars($row['name']) ?>
                                                    </td>
                                                    <td class="text-<?php echo ($stt <= 3) ? 'danger' : 'success'; ?> fw-bold">
                                                        <?= number_format($row['danap'], 0, ',', '.') ?>đ
                                                    </td>
                                                </tr>
                                                <?php $stt++; ?>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <center>
                                        <h6>Chưa có thống kê bảng xếp hạng top nạp tháng này!</h6>
                                    </center>
                                <?php endif; ?>
                            </div>
                            <div id="sucmanh-table" class="table-container" style="display: none;">
                                <table class="table table-striped table-hover table-bordered table-responsive mt-3 text-center">
                                    <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>Nhân Vật</th>
                                            <th>Sức Mạnh</th>
                                            <th>Hành Tinh</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php if ($dataSucManh->rowCount() > 0):
                                            $countTop = 1;
                                            while ($row = $dataSucManh->fetch(PDO::FETCH_ASSOC)): ?>
                                                <tr>
                                                    <td
                                                        class="text-<?php echo ($countTop == 1) ? 'danger' : (($countTop == 2) ? 'warning' : (($countTop == 3) ? 'info' : 'success')); ?> fw-bold">
                                                        <?= $countTop ?>
                                                    </td>
                                                    <td
                                                        class="text-<?php echo ($countTop == 1) ? 'danger' : (($countTop == 2) ? 'warning' : (($countTop == 3) ? 'info' : 'success')); ?> fw-bold">
                                                        <?= htmlspecialchars($row['name']) ?>
                                                    </td>
                                                    <td
                                                        class="text-<?php echo ($countTop == 1) ? 'danger' : (($countTop == 2) ? 'warning' : (($countTop == 3) ? 'info' : 'success')); ?> fw-bold">
                                                        <?= formatValue($row['tongdiem']) ?>
                                                    </td>
                                                    <td
                                                        class="text-<?php echo ($countTop == 1) ? 'danger' : (($countTop == 2) ? 'warning' : (($countTop == 3) ? 'info' : 'success')); ?> fw-bold">
                                                        <?= getPlanetName($row['gender']) ?>
                                                    </td>
                                                </tr>
                                                <?php $countTop++; ?>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6">Máy Chủ 1 chưa có thông kê bảng xếp hạng!</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php
                        function formatValue($value)
                        {
                            if ($value != '') {
                                if ($value > 1000000000) {
                                    return number_format($value / 1000000000, 1, '.', '') . ' tỷ';
                                } elseif ($value > 1000000) {
                                    return number_format($value / 1000000, 1, '.', '') . ' triệu';
                                } elseif ($value >= 1000) {
                                    return number_format($value / 1000, 1, '.', '') . ' k';
                                } else {
                                    return number_format($value, 0, ',', '');
                                }
                            } else {
                                return 'Không có chỉ số';
                            }
                        }
                        function getPlanetName($gender)
                        {
                            switch ($gender) {
                                case 0:
                                    return "Trái Đất";
                                case 1:
                                    return "Namec";
                                case 2:
                                    return "Xayda";
                                default:
                                    return "Không xác định";
                            }
                        }
                        ?>
                    </div>
                    <script>
                        function showTable(table) {
                            var napTable = document.getElementById('nap-table');
                            var sucManhTable = document.getElementById('sucmanh-table');

                            if (table === 'nap') {
                                napTable.style.display = 'block';
                                sucManhTable.style.display = 'none';
                            } else if (table === 'sucmanh') {
                                napTable.style.display = 'none';
                                sucManhTable.style.display = 'block';
                            }
                        }
                    </script>
                </div>
            </div>
            <div class="post-info mt-2"></div>
        </div>
    </div>
</div>
<?php
require_once '../../Controllers/Footer.php';
?>