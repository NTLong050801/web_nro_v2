<?php
require_once 'Public/Controllers/Header.php';
?>
<div class="card">
    <div class="card-body">
        <div class="card-title h5">Bài viết mới</div>
        <hr>
        <div>
            <div class="post-item d-flex align-items-center my-2">
                <div class="post-image">
                    <img src="/Public/Assets/Images/<?= LOGO ?>" class="logo" valt="Bảng Xếp Hạng">
                </div>
                <div>
                    <a class="fw-bold " href="/Others/Top">Bảng Xếp Hạng </a>
                    <div class="text-muted font-weight-bold">Đã đăng bởi <span class="text-danger fw-bold">Admin</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-2 card-title h5">Danh mục</div>
        <hr>
        <div>
            <div class="post-item d-flex align-items-center my-2">
                <div class="post-image">
                    <img src="/Public/Assets/Images/<?= LOGO ?>" class="logo" alt="Hướng dẫn - Tính năng">
                </div>
                <div>
                    <a class="fw-bold text-danger" href="/Others/Tutorial">Hướng dẫn - Tính năng </a>
                    <div class="text-muted font-weight-bold">Các bài viết hướng dẫn về tính năng trên GAME</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'Public/Controllers/Footer.php';
?>