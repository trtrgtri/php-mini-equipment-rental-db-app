<?php

class RentalSlipController
{
    private function pdo(): PDO
    {
        $config = require __DIR__ . '/../../config/database.php';
        return (new Database($config))->getConnection();
    }

    private function slipRepo(): RentalSlipRepository
    {
        return new RentalSlipRepository($this->pdo());
    }
    private function equipmentRepo(): EquipmentRepository
    {
        return new EquipmentRepository($this->pdo());
    }

    public function index(): void
    {
        $q = trim($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $sort = $_GET['sort'] ?? 'created_at';
        $direction = $_GET['direction'] ?? 'desc';
        $offset = ($page - 1) * $perPage;

        $repo = $this->slipRepo();
        $total = $repo->countAll($q);
        $totalPages = max(1, (int)ceil($total / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $perPage;
        }

        $slips = $repo->getPaginated($q, $perPage, $offset, $sort, $direction);
        view('rental_slips/index', compact('slips', 'q', 'page', 'perPage', 'total', 'totalPages', 'sort', 'direction'));
    }

    public function create(): void
    {
        $errors = [];
        $old = ['slip_code' => '', 'equipment_id' => '', 'borrower_name' => '', 'borrower_email' => '', 'status' => 'borrowed'];
        $availableEquipments = $this->equipmentRepo()->getAllAvailable();
        view('rental_slips/create', compact('errors', 'old', 'availableEquipments'));
    }

    public function store(): void
    {
        $values = [
            'slip_code' => trim($_POST['slip_code'] ?? ''),
            'equipment_id' => (int)($_POST['equipment_id'] ?? 0),
            'borrower_name' => trim($_POST['borrower_name'] ?? ''),
            'borrower_email' => trim($_POST['borrower_email'] ?? ''),
            'status' => trim($_POST['status'] ?? 'borrowed'),
        ];

        $errors = [];
        if ($values['slip_code'] === '') $errors['slip_code'] = 'Vui lòng nhập mã phiếu.';
        if ($values['equipment_id'] <= 0) $errors['equipment_id'] = 'Vui lòng chọn thiết bị.';
        if ($values['borrower_name'] === '') $errors['borrower_name'] = 'Vui lòng nhập tên người mượn.';

        if (!empty($errors)) {
            $old = $values;
            $availableEquipments = $this->equipmentRepo()->getAllAvailable();
            view('rental_slips/create', compact('errors', 'old', 'availableEquipments'));
            return;
        }

        try {
            $this->slipRepo()->create($values);
            flash_set('success', 'Tạo phiếu mượn thành công.');
            redirect('/rentals');
        } catch (DuplicateRecordException $e) {
            $errors['slip_code'] = 'Mã phiếu này đã tồn tại.';
            $old = $values;
            $availableEquipments = $this->equipmentRepo()->getAllAvailable();
            view('rental_slips/create', compact('errors', 'old', 'availableEquipments'));
        }
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $old = $this->slipRepo()->findById($id);

        if (!$old) {
            http_response_code(404);
            view('errors/404');
            return;
        }

        $errors = [];
        // Lấy thêm thông tin thiết bị để hiển thị cho rõ ràng
        $equipment = $this->equipmentRepo()->findById($old['equipment_id']);
        view('rental_slips/edit', compact('errors', 'old', 'equipment'));
    }

    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $values = [
            'slip_code' => trim($_POST['slip_code'] ?? ''),
            'borrower_name' => trim($_POST['borrower_name'] ?? ''),
            'status' => trim($_POST['status'] ?? '')
        ];

        try {
            $this->slipRepo()->update($id, $values);
            flash_set('success', 'Cập nhật phiếu mượn thành công.');
            redirect('/rentals');
        } catch (Exception $e) {
            error_log('[RentalSlipController::update] ' . $e->getMessage());
            flash_set('error', 'Lỗi hệ thống: Không thể cập nhật.');
            redirect("/rentals/edit?id={$id}");
        }
    }

    public function delete(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $this->slipRepo()->delete($id);
            flash_set('success', 'Xóa phiếu mượn thành công.');
        } catch (Exception $e) {
            error_log('[RentalSlipController::delete] ' . $e->getMessage());
            flash_set('error', 'Lỗi hệ thống: Không thể xóa phiếu mượn.');
        }
        redirect('/rentals');
    }
}
