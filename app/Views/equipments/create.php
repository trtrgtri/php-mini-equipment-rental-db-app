<?php ob_start(); ?>
<h1>Thêm Thiết bị mới</h1>
<form method="post" action="/equipments/store" class="card form-card">
    <label>Mã thiết bị:</label>
    <input type="text" name="equipment_code" value="<?= e($old['equipment_code'] ?? '') ?>">
    <?php if (!empty($errors['equipment_code'])): ?><p class="error"><?= e($errors['equipment_code']) ?></p><?php endif; ?>

    <label>Tên thiết bị:</label>
    <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>">
    <?php if (!empty($errors['name'])): ?><p class="error"><?= e($errors['name']) ?></p><?php endif; ?>

    <label>Danh mục:</label>
    <input type="text" name="category" value="<?= e($old['category'] ?? '') ?>">

    <label>Trạng thái:</label>
    <select name="status">
        <?php foreach (['available' => 'Rảnh', 'rented' => 'Đang cho mượn', 'maintenance' => 'Bảo trì'] as $val => $label): ?>
            <option value="<?= e($val) ?>" <?= ($old['status'] ?? ($equipment['status'] ?? 'available')) === $val ? 'selected' : '' ?>>
                <?= e($label) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Ghi chú:</label>
    <textarea name="note"><?= e($old['note'] ?? ($equipment['note'] ?? '')) ?></textarea>
    <button class="btn primary" type="submit">Lưu</button>
    <a class="btn" href="/equipments">Quay lại</a>
</form>
<?php
$content = ob_get_clean();
$title = 'Thêm Thiết bị';
require __DIR__ . '/../layout.php';
?>