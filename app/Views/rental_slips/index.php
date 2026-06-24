<?php

ob_start();
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 style="margin: 0; font-size: 2rem;">Danh sách Phiếu Mượn</h1>
    <a class="btn primary" href="/rentals/create">+ Tạo Phiếu Mượn</a>
</div>

<form method="get" action="/rentals" class="toolbar">
    <input type="hidden" name="page" value="1">
    <div class="flex-grow">
        <label style="margin:0; min-width: max-content;">Tìm kiếm</label>
        <input type="text" name="q" value="<?= e($q) ?>" placeholder="Mã phiếu, Tên người mượn...">
    </div>
    <span class="sort-info">Sắp xếp: <?= e($sort) ?> <?= strtoupper(e($direction)) ?> | Hiển thị: <?= e($perPage) ?></span>
    <button type="submit" class="btn secondary">Lọc</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th><a href="/rentals?<?= e(query_string(['sort' => 'slip_code'])) ?>">Mã Phiếu</a></th>
            <th>Thiết bị</th>
            <th>Người mượn</th>
            <th>Trạng thái</th>
            <th><a href="/rentals?<?= e(query_string(['sort' => 'created_at'])) ?>">Ngày tạo</a></th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($slips as $slip): ?>
            <?php
            $badgeClass = 'badge-blue';
            if ($slip['status'] === 'returned') $badgeClass = 'badge-green';
            if ($slip['status'] === 'overdue') $badgeClass = 'badge-red';
            ?>
            <tr>
                <td><?= e($slip['id']) ?></td>
                <td style="font-family: monospace; font-weight: bold;"><?= e($slip['slip_code']) ?></td>
                <td><?= e($slip['equipment_name']) ?></td>
                <td><?= e($slip['borrower_name']) ?></td>
                <td><span class="badge <?= $badgeClass ?>"><?= e($slip['status']) ?></span></td>
                <td><?= date('d/m/Y H:i', strtotime($slip['created_at'])) ?></td>
                <td>
                    <a href="/rentals/edit?id=<?= e($slip['id']) ?>" style="color: #2563eb; text-decoration: none; margin-right: 12px; font-weight: 500;">Sửa</a>
                    <form method="post" action="/rentals/delete" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phiếu mượn này?')">
                        <input type="hidden" name="id" value="<?= e($slip['id']) ?>">
                        <button type="submit" style="background: transparent; border: none; color: #dc2626; cursor: pointer; padding: 0; text-decoration: none; font-weight: 500; font-size: 1rem; font-family: inherit;">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <span class="pagination-info">Đang hiển thị <?= count($slips) ?> trên tổng số <?= e($total) ?> phiếu mượn</span>
    <?php if ($page > 1): ?>
        <a href="/rentals?<?= e(query_string(['page' => $page - 1])) ?>">Trước</a>
    <?php endif; ?>

    <span class="current"><?= e($page) ?></span>

    <?php if ($page < $totalPages): ?>
        <a href="/rentals?<?= e(query_string(['page' => $page + 1])) ?>">Sau</a>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'Quản lý Phiếu Mượn';
require __DIR__ . '/../layout.php';
?>