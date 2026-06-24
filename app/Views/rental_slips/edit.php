<?php ob_start(); ?>
<div class="page-header">
    <h1>Cập nhật Phiếu Mượn</h1>
</div>

<div class="form-layout">
    <form method="post" action="/rentals/update" class="card">
        <input type="hidden" name="id" value="<?= e($old['id']) ?>">

        <div class="form-grid">
            <div class="form-group-row">
                <label>Mã phiếu mượn</label>
                <input type="text" value="<?= e($old['slip_code']) ?>" readonly style="background: #f1f5f9; color: #64748b; cursor: not-allowed;">
            </div>

            <div class="form-group-row">
                <label>Thiết bị</label>
                <input type="text" value="<?= e($equipment['equipment_code'] ?? '') ?> - <?= e($equipment['name'] ?? '') ?>" readonly style="background: #f1f5f9; color: #64748b; cursor: not-allowed;">
            </div>

            <div class="form-group-row">
                <label>Người mượn</label>
                <div>
                    <input type="text" name="borrower_name" value="<?= e($old['borrower_name'] ?? '') ?>" class="<?= isset($errors['borrower_name']) ? 'input-error' : '' ?>">
                    <?php if (isset($errors['borrower_name'])): ?><p class="field-error"><?= e($errors['borrower_name']) ?></p><?php endif; ?>
                </div>
            </div>

            <div class="form-group-row">
                <label>Email người mượn</label>
                <input type="email" name="borrower_email" value="<?= e($old['borrower_email'] ?? '') ?>">
            </div>

            <div class="form-group-row">
                <label>Trạng thái</label>
                <select name="status">
                    <option value="borrowed" <?= ($old['status'] === 'borrowed') ? 'selected' : '' ?>>Đang mượn</option>
                    <option value="returned" <?= ($old['status'] === 'returned') ? 'selected' : '' ?>>Đã trả (Hệ thống sẽ trả lại thiết bị vào kho)</option>
                    <option value="overdue" <?= ($old['status'] === 'overdue') ? 'selected' : '' ?>>Quá hạn</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn primary" type="submit">Cập nhật Phiếu</button>
            <a class="btn outline" href="/rentals">Hủy</a>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
$title = 'Sửa Phiếu Mượn';
require __DIR__ . '/../layout.php';
?>