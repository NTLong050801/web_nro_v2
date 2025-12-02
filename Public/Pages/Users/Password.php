<?php
include '../../Controllers/Header.php';
?>
<div class="card">
    <div class="card-body">
        <div class="w-100 d-flex justify-content-center">
            <form id="change-password-form" class="pb-3" style="width: 26rem;">
                <div class="fs-5 fw-bold text-center mb-3">Đổi mật khẩu</div>
                <div class="mb-2">
                    <div class="input-group">
                        <input name="current_password" type="text" autocomplete="off" placeholder="Nhập mật khẩu hiện tại" class="form-control form-control-solid" value="">
                    </div>
                </div>
                <div class="mb-2">
                    <div class="input-group">
                        <input name="password" type="password" autocomplete="off" placeholder="Mật khẩu" class="form-control form-control-solid" value="">
                    </div>
                </div>
                <div class="mb-2">
                    <div class="input-group">
                        <input name="confirm_password" type="password" autocomplete="off" placeholder="Nhập lại mật khẩu" class="form-control form-control-solid" value="">
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="me-3 btn btn-primary">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include '../../Controllers/Footer.php';
?>
<script>
    $(document).ready(function() {
        $('#change-password-form').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.post("/Api/Users/Password", formData)
                .done(function(response) {
                    if (response.status === "success") {
                        toastr.success(response.message);
                        $("#unifiedModal").modal("hide");
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        toastr.error(response.message);
                    }
                })
                .fail(function(jqXHR) {
                    var errorMessage = jqXHR.responseJSON?.message || "Vui lòng thử lại trong ít phút nữa.";
                    toastr.error(errorMessage);
                });
        });
    });
</script>