<?php

class EquipmentController
{
    private function repository(): EquipmentRepository
    {
        $config = require __DIR__ . '/../../config/database.php';
        $pdo = (new Database($config))->getConnection();
        return new EquipmentRepository($pdo);
    }

    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $sort = $_GET['sort'] ?? 'created_at';
        $direction = $_GET['direction'] ?? 'desc';
        $offset = ($page - 1) * $perPage;

        $repo = $this->repository();
        $total = $repo->countAll($q);
        $totalPages = max(1, (int)ceil($total / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $perPage;
        }

        $equipments = $repo->getPaginated($q, $perPage, $offset, $sort, $direction);
        view('equipments/index', compact('equipments', 'q', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }

    public function create(): void
    {
        $errors = [];
        $old = ['equipment_code' => '', 'name' => '', 'category' => '', 'status' => 'available', 'note' => ''];
        view('equipments/create', compact('errors', 'old'));
    }

    public function store(): void
    {
        $values = [
            'equipment_code' => trim($_POST['equipment_code'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'status' => trim($_POST['status'] ?? 'available'),
            'note' => trim($_POST['note'] ?? ''),
        ];

        $errors = [];
        if ($values['equipment_code'] === '') $errors['equipment_code'] = 'Vui lòng nhập mã thiết bị.';
        if ($values['name'] === '') $errors['name'] = 'Vui lòng nhập tên thiết bị.';

        if (!empty($errors)) {
            $old = $values;
            view('equipments/create', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->create($values);
            flash_set('success', 'Đã thêm thiết bị thành công.');
            redirect('/equipments');
        } catch (DuplicateRecordException $e) {
            $errors['equipment_code'] = 'Mã thiết bị này đã tồn tại.';
            $old = $values;
            view('equipments/create', compact('errors', 'old'));
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $repo = $this->repository();
        $old = $repo->findById($id);

        if (!$old) {
            http_response_code(404);
            view('errors/404');
            return;
        }

        $errors = [];
        view('equipments/edit', compact('errors', 'old')); // Bạn cần tự tạo file view equipments/edit.php tương tự form create
    }

    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $values = [
            'equipment_code' => trim($_POST['equipment_code'] ?? ''),
            'name' => trim($_POST['name'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'status' => trim($_POST['status'] ?? 'available'),
            'note' => trim($_POST['note'] ?? ''),
        ];

        $errors = [];
        if ($values['equipment_code'] === '') $errors['equipment_code'] = 'Vui lòng nhập mã thiết bị.';
        if ($values['name'] === '') $errors['name'] = 'Vui lòng nhập tên thiết bị.';

        if (!empty($errors)) {
            $old = array_merge($values, ['id' => $id]);
            view('equipments/edit', compact('errors', 'old'));
            return;
        }

        try {
            $this->repository()->update($id, $values);
            flash_set('success', 'Cập nhật thiết bị thành công.');
            redirect('/equipments');
        } catch (DuplicateRecordException $e) {
            $errors['equipment_code'] = 'Mã thiết bị này đã tồn tại.';
            $old = array_merge($values, ['id' => $id]);
            view('equipments/edit', compact('errors', 'old'));
        }
    }

    public function delete(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->repository()->delete($id);
            flash_set('success', 'Xóa thiết bị thành công.');
        } catch (Exception $e) {
            if ($e->getMessage() === 'foreign_key_constraint') {
                flash_set('error', 'Không thể xóa: Thiết bị này đang được sử dụng trong các Phiếu mượn.');
            } else {
                flash_set('error', 'Lỗi hệ thống: Không thể thực hiện thao tác xóa.');
            }
        }
        redirect('/equipments');
    }
}
