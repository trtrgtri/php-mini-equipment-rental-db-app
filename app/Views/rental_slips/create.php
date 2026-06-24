<?php

/** * @var array $old 
 * @var array $errors 
 * @var array $availableEquipments 
 */
ob_start();
?>
<div class="page-header">
    <?php if (isset($errors['slip_code'])): ?>
        <h1>Create Rental - Duplicate Slip Code</h1>
    <?php else: ?>
        <h1>Create Rental</h1>
        <p>Form này submit bằng POST /rentals/store, nếu thành công sẽ redirect về /rentals.</p>
    <?php endif; ?>
</div>

<?php if (isset($errors['slip_code'])): ?>
    <div class="alert error" style="margin-bottom: 24px;">
        Mã phiếu mượn này đã tồn tại. Vui lòng nhập mã khác.
    </div>
<?php endif; ?>

<div class="form-layout">
    <form method="post" action="/rentals/store" class="card">
        <div class="form-grid">
            <div class="form-group-row">
                <label>Slip code</label>
                <div>
                    <input type="text" name="slip_code" value="<?= e($old['slip_code'] ?? '') ?>" class="<?= isset($errors['slip_code']) ? 'input-error' : '' ?>">
                    <?php if (isset($errors['slip_code'])): ?>
                        <p class="field-error" style="color:#dc2626; font-size:13px; margin-top:4px;">slip_code bị unique constraint chặn.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group-row">
                <label>Equipment</label>
                <div>
                    <select name="equipment_id" class="<?= isset($errors['equipment_id']) ? 'input-error' : '' ?>">
                        <option value="">-- Chọn thiết bị --</option>
                        <?php foreach ($availableEquipments as $eq): ?>
                            <option value="<?= e($eq['id']) ?>" <?= (($old['equipment_id'] ?? '') == $eq['id']) ? 'selected' : '' ?>>
                                <?= e($eq['equipment_code']) ?> - <?= e($eq['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['equipment_id'])): ?><p class="field-error"><?= e($errors['equipment_id']) ?></p><?php endif; ?>
                </div>
            </div>

            <div class="form-group-row">
                <label>Borrower name</label>
                <div>
                    <input type="text" name="borrower_name" value="<?= e($old['borrower_name'] ?? '') ?>" class="<?= isset($errors['borrower_name']) ? 'input-error' : '' ?>">
                    <?php if (isset($errors['borrower_name'])): ?><p class="field-error"><?= e($errors['borrower_name']) ?></p><?php endif; ?>
                </div>
            </div>

            <div class="form-group-row">
                <label>Borrower email</label>
                <input type="email" name="borrower_email" value="<?= e($old['borrower_email'] ?? '') ?>">
            </div>

            <div class="form-group-row">
                <label>Status</label>
                <input type="text" name="status" value="borrowed" readonly style="background: #f8fafc; color: #64748b; outline: none; border-color: #e2e8f0; cursor: not-allowed;">
            </div>
        </div>

        <div class="form-actions" style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
            <button class="btn primary" type="submit">Save Rental</button>
        </div>
    </form>

    <div class="info-box" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
        <?php if (isset($errors['slip_code'])): ?>
            <h3 style="margin-top:0;">Database rule</h3>
            <p style="color: #991b1b; font-weight: bold; font-family: monospace; font-size: 1.1rem; margin-bottom: 8px;">UNIQUE KEY<br>unique_slip_code</p>
            <p style="color: #64748b; font-size: 0.9rem;">Tầng PHP có thể kiểm tra trước, nhưng database là lớp bảo vệ cuối cùng.</p>
        <?php else: ?>
            <h3 style="margin-top:0;">Form requirements</h3>
            <ul style="list-style-type: '✔  '; margin-left: 0; padding-left: 20px; color: #475569; font-size: 0.9rem; line-height: 1.8;">
                <li>Validate required fields</li>
                <li>Prepared statement INSERT</li>
                <li>Catch duplicate slip code</li>
                <li>PRG after success</li>
                <li>Keep old data when error</li>
            </ul>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Create Rental';
require __DIR__ . '/../layout.php';
?>