<?php ob_start(); ?>
<div class="page-header">
    <h1 style="font-size: 2rem;">Something went wrong</h1>
    <p>Production mode không hiển thị SQLSTATE hoặc đường dẫn file cho người dùng.</p>
</div>

<div class="alert error" style="padding: 24px;">
    <h3 style="margin: 0 0 8px 0;">Sorry, we could not process your request right now.</h3>
    <p style="margin: 0; color: #7f1d1d;">Please try again later or contact the administrator.</p>
</div>

<div class="info-box" style="background: #f8fafc; border-color: #e2e8f0;">
    <h4 style="margin: 0 0 8px 0; color: #334155;">Developer note:</h4>
    <p style="margin: 0;">Chi tiết lỗi được ghi vào log; giao diện chỉ hiển thị safe message.</p>
</div>

<div style="margin-top: 24px;">
    <a href="/" class="btn outline">Back to Dashboard</a>
</div>
<?php
$content = ob_get_clean();
$title = 'Server Error';
require __DIR__ . '/../layout.php';
