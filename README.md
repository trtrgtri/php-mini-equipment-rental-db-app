# Mini Equipment Rental DB App

Ứng dụng quản lý **thiết bị** và **phiếu mượn thiết bị** được xây dựng bằng **PHP + MySQL (PDO)** theo mô hình **Front Controller + Repository Pattern**, thực hiện trong **PHP Lab05**.

---

## 1. Cài đặt & Cấu hình Database

### Lưu ý bảo mật

File `config/database.php` chứa thông tin kết nối nhạy cảm nên đã được cấu hình loại bỏ trong `.gitignore`.

Để chạy ứng dụng:

1. Copy file `config/database.example.php` thành `config/database.php`
2. Chỉnh sửa thông tin kết nối bên trong cho phù hợp với môi trường local
3. Import 2 file `database/schema.sql` và `database/seed.sql` vào MySQL

### Chạy project

```bash
composer dump-autoload
php -S localhost:8000 -t public
```

---

## 2. Danh sách Route

| Method | URL                      | Chức năng                                       |
| ------ | ------------------------ | ----------------------------------------------- |
| GET    | /                        | Dashboard                                       |
| GET    | /health                  | Kiểm tra trạng thái kết nối Database            |
| GET    | /equipments              | Danh sách thiết bị (Search, Pagination, Sort)   |
| GET    | /equipments/create       | Form thêm thiết bị                              |
| POST   | /equipments/store        | Lưu thiết bị mới                                |
| GET    | /equipments/edit?id={id} | Form chỉnh sửa thiết bị                         |
| POST   | /equipments/update       | Cập nhật thiết bị                               |
| POST   | /equipments/delete       | Xóa thiết bị                                    |
| GET    | /rentals                 | Danh sách phiếu mượn (Search, Pagination, Sort) |
| GET    | /rentals/create          | Form tạo phiếu mượn                             |
| POST   | /rentals/store           | Lưu phiếu mượn                                  |
| GET    | /rentals/edit?id={id}    | Form chỉnh sửa phiếu mượn                       |
| POST   | /rentals/update          | Cập nhật phiếu mượn                             |
| POST   | /rentals/delete          | Xóa phiếu mượn                                  |

### Xử lý lỗi

- URL không tồn tại → **404 Not Found**
- Route đúng nhưng sai HTTP Method → **405 Method Not Allowed**

---

## 3. Minh chứng tối ưu hóa chỉ mục (EXPLAIN Evidence)

Hệ thống đã thiết lập chỉ mục (**INDEX**) cho các trường dữ liệu có tần suất tìm kiếm cao.

Kết quả phân tích bằng lệnh `EXPLAIN` trong MySQL chứng minh các chỉ mục hoạt động hiệu quả, tránh được việc quét toàn bộ bảng (**Full Table Scan**).

### Truy vấn thiết bị

```sql
EXPLAIN
SELECT *
FROM equipments
WHERE status = 'available'
  AND created_at >= '2026-06-01';
```

### Truy vấn phiếu mượn

```sql
EXPLAIN
SELECT *
FROM rental_slips
WHERE borrower_email = 'tri.tran@example.com';
```

Ảnh minh chứng được lưu trong thư mục:

```text
docs/explain_eq.png
docs/explain_rental.png
```

---

## 4. Hướng dẫn kiểm thử nhanh (Test Cases)

| Mã   | Kịch bản                             | Kết quả mong đợi                                           |
| ---- | ------------------------------------ | ---------------------------------------------------------- |
| TC01 | Truy cập GET /health                 | JSON trả về status = ok, database = connected              |
| TC02 | Truy cập GET /equipments             | Hiển thị danh sách thiết bị, có Search, Pagination và Sort |
| TC03 | Thêm thiết bị hợp lệ                 | Redirect về danh sách và lưu thành công                    |
| TC04 | Thêm thiết bị trùng mã               | Hiển thị lỗi Duplicate, không insert                       |
| TC05 | Chỉnh sửa thiết bị                   | Dữ liệu được cập nhật thành công                           |
| TC06 | Xóa thiết bị                         | Chỉ xóa bằng POST và Redirect về danh sách                 |
| TC07 | Truy cập GET /rentals                | Hiển thị danh sách phiếu mượn                              |
| TC08 | Thêm phiếu mượn hợp lệ               | Redirect và lưu dữ liệu thành công                         |
| TC09 | Thêm phiếu có mã trùng               | Hiển thị lỗi Duplicate                                     |
| TC10 | Search theo tên hoặc mã              | Danh sách hiển thị đúng kết quả tìm kiếm                   |
| TC11 | Truy cập ?page=-5                    | Hệ thống tự đưa về trang hợp lệ                            |
| TC12 | Truy cập ?sort=id desc;drop table... | Hệ thống bỏ qua giá trị không hợp lệ và dùng sort mặc định |
| TC13 | Truy cập URL không tồn tại           | Hiển thị trang 404                                         |
| TC14 | Gửi sai HTTP Method                  | Hiển thị trang 405                                         |
| TC15 | Sai cấu hình Database                | Hiển thị trang lỗi 500 an toàn, không lộ SQLSTATE          |
| TC16 | Refresh sau khi Create/Update        | Không tạo dữ liệu trùng (PRG Pattern)                      |

---

## 5. Database & Kỹ thuật áp dụng

Hệ thống sử dụng 3 bảng chính:

- `users`
- `equipments`
- `rental_slips`

Áp dụng đầy đủ:

- Primary Key
- Unique Constraint
- Index
- Foreign Key

### Các kỹ thuật đã áp dụng

- Front Controller & Custom Router
- MVC Structure & Repository Pattern
- PDO Prepared Statements
- Search, Pagination, Safe Sort (Whitelist)
- Validation & Duplicate Record Handling
- PRG Pattern (Post/Redirect/Get)
- Error Handling (404, 405, 500)
- Health Check API

---

## 6. Cấu trúc thư mục

```text
mini-equipment-rental/
│
├── app/
│   ├── Controllers/
│   ├── Core/
│   ├── Repositories/
│   └── Views/
│
├── config/
│   ├── app.php
│   ├── database.example.php
│   └── database.php
│
├── database/
│   ├── schema.sql
│   └── seed.sql
│
├── docs/
│   ├── explain_eq.png
│   └── explain_rental.png
│
├── public/
│   ├── assets/
│   │   └── style.css
│   └── index.php
│
├── storage/
│   └── logs/
│       └── app.log
│
├── .gitignore
├── composer.json
├── composer.lock
└── README.md
```

---

## 7. Tác giả

**PHP Lab05 – Mini Equipment Rental DB App**

Developed by **Tri**
