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
git clone https://github.com/trtrgtri/php-mini-equipment-rental-db-app.git
```

---

### Bước 2. Import database

Import lần lượt:

```
database/schema.sql
database/seed.sql
```

---

### Bước 3. Cấu hình Database

Mở file:

```
config/database.php
```

Chỉnh thông tin kết nối:

```php
return [
    'host' => 'localhost',
    'database' => 'equipment_rental_db',
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

Truy cập:

```
http://localhost:8000
```

---

# 4. Danh sách Route

| Method | URL                        | Chức năng                                       |
| ------ | -------------------------- | ----------------------------------------------- |
| GET    | /                          | Dashboard                                       |
| GET    | /health                    | Kiểm tra trạng thái kết nối Database            |
| GET    | /equipment                 | Danh sách thiết bị (Search, Pagination, Sort)   |
| GET    | /equipment/create          | Form thêm thiết bị                              |
| POST   | /equipment/store           | Lưu thiết bị mới                                |
| GET    | /equipment/edit?id={id}    | Form chỉnh sửa thiết bị                         |
| POST   | /equipment/update          | Cập nhật thiết bị                               |
| POST   | /equipment/delete          | Xóa thiết bị                                    |
| GET    | /rental-slips              | Danh sách phiếu mượn (Search, Pagination, Sort) |
| GET    | /rental-slips/create       | Form tạo phiếu mượn                             |
| POST   | /rental-slips/store        | Lưu phiếu mượn                                  |
| GET    | /rental-slips/edit?id={id} | Form chỉnh sửa phiếu mượn                       |
| POST   | /rental-slips/update       | Cập nhật phiếu mượn                             |
| POST   | /rental-slips/delete       | Xóa phiếu mượn                                  |

> URL không tồn tại sẽ trả về **404 Not Found**.
> Route đúng nhưng sai HTTP Method sẽ trả về **405 Method Not Allowed**.

---

# 5. Hướng dẫn kiểm thử nhanh (Test Cases)

| Mã   | Kịch bản                               | Kết quả mong đợi                                           |
| ---- | -------------------------------------- | ---------------------------------------------------------- |
| TC01 | Truy cập **GET /health**               | JSON trả về `status = ok`, `database = connected`          |
| TC02 | Truy cập **GET /equipment**            | Hiển thị danh sách thiết bị, có Search, Pagination và Sort |
| TC03 | Thêm thiết bị hợp lệ                   | Redirect về danh sách và lưu thành công                    |
| TC04 | Thêm thiết bị trùng mã                 | Hiển thị thông báo lỗi Duplicate, không insert             |
| TC05 | Chỉnh sửa thiết bị                     | Dữ liệu được cập nhật thành công                           |
| TC06 | Xóa thiết bị                           | Chỉ xóa bằng POST và Redirect về danh sách                 |
| TC07 | Truy cập **GET /rental-slips**         | Hiển thị danh sách phiếu mượn                              |
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

# 6. Database

Hệ thống sử dụng **03 bảng chính**

- users
- equipment
- rental_slips

Áp dụng:

- Primary Key
- Unique Constraint
- Index
- Foreign Key (nếu có)

---

# 7. Các kỹ thuật đã áp dụng

- Front Controller
- Router
- MVC Structure
- Repository Pattern
- PDO Prepared Statements
- Search
- Pagination
- Safe Sort (Whitelist)
- Validation
- Duplicate Record Handling
- PRG Pattern
- Error Handling (404, 405, 500)
- Health Check API

---

# 8. Thư mục chính

````
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
````

```

---

# 9. Tác giả

**PHP Lab05 – Mini Equipment Rental DB App**

Developed by **Tri**
```
