<?php

class RentalSlipRepository
{
    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM rental_slips rs 
                JOIN equipments e ON rs.equipment_id = e.id";
        $params = [];
        if ($keyword !== '') {
            $sql .= " WHERE rs.slip_code LIKE :keyword OR rs.borrower_name LIKE :keyword OR e.name LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array
    {
        $allowedSorts = ['id', 'slip_code', 'borrower_name', 'status', 'created_at'];
        $sort = in_array($sort, $allowedSorts, true) ? "rs." . $sort : 'rs.created_at';
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        $sql = "SELECT rs.id, rs.slip_code, e.equipment_code, e.name as equipment_name, rs.borrower_name, rs.status, rs.created_at 
                FROM rental_slips rs JOIN equipments e ON rs.equipment_id = e.id";
        $params = [];
        if ($keyword !== '') {
            $sql .= " WHERE rs.slip_code LIKE :keyword OR rs.borrower_name LIKE :keyword OR e.name LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }
        $sql .= " ORDER BY {$sort} {$direction} LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO rental_slips (slip_code, equipment_id, borrower_name, borrower_email, status)
                VALUES (:slip_code, :equipment_id, :borrower_name, :borrower_email, :status)";
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'slip_code' => $data['slip_code'],
                'equipment_id' => $data['equipment_id'],
                'borrower_name' => $data['borrower_name'],
                'borrower_email' => $data['borrower_email'] ?: null,
                'status' => $data['status']
            ]);

            // Tùy chọn: Tự động đổi trạng thái thiết bị thành 'rented'
            if ($result) {
                $updateEq = $this->db->prepare("UPDATE equipments SET status = 'rented' WHERE id = :id");
                $updateEq->execute(['id' => $data['equipment_id']]);
            }
            return $result;
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Slip code already exists.');
            }
            throw $e;
        }
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM rental_slips WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE rental_slips 
                SET borrower_name = :borrower_name, borrower_email = :borrower_email, status = :status 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id' => $id,
            'borrower_name' => $data['borrower_name'],
            'borrower_email' => $data['borrower_email'] ?: null,
            'status' => $data['status']
        ]);

        // Nghiệp vụ: Nếu phiếu chuyển thành 'returned', nhả thiết bị về 'available'
        if ($result && $data['status'] === 'returned') {
            $currentSlip = $this->findById($id);
            if ($currentSlip) {
                $updEq = $this->db->prepare("UPDATE equipments SET status = 'available' WHERE id = :eq_id");
                $updEq->execute(['eq_id' => $currentSlip['equipment_id']]);
            }
        }
        return $result;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM rental_slips WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
