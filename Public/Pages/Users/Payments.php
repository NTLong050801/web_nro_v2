<?php
require_once '../../Controllers/Header.php';
?>

<div class="card">
    <div class="card-body">
        <div class="col-md-12">
            <div class="row text-center justify-content-center my-1 mb-2">
                <div class="col-md-12 mb-4">
                    <div class="row text-center justify-content-center row-cols-2 row-cols-lg-5 g-2 g-lg-2 my-1 mb-2" id="custom-tabs" role="tablist">
                        <div class="col">
                            <a class="w-100 fw-semibold active" id="tab-atm" data-toggle="tab" href="#content-atm" role="tab" aria-controls="content-atm" aria-selected="false">
                                <div class="recharge-method-item active">
                                    <img src="/Public/Assets/Images/Atm.png" alt="Atm" data-pin-no-hover="true">
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="content-atm" role="tabpanel" aria-labelledby="tab-atm">
                            <div class="row justify-content-center">
                                <div class="col-md-8 mt-3">
                                    <div id="selectedAmountMessage" class="mt-3 text-center"></div>
                                    <div id="list_atm" class="row text-center justify-content-center row-cols-2 row-cols-lg-3 g-2 g-lg-2 my-1 mb-2">
                                        <?php if (isset($rechargeOptions) && is_iterable($rechargeOptions)): ?>
                                            <?php foreach ($rechargeOptions as $option): ?>
                                                <div class="col">
                                                    <div class="w-100 fw-semibold cursor-pointer recharge-method-item position-relative nduckien"
                                                        style="height: 90px;" data-amount="<?= htmlspecialchars($option['displayAmount']) ?>">
                                                        <div class="price_atm"><?= htmlspecialchars($option['displayAmount']) ?></div>
                                                        <div class="center-text text-danger"><span>Nhận</span></div>
                                                        <div class="text-primary"><?= htmlspecialchars($option['displayCoins']) ?></div>
                                                        <?php if ($option['displayBonus'] !== '0%'): ?>
                                                            <span class="text-white position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger-2"
                                                                style="z-index: 1;">
                                                                <?= htmlspecialchars($option['displayBonus']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <h6>Không thể thực hiện vui lòng báo cho Admin!</h6>
                                        <?php endif; ?>
                                    </div>

                                    <div id="atm_info"></div>
                                    <div class="text-center mt-3 atm-btn">
                                        <?php if ($_StatusAtm == 0) { ?>
                                            <button type="button" id="payment_atm" class="w-50 rounded-3 btn btn-primary btn-sm">Thanh toán</button>
                                        <?php } else { ?>
                                            <button type="button" class="w-50 rounded-3 btn btn-primary btn-sm disabled" disabled>Đang bảo trì</button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-2">
                                <a href="/Users/History">
                                    <p class="history">Lịch sử nạp thẻ</p>
                                </a>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="custom-tab-content" role="tabpanel" aria-labelledby="tab-atm">
                            <div class="row justify-content-center">
                                <div class="col-md-8 mt-3">
                                    <div>
                                        <div>
                                            <div class="table-responsive-sm">
                                                <table class="fw-semibold mt-3 table">
                                                    <tbody>
                                                        <tr>
                                                            <td>Ngân hàng</td>
                                                            <td>Vietcombank ( VCB )</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Chủ tài khoản</td>
                                                            <td><?= $AChauBank['Name'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Số tài khoản</td>
                                                            <td><?= $AChauBank['Account'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Số tiền</td>
                                                            <td id="amountDisplay">
                                                                0đ
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nội dung</td>
                                                            <td>naptien <?= $_Id ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-center fw-semibold fs-5">Quét mã để thanh toán</div>
                                            <div class="text-center mt-2"><img src="https://img.vietqr.io/image/VCB-<?= $AChauBank['Account'] ?>-qr_only.png?&addInfo=naptien <?= $_Id ?>&accountName=<?= $AChauBank['Name'] ?>" alt="" style="height: 250px;"></div>
                                        </div>
                                        <div class="text-center mt-3"><button type="button" id="confirmPaymentDetails" class="w-50 rounded-3 btn btn-primary btn-sm">Xác nhận (60)</button>
                                            <div class="mt-2"><small class="fw-semibold"><a href="/Users/History">Lịch sử giao dịch</a></small></div>
                                        </div>
                                        <div class="mt-4"><small class="fw-semibold">Lưu ý khi thanh toán: Giao dịch trên hoàn toàn được kiểm duyệt tự động, yêu cầu kiểm tra kỹ nội dung chuyển tiền trước khi thực hiện chuyển. Nếu ghi thiếu, sai hoặc quá 10 phút không thấy cộng tiền, các bạn hãy liên hệ với <a target="_blank" href="https://www.facebook.com/vutrungocrong.official" rel="noreferrer">Fanpage</a> để được hỗ trợ<p></p></small></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../../Controllers/Footer.php';
?>
<script>
    $(document).ready(function() {
        // Cài đặt các sự kiện và chức năng
        $('#custom-tabs a').on('click', function(e) {
            e.preventDefault();
            var targetTab = $(this).attr('href');

            $(this).tab('show');
            $('#custom-tabs .recharge-method-item').removeClass('active');
            $(this).find('.recharge-method-item').addClass('active');

            updateMethodText(targetTab);
            window.history.pushState(null, null, window.location.pathname);

            if (targetTab === '#content-card') {
                $('#content-atm').hide();
                $('#custom-tab-content').hide();
            } else if (targetTab === '#content-atm') {
                $('#custom-tab-content').hide();
                $('#content-atm').show();
            }
        });

        function updateMethodText(tab) {
            if (tab === '#content-card') {
                $('.method').text('Thẻ cào');
            } else if (tab === '#content-atm') {
                $('.method').text('Ngân Hàng Quân Đội atm');
            }
        }

        $('#list_atm').on('click', '.recharge-method-item', function() {
            $('.nduckien').removeClass('active');
            $(this).addClass('active');
            const selectedAmountText = $(this).find('.price_atm').text();
            const bonusText = $(this).find('.text-white').text() || '0%';
            const selectedAmount = parseFloat(selectedAmountText.replace(/\./g, '').replace(',', '.'));
            const bonus = parseFloat(bonusText) / 100;
            const actualAmount = selectedAmount + (selectedAmount * bonus);
            $('#selectedAmountMessage').html(`<b>Bạn đang chọn mốc nạp: ${selectedAmountText}</b>`);

            if (bonus > 0) {
                const formattedActualAmount = actualAmount.toLocaleString('vi-VN', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });

                $('#selectedAmountMessage').append(`<br><b>Thực nhận: ${formattedActualAmount} đ</b>`);

                $('#amountDisplay').html(`
            ${selectedAmountText}
            <span class="text-white top-0 start-100 translate-middle badge rounded-pill bg-danger-2" 
                  style="margin-left: 10%; z-index: 1;">
                  ${bonusText}
            </span> | Thực Nhận: ${formattedActualAmount} đ
        `);

            } else {
                $('#amountDisplay').text(selectedAmountText);
            }

            $('#payment_atm').data('amount', selectedAmountText);
        });

        $('#payment_atm').on('click', function() {
            var selectedAmount = $(this).data('amount');
            if (!selectedAmount) {
                toastr.error('Vui lòng chọn mốc nạp trước.', 'Error');
                return;
            }

            $('#custom-tab-content').show();
            $('#content-atm').hide();

            var countdown = 60;
            $('#confirmPaymentDetails').text('Xác nhận (' + countdown + ')');

            var timer = setInterval(function() {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(timer);
                    $('#custom-tab-content').hide();
                    $('#content-atm').show();
                    toastr.error('Bạn đã hết thời gian để xác nhận. Vui lòng chọn lại mốc nạp.', 'Error');
                } else {
                    $('#confirmPaymentDetails').text('Xác nhận (' + countdown + ')');
                }
            }, 1000);
        });

        $('#confirmPaymentDetails').on('click', function() {
            toastr.info('Vui lòng chờ 1-2 phút để máy chủ duyệt', 'Thông báo');
            $('#custom-tab-content').hide();
            $('#content-atm').show();
        });

        window.submitCard = function() {
            var telco = document.querySelector("[name=telco]").value;
            var amount = document.querySelector("[name=amount]").value;
            var serial = document.querySelector("[name=serial]").value;
            var code = document.querySelector("[name=code]").value;

            if (!telco || !amount || !serial || !code) {
                toastr.error('Vui lòng điền đầy đủ thông tin.', 'Error');
                return;
            }

            var submitButton = document.getElementById('cardSubmitBtn');
            var loadingSpinner = document.getElementById('loading-spinner');
            submitButton.disabled = true;
            loadingSpinner.classList.remove('d-none');

            fetch('/Api/Users/Card', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        telco: telco,
                        amount: amount,
                        serial: serial,
                        code: code
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message, 'Success');
                    } else {
                        toastr.error(data.message, 'Error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Có lỗi xảy ra khi nạp thẻ.', 'Error');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    loadingSpinner.classList.add('d-none');
                });
        }

        $('#custom-tab-content').hide();
    });
</script>