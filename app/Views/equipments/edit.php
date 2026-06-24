<?php ob_start(); ?>
<div class="page-header">
    <h1>Cập nhật Thiết bị</h1>
</div>

<div class="form-layout">
    <form method="post" action="/equipments/update" class="card">
        <input type="hidden" name="id" value="<?= e($old['id']) ?>">

        <div class="form-grid">
            <div class="form-group-row">
                <label>Mã thiết bị</label>
                <div>
                    <input type="text" name="equipment_code" value="<?= e($old['equipment_code'] ?? '') ?>" class="<?= isset($errors['equipment_code']) ? 'input-error' : '' ?>">
                    <?php if (isset($errors['equipment_code'])): ?><p class="field-error"><?= e($errors['equipment_code']) ?></p><?php endif; ?>
                </div>
            </div>

            <div class="form-group-row">
                <label>Tên thiết bị</label>
                <div>
                    <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" class="<?= isset($errors['name']) ? 'input-error' : '' ?>">
                    <?php if (isset($errors['name'])): ?><p class="field-error"><?= e($errors['name']) ?></p><?php endif; ?>
                </div>
            </div>

            <div class="form-group-row">
                <label>Danh mục</label>
                <input type="text" name="category" value="<?= e($old['category'] ?? '') ?>">
            </div>

            <div class="form-group-row">
                <label>Trạng thái</label>
                <select name="status">
                    <option value="available" <?= ($old['status'] === 'available') ? 'selected' : '' ?>>Rảnh</option>
                    <option value="rented" <?= ($old['status'] === 'rented') ? 'selected' : '' ?>>Đang cho mượn</option>
                    <option value="maintenance" <?= ($old['status'] === 'maintenance') ? 'selected' : '' ?>>Bảo trì</option>
                </select>
            </div>

            <div class="form-group-row">
                <label>Ghi chú</label>
                <textarea name="note"><?= e($old['note'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn primary" type="submit">Lưu Thay Đổi</button>
            <a class="btn outline" href="/equipments">Hủy</a>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
$title = 'Sửa Thiết bị';
require __DIR__ . '/../layout.php';
?>