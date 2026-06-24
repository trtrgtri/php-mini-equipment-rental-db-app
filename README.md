# Mini Equipment Rental DB App

Ứng dụng quản lý **thiết bị** và **phiếu mượn thiết bị** được xây dựng bằng **PHP + MySQL (PDO)** theo mô hình **Front Controller + Repository Pattern**, thực hiện trong **PHP Lab05**.

---

## 1. Tính năng

- CRUD Thiết bị (Equipment)
- CRUD Phiếu mượn (Rental Slip)
- Search
- Pagination
- Safe Sort (Whitelist)
- Validation
- Repository Pattern
- PDO Prepared Statements
- Duplicate Key Handling
- PRG Pattern
- Health Check API
- Error Pages (404, 405, 500)

---

## 2. Công nghệ sử dụng

- PHP 8+
- MySQL / MariaDB
- PDO
- HTML5
- CSS3
- Git

Áp dụng kiến trúc:

```

Browser
↓
public/index.php
↓
Router
↓
Controller
↓
Repository
↓
PDO
↓
MySQL
↓
View / Redirect

```

---

## 3. Cài đặt

### Bước 1. Clone project

```bash
git clone [https://github.com/trtrgtri/php-mini-equipment-rental-db-app.git](https://github.com/trtrgtri/php-mini-equipment-rental-db-app.git)

```

---

### Bước 2. Import database

Import lần lượt các file trong thư mục database:

```
database/schema.sql
database/seed.sql

```

---

### Bước 3. Cấu hình Database

**Lưu ý bảo mật:** File `config/database.php` chứa thông tin kết nối nhạy cảm nên đã được cấu hình loại bỏ trong `.gitignore`. Để chạy ứng dụng, bạn thực hiện tạo file cấu hình từ bản mẫu theo các bước sau:

1. Copy file `config/database.example.php`
2. Đổi tên file vừa copy thành `config/database.php`
3. Chỉnh sửa thông tin kết nối bên trong cho phù hợp với môi trường local của bạn:

```php
return [
    'host' => 'localhost',
    'database' => 'mini_equipment_rental',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];

```

---

### Bước 4. Chạy project

```bash
composer dump-autoload

php -S localhost:8000 -t public

```

Truy cập hệ thống qua đường dẫn:

```
http://localhost:8000

```

---

## 4. Danh sách Route

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

> URL không tồn tại sẽ trả về **404 Not Found**.
> Route đúng định dạng nhưng sai HTTP Method sẽ trả về **405 Method Not Allowed**.

---

## 5. Hướng dẫn kiểm thử nhanh (Test Cases)

| Mã   | Kịch bản                               | Kết quả mong đợi                                           |
| ---- | -------------------------------------- | ---------------------------------------------------------- |
| TC01 | Truy cập **GET /health**               | JSON trả về `status = ok`, `database = connected`          |
| TC02 | Truy cập **GET /equipments**           | Hiển thị danh sách thiết bị, có Search, Pagination và Sort |
| TC03 | Thêm thiết bị hợp lệ                   | Redirect về danh sách và lưu thành công                    |
| TC04 | Thêm thiết bị trùng mã                 | Hiển thị thông báo lỗi Duplicate, không insert             |
| TC05 | Chỉnh sửa thiết bị                     | Dữ liệu được cập nhật thành công                           |
| TC06 | Xóa thiết bị                           | Chỉ xóa bằng POST và Redirect về danh sách                 |
| TC07 | Truy cập **GET /rentals**              | Hiển thị danh sách phiếu mượn                              |
| TC08 | Thêm phiếu mượn hợp lệ                 | Redirect và lưu dữ liệu thành công                         |
| TC09 | Thêm phiếu có mã trùng                 | Hiển thị lỗi Duplicate                                     |
| TC10 | Search theo tên hoặc mã                | Danh sách hiển thị đúng kết quả tìm kiếm                   |
| TC11 | Truy cập `?page=-5`                    | Hệ thống tự đưa về trang hợp lệ                            |
| TC12 | Truy cập `?sort=id desc;drop table...` | Hệ thống bỏ qua giá trị không hợp lệ và dùng sort mặc định |
| TC13 | Truy cập URL không tồn tại             | Hiển thị trang 404                                         |
| TC14 | Gửi sai HTTP Method                    | Hiển thị trang 405                                         |
| TC15 | Sai cấu hình Database                  | Hiển thị trang lỗi 500 an toàn, không lộ SQLSTATE          |
| TC16 | Refresh sau khi Create/Update          | Không tạo dữ liệu trùng (PRG Pattern)                      |

---

## 6. Database Schema

Hệ thống sử dụng **03 bảng chính** với đầy đủ các ràng buộc tối ưu hiệu năng:

- **users**: Lưu thông tin tài khoản hệ thống.
- **equipments**: Quản lý thông tin thiết bị kèm theo các chỉ mục `UNIQUE KEY` cho mã thiết bị và các `INDEX` hỗ trợ tìm kiếm tăng tốc.
- **rental_slips**: Quản lý phiếu mượn, sử dụng ràng buộc khóa ngoại `FOREIGN KEY` liên kết đến bảng thiết bị với cơ chế `ON DELETE RESTRICT` để chống xóa dữ liệu rác.

---

## 7. Các kỹ thuật đã áp dụng

- Front Controller Pattern & Custom Router System
- MVC Architecture Standard
- Repository Pattern (Tách biệt hoàn toàn tầng xử lý dữ liệu và logic điều hướng)
- Toàn bộ câu lệnh dùng PDO Prepared Statements (Chống SQL Injection tuyệt đối)
- Kiểm soát lỗi Duplicate Key tầng Database và xử lý Exception mượt mà ở giao diện
- Áp dụng PRG (Post-Redirect-Get) Pattern triệt tiêu lỗi lặp dữ liệu khi nhấn F5/Refresh
- Thiết lập Error Pages tập trung (404, 405, 500) an toàn bảo mật, không lộ vết hệ thống

---

## 8. Cấu trúc thư mục

```text
mini-equipment-rental/
│
├── app/
│   ├── Controllers/
│   │   ├── EquipmentController.php
│   │   ├── HealthController.php
│   │   ├── HomeController.php
│   │   └── RentalSlipController.php
│   │
│   ├── Core/
│   │   ├── Database.php
│   │   ├── DuplicateRecordException.php
│   │   ├── helpers.php
│   │   └── Router.php
│   │
│   ├── Repositories/
│   │   ├── EquipmentRepository.php
│   │   └── RentalSlipRepository.php
│   │
│   └── Views/
│       ├── equipments/
│       │   ├── create.php
│       │   ├── edit.php
│       │   └── index.php
│       │
│       ├── rental_slips/
│       │   ├── create.php
│       │   ├── edit.php
│       │   └── index.php
│       │
│       ├── errors/
│       │   ├── 404.php
│       │   ├── 405.php
│       │   └── 500.php
│       │
│       ├── dashboard.php
│       └── layout.php
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

## 9. Minh chứng tối ưu hóa chỉ mục (EXPLAIN Evidence)

Hệ thống đã thiết lập chỉ mục (`INDEX`) cho các trường dữ liệu tần suất tìm kiếm cao. Kết quả phân tích câu lệnh bằng phương thức `EXPLAIN` trong MySQL chứng minh các chỉ mục hoạt động hiệu quả, tránh được việc quét toàn bộ bảng dữ liệu (Full Table Scan):

**1. Truy vấn lọc và sắp xếp thiết bị dựa trên trạng thái và ngày khởi tạo:**

```sql
EXPLAIN SELECT * FROM equipments WHERE status = 'available' AND created_at >= '2026-06-01';

```

_Kết quả ghi nhận sử dụng index: `idx_equipments_status_created_at_`

**2. Truy vấn tìm kiếm thông tin phiếu mượn theo email người đăng ký:**

```sql
EXPLAIN SELECT * FROM rental_slips WHERE borrower_email = 'tri.tran@example.com';

```

_Kết quả ghi nhận sử dụng index: `idx_rental_slips_borrower_email_`

---

## 10. Tác giả

**PHP Lab05 – Mini Equipment Rental DB App**

Developed by **Tri**

```

```
