<?php
include '../../Controllers/Header.php';
?>

<div class="container px-0">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mt-0 mb-0">Lịch sử nạp thẻ và giao dịch ATM</h5>
                <button id="reloadDataBtn" class="btn btn-primary">Tải lại dữ liệu</button>
            </div>
            <p class="text-muted mb-4">
                Dưới đây là thông tin chi tiết về các giao dịch nạp thẻ và chuyển khoản của bạn.
                Bạn có thể kiểm tra lịch sử giao dịch để theo dõi các hoạt động tài chính của mình.
            </p>
            <hr>

            <div class="mb-4">
                <h5 class="mt-3 mb-2">Thẻ Cào</h5>
                <p class="text-muted mb-3">Xem chi tiết các giao dịch nạp thẻ của bạn, bao gồm ngày giờ, số tiền và trạng thái.</p>
                <div id="napthe-history" class="table-responsive">
                    <!-- Dữ liệu nạp thẻ sẽ được tải ở đây -->
                    <p class="text-center text-muted">Đang tải lịch sử nạp thẻ...</p>
                </div>
            </div>

            <div>
                <h5 class="mt-3 mb-2">Chuyển Khoản - ATM</h5>
                <p class="text-muted mb-3">Theo dõi các giao dịch ATM của bạn, bao gồm thông tin về thời gian, địa điểm và số tiền đã giao dịch.</p>
                <div id="atm-lichsu" class="table-responsive">
                    <!-- Dữ liệu giao dịch ATM sẽ được tải ở đây -->
                    <p class="text-center text-muted">Đang tải lịch sử giao dịch ATM...</p>
                </div>
            </div>
            <br><br>
            <hr>
            <footer class="mt-4">
                <p class="text-muted text-center">Nếu bạn gặp bất kỳ vấn đề nào trong việc xem lịch sử giao dịch, vui lòng liên hệ với bộ phận hỗ trợ của chúng tôi.</p>
            </footer>
        </div>
    </div>
</div>
<script>
    function loadNapTheHistory(page) {
        $.ajax({
            url: '/Api/Users/CardHistory',
            type: 'GET',
            data: {
                page: page
            },
            success: function(response) {
                $('#napthe-history').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                $('#napthe-history').html('<p class="text-danger">Không thể tải lịch sử nạp thẻ. Vui lòng thử lại.</p>');
            }
        });
    }

    function loadATMLichsu(page) {
        $.ajax({
            url: '/Api/Users/AtmHistory',
            type: 'GET',
            data: {
                page: page
            },
            success: function(response) {
                $('#atm-lichsu').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                $('#atm-lichsu').html('<p class="text-danger">Không thể tải lịch sử giao dịch ATM. Vui lòng thử lại.</p>');
            }
        });
    }

    $(document).ready(function() {
        var napThePage = 1;
        var atmPage = 1;

        loadNapTheHistory(napThePage);
        loadATMLichsu(atmPage);

        $('#reloadDataBtn').click(function() {
            loadNapTheHistory(napThePage);
            loadATMLichsu(atmPage);
        });

        $(document).on('click', '.pagination-custom-style a', function(e) {
            e.preventDefault();
            napThePage = $(this).attr('href').split('page=')[1];
            loadNapTheHistory(napThePage);
        });

        $(document).on('click', '.pagination-custom-style2 a', function(e) {
            e.preventDefault();
            atmPage = $(this).attr('href').split('page=')[1];
            loadATMLichsu(atmPage);
        });
    });
</script>

<?php
include '../../Controllers/Footer.php';
?>