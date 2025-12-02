<?php
include '../../Controllers/Configs.php';

if (isset($_SESSION['ImSynZx_Login'])) {
    $itemsPerPage = 5;
    $currentPage = max(1, isset($_GET['page']) ? intval($_GET['page']) : 1);
    $startIndex = ($currentPage - 1) * $itemsPerPage;
    $stmt = $Connect->prepare("SELECT * FROM `{$ImSGame}`.napthe WHERE `{$ImSGame}`.napthe.user_nap = :username ORDER BY id DESC LIMIT :startIndex, :itemsPerPage");
    $stmt->bindParam(":username", $_SESSION['ImSynZx_Login']);
    $stmt->bindParam(":startIndex", $startIndex, PDO::PARAM_INT);
    $stmt->bindParam(":itemsPerPage", $itemsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $countStmt = $Connect->prepare("SELECT COUNT(*) FROM `{$ImSGame}`.napthe WHERE `{$ImSGame}`.napthe.user_nap = :username");
    $countStmt->bindParam(":username", $_SESSION['ImSynZx_Login']);
    $countStmt->execute();
    $totalItems = $countStmt->fetchColumn();

    if ($result) {
        echo '<div class="products-area-wrapper tableView">';
        echo '<div class="products-header">';
        echo '<div class="product-cell image fw-bolder">STT</div>';
        echo '<div class="product-cell category fw-bolder">Mã Thẻ</div>';
        echo '<div class="product-cell category fw-bolder">Loại Thẻ</div>';
        echo '<div class="product-cell status-cell fw-bolder">Trạng Thái</div>';
        echo '<div class="product-cell sales fw-bolder">Giá Trị</div>';
        echo '<div class="product-cell stock fw-bolder">Thời Gian</div>';
        echo '</div>';

        foreach ($result as $index => $row) {
            $telcoImage = getTelcoImage($row['telco']);
            $status = getStatusLabel($row['status']);
            $formattedDate = date('H:i d/m/Y', strtotime($row['created_at']));
            $stt = $totalItems - $startIndex - $index;
?>
            <div class="products-row">
                <div class="product-cell image">
                    <span>#<?= $stt; ?></span> 
                </div>
                <div class="product-cell category">
                    <span><?= htmlspecialchars($row['code']); ?></span>
                </div>
                <div class="product-cell category">
                    <span class="cell-label">Category:</span>
                    <img src="/Public/Assets/Images/<?= htmlspecialchars($telcoImage); ?>" alt="<?php echo htmlspecialchars($row['telco']); ?>">
                    <?= htmlspecialchars($row['telco']); ?>
                </div>
                <div class="product-cell status-cell">
                    <span class="cell-label">Status:</span>
                    <?= $status; ?>
                </div>
                <div class="product-cell sales">
                    <span class="cell-label">Sales:</span>
                    <?= formatMoney($row['amount']); ?>
                </div>
                <div class="product-cell stock">
                    <span class="cell-label">Stock:</span>
                    <?= $formattedDate; ?>
                </div>
            </div>
<?php
        }

        echo '</div>';
        echo '<br>';
        echo '<ul class="pagination justify-content-end pagination-custom-style">';

        $totalPages = ceil($totalItems / $itemsPerPage);
        if ($currentPage > 1) {
            echo '<li><a class="btn btn-sm current-page" href="?page=' . ($currentPage - 1) . '"><</a></li>';
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
        echo '<div class="text-center">Không có dữ liệu lịch sử nạp thẻ.</div>';
    }
} else {
    echo '<div class="text-center">Bạn cần đăng nhập để xem lịch sử nạp thẻ.</div>';
}
?>