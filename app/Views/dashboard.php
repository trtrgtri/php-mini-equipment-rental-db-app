<?php ob_start(); ?>
<div class="page-header">
    <h1>Lab05 - Database CRUD Management App</h1>
    <p>PDO + Repository + CRUD + Search/Pagination + Unique + Index</p>
</div>

<div class="dashboard-grid">
    <div class="dash-card">
        <div class="dash-icon" style="background: #e0f2fe; color: #0369a1;">{ }</div>
        <h3>Database</h3>
        <p>equipments / rentals<br>utf8mb4 +<br>constraints</p>
    </div>
    <div class="dash-card">
        <div class="dash-icon" style="background: #f3e8ff; color: #7e22ce;">{ }</div>
        <h3>PDO Repository</h3>
        <p>Prepared statements<br>No SQL string concat</p>
    </div>
    <div class="dash-card">
        <div class="dash-icon" style="background: #dcfce7; color: #166534;">{ }</div>
        <h3>Equipment CRUD</h3>
        <p>List, create, edit,<br>delete friendly</p>
    </div>
    <div class="dash-card">
        <div class="dash-icon" style="background: #fef08a; color: #854d0e;">{ }</div>
        <h3>Rental CRUD</h3>
        <p>Ticket code unique<br>Search + pagination</p>
    </div>
    <div class="dash-card">
        <div class="dash-icon" style="background: #fee2e2; color: #991b1b;">{ }</div>
        <h3>Performance</h3>
        <p>Index + EXPLAIN<br>LIMIT / OFFSET safe</p>
    </div>
</div>

<div class="note-footer">
    <p><strong>Luồng chính: Browser -> public/index.php -> Router -> Controller -> Repository -> PDO -> MySQL</strong></p>
    <p style="color: #64748b;">Mục tiêu: CRUD không chỉ chạy được, mà phải an toàn, sạch dữ liệu và có khả năng mở rộng.</p>
</div>
<?php
$content = ob_get_clean();
$title = 'Dashboard';
require __DIR__ . '/layout.php';
