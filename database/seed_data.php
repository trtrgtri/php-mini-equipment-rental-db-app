<?php
$config = require __DIR__ . '/../config/database.php';
try {
    $db = new PDO("mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}", $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    echo "Dang sinh 200 thiet bi mau...\n";
    $stmt = $db->prepare("INSERT INTO equipments (equipment_code, name, category, status) VALUES (?, ?, ?, ?)");
    $categories = ['Điện tử', 'Văn phòng', 'Sự kiện', 'Y tế'];
    $statuses = ['available', 'rented', 'maintenance'];

    for ($i = 1; $i <= 200; $i++) {
        $code = 'EQ-BONUS-' . str_pad($i, 4, '0', STR_PAD_LEFT);
        $stmt->execute([
            $code,
            "Thiết bị test tự động số " . $i,
            $categories[array_rand($categories)],
            $statuses[array_rand($statuses)]
        ]);
    }
    echo "Da tao xong 200 ban ghi thanh cong! Hay vao web kiem tra Pagination.\n";
} catch (Exception $e) {
    echo "Loi: " . $e->getMessage();
}
