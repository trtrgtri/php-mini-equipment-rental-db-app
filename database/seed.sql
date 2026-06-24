-- =================================================================
-- 5. NẠP DỮ LIỆU (CHẠY NGAY SAU KHI TẠO BẢNG ĐỂ ĐẢM BẢO ID KHỚP NHAU)
-- =================================================================

-- Nạp dữ liệu Users
INSERT INTO users (name, email, password_hash, role, status) VALUES
('Quản trị viên', 'admin@example.com', '$2y$10$examplehashadmin', 'admin', 'active'),
('Nhân viên Kho', 'staff@example.com', '$2y$10$examplehashstaff', 'staff', 'active');

-- Nạp 15 dữ liệu Equipments
INSERT INTO equipments (equipment_code, name, category, status, note) VALUES
('EQ-2026-001', 'Máy chiếu Sony Bravia', 'Điện tử', 'available', 'Đầy đủ cáp HDMI và remote'),
('EQ-2026-002', 'Bảng trắng di động 1m2', 'Văn phòng', 'rented', 'Kèm 3 bút lông và đồ lau'),
('EQ-2026-003', 'Loa kéo sự kiện Sony', 'Sự kiện', 'available', 'Kèm 2 micro không dây'),
('EQ-2026-004', 'Micro thu âm Rode', 'Điện tử', 'available', 'Trong hộp chống sốc'),
('EQ-2026-005', 'Laptop Dell XPS 15', 'Điện tử', 'rented', 'Máy trầy xước nhẹ mặt lưng'),
('EQ-2026-006', 'Màn hình Dell Ultrasharp 24"', 'Điện tử', 'available', ''),
('EQ-2026-007', 'Bộ đàm Motorola', 'Sự kiện', 'available', 'Pin còn 80%, kèm dock sạc'),
('EQ-2026-008', 'Máy in Canon LBP2900', 'Văn phòng', 'available', 'Mới thay mực tuần trước'),
('EQ-2026-009', 'Camera Sony Alpha A7III', 'Điện tử', 'maintenance', 'Đang gửi bảo hành lỗi sensor'),
('EQ-2026-010', 'Tripod máy ảnh Benro', 'Sự kiện', 'available', ''),
('EQ-2026-011', 'Đèn LED quay phim', 'Điện tử', 'rented', 'Kèm chân đèn đứng'),
('EQ-2026-012', 'Ghế xoay văn phòng', 'Văn phòng', 'available', 'Mới 100%'),
('EQ-2026-013', 'Bàn xếp sự kiện', 'Sự kiện', 'rented', 'Chân bàn hơi rỉ sét'),
('EQ-2026-014', 'Máy phát điện mini', 'Sự kiện', 'available', 'Nhớ châm xăng trước khi dùng'),
('EQ-2026-015', 'Ổ cắm điện Lioa 10m', 'Văn phòng', 'available', '');

-- Nạp 15 dữ liệu Rental Slips
INSERT INTO rental_slips (slip_code, equipment_id, borrower_name, borrower_email, status, created_at) VALUES
('SLIP-2026-001', 2, 'Trần Trọng Trí', 'tri.tran@example.com', 'borrowed', '2026-06-15 08:30:00'),
('SLIP-2026-002', 5, 'Nguyễn Văn An', 'an.nguyen@example.com', 'borrowed', '2026-06-18 10:15:00'),
('SLIP-2026-003', 11, 'Lê Thị Hoa', 'hoa.le@example.com', 'borrowed', '2026-06-20 09:00:00'),
('SLIP-2026-004', 13, 'Phạm Minh Tuấn', 'tuan.pham@example.com', 'borrowed', '2026-06-21 14:20:00'),
('SLIP-2026-005', 1, 'Hoàng Thu Trang', 'trang.hoang@example.com', 'returned', '2026-06-22 16:50:00'),
('SLIP-2026-006', 3, 'Đỗ Văn Cường', 'cuong.do@example.com', 'returned', '2026-06-01 09:00:00'),
('SLIP-2026-007', 4, 'Bùi Thị Dung', 'dung.bui@example.com', 'returned', '2026-06-02 10:30:00'),
('SLIP-2026-008', 6, 'Vũ Văn Em', 'em.vu@example.com', 'returned', '2026-06-03 14:00:00'),
('SLIP-2026-009', 7, 'Ngô Thị Phương', 'phuong.ngo@example.com', 'returned', '2026-06-04 16:15:00'),
('SLIP-2026-010', 8, 'Đặng Văn Giang', 'giang.dang@example.com', 'returned', '2026-06-05 08:45:00'),
('SLIP-2026-011', 10, 'Lý Thị Hà', 'ha.ly@example.com', 'returned', '2026-06-06 11:20:00'),
('SLIP-2026-012', 12, 'Trần Văn Inh', 'inh.tran@example.com', 'returned', '2026-06-07 13:50:00'),
('SLIP-2026-013', 14, 'Nguyễn Thị Kim', 'kim.nguyen@example.com', 'returned', '2026-06-08 15:10:00'),
('SLIP-2026-014', 15, 'Lê Văn Long', 'long.le@example.com', 'returned', '2026-06-09 09:30:00'),
('SLIP-2026-015', 3, 'Phạm Thị Mai', 'mai.pham@example.com', 'returned', '2026-06-10 10:00:00');
