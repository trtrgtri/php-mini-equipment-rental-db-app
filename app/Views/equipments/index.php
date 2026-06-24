<?php

ob_start();
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 style="margin: 0; font-size: 2rem;">Danh sách Thiết bị</h1>
    <a class="btn primary" href="/equipments/create">+ Thêm Thiết bị</a>
</div>

<form method="get" action="/equipments" style="display: flex; gap: 10px; align-items: center; margin-bottom: 20px;">
    <input type="hidden" name="page" value="1">

    <input type="text" name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Mã TB, Tên TB..." style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">

    <select name="status" style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">
        <option value="">Tất cả trạng thái</option>
        <option value="available" <?= (isset($status) && $status === 'available') ? 'selected' : '' ?>>Rảnh</option>
        <option value="rented" <?= (isset($status) && $status === 'rented') ? 'selected' : '' ?>>Đang cho mượn</option>
        <option value="maintenance" <?= (isset($status) && $status === 'maintenance') ? 'selected' : '' ?>>Bảo trì</option>
    </select>

    <button type="submit" style="padding: 6px 12px; cursor: pointer;">Lọc</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th><a href="/equipments?<?= e(query_string(['sort' => 'equipment_code'])) ?>">Mã TB</a></th>
            <th><a href="/equipments?<?= e(query_string(['sort' => 'name'])) ?>">Tên</a></th>
            <th>Danh mục</th>
            <th>Trạng thái</th>
            <th><a href="/equipments?<?= e(query_string(['sort' => 'created_at'])) ?>">Ngày tạo</a></th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($equipments as $eq): ?>
            <?php
            $badgeClass = 'badge-blue';
            if ($eq['status'] === 'rented') $badgeClass = 'badge-yellow';
            if ($eq['status'] === 'maintenance') $badgeClass = 'badge-red';
            ?>
            <tr>
                <td><?= e($eq['id']) ?></td>
                <td style="font-family: monospace; font-weight: bold;"><?= e($eq['equipment_code']) ?></td>
                <td><?= e($eq['name']) ?></td>
                <td><?= e($eq['category']) ?></td>
                <td><span class="badge <?= $badgeClass ?>"><?= e($eq['status']) ?></span></td>
                <td><?= date('d/m/Y', strtotime($eq['created_at'])) ?></td>
                <td>
                    <a href="/equipments/edit?id=<?= e($eq['id']) ?>" style="color: #2563eb; text-decoration: none; margin-right: 12px; font-weight: 500;">Sửa</a>
                    <form method="post" action="/equipments/delete" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thiết bị này?')">
                        <input type="hidden" name="id" value="<?= e($eq['id']) ?>">
                        <button type="submit" style="background: transparent; border: none; color: #dc2626; cursor: pointer; padding: 0; 
                        text-decoration: none; font-weight: 500; font-size: 1rem; font-family: inherit;">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <span class="pagination-info">Đang hiển thị <?= count($equipments) ?> trên tổng số <?= e($total) ?> thiết bị</span>
    <?php if ($page > 1): ?>
        <a href="/equipments?<?= e(query_string(['page' => $page - 1])) ?>">Trước</a>
    <?php endif; ?>

    <span class="current"><?= e($page) ?></span>

    <?php if ($page < $totalPages): ?>
        <a href="/equipments?<?= e(query_string(['page' => $page + 1])) ?>">Sau</a>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'Quản lý Thiết bị';
require __DIR__ . '/../layout.php';
?>