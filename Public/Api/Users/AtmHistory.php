<?php
include '../../Controllers/Configs.php';

if (isset($_Id)) {
    $itemsPerPage = 5;
    $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $startIndex = ($currentPage - 1) * $itemsPerPage;
    $stmt = $Connect->prepare("SELECT * FROM `{$ImSGame}`.payments WHERE `{$ImSGame}`.payments.name = :id ORDER BY id DESC LIMIT :startIndex, :itemsPerPage");
    $stmt->bindParam(":id", $_Id);
    $stmt->bindParam(":startIndex", $startIndex, PDO::PARAM_INT);
    $stmt->bindParam(":itemsPerPage", $itemsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $countStmt = $Connect->prepare("SELECT COUNT(*) FROM `{$ImSGame}`.payments WHERE name = :id");
    $countStmt->bindParam(":id", $_Id);
    $countStmt->execute();
    $totalItems = $countStmt->fetchColumn();
    if ($result) {
        echo '<div class="products-area-wrapper tableView">';
        echo '<div class="products-header">';
        echo '<div class="product-cell fw-bolder">STT</div>';
        echo '<div class="product-cell fw-bolder">Số Tiền</div>';
        echo '<div class="product-cell fw-bolder">Trạng Thái</div>';
        echo '<div class="product-cell fw-bolder">Mã Giao Dịch</div>';
        echo '<div class="product-cell fw-bolder">Ngày Tháng</div>';
        echo '</div>';

        foreach ($result as $index => $row) {
            $count = $startIndex + $index + 1;
            $status = getStatusLabel($row['status']);

            echo '<div class="products-row">';
            echo '<div class="product-cell">' . $count . '</div>';
            echo '<div class="product-cell">' . formatMoney($row['amount']) . '</div>';
            echo '<div class="product-cell">' . $status . '</div>';
            echo '<div class="product-cell">' . htmlspecialchars($row['refNo']) . '</div>';
            echo '<div class="product-cell">' . htmlspecialchars($row['date']) . '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '<br>';
        echo '<ul class="pagination justify-content-end pagination-custom-style">';
        $totalPages = ceil($totalItems / $itemsPerPage);
        if ($currentPage > 1) {
            echo '<li><a class="btn btn-sm d" href="?page=' . ($currentPage - 1) . '"><</a></li>';
        }

        $numLinks = min(3, $totalPages);
        $middlePage = floor($numLinks / 2);
        $startPage = max(1, $currentPage - $middlePage);
        $endPage = min($totalPages, $startPage + $numLinks - 1);
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $currentPage) {
                echo '<li><a class="btn btn-sm active-page">' . $i . '</a></li>';
            } else {
                echo '<li><a class="btn btn-sm current-page" href="?page=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($currentPage < $totalPages) {
            echo '<li><a class="btn btn-sm current-page" href="?page=' . ($currentPage + 1) . '">></a></li>';
        }

        echo '</ul>';
    } else {
        echo '<div class="text-center">Không có dữ liệu lịch sử giao dịch ATM.</div>';
    }
} else {
    echo '<div class="text-center">Không tìm thấy tên người dùng trong bảng account.</div>';
}
