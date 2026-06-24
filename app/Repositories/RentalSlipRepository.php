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
            $sql .= " WHERE rs.slip_code LIKE :kw_slip OR rs.borrower_name LIKE :kw_borrower OR e.name LIKE :kw_eq_name";
            $searchTerm = '%' . $keyword . '%';
            $params['kw_slip'] = $searchTerm;
            $params['kw_borrower'] = $searchTerm;
            $params['kw_eq_name'] = $searchTerm;
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
            $sql .= " WHERE rs.slip_code LIKE :kw_slip OR rs.borrower_name LIKE :kw_borrower OR e.name LIKE :kw_eq_name";
            $searchTerm = '%' . $keyword . '%';
            $params['kw_slip'] = $searchTerm;
            $params['kw_borrower'] = $searchTerm;
            $params['kw_eq_name'] = $searchTerm;
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
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO rental_slips (slip_code, equipment_id, borrower_name, borrower_email, status) 
                    VALUES (:slip_code, :equipment_id, :borrower_name, :borrower_email, :status)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'slip_code' => $data['slip_code'],
                'equipment_id' => $data['equipment_id'],
                'borrower_name' => $data['borrower_name'],
                'borrower_email' => $data['borrower_email'] ?: null,
                'status' => $data['status']
            ]);

            $updateStmt = $this->db->prepare("UPDATE equipments SET status = 'rented' WHERE id = :id");
            $updateStmt->execute(['id' => $data['equipment_id']]);

            $this->db->commit();
            return true;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Mã phiếu mượn đã tồn tại.');
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
                SET slip_code = :slip_code, borrower_name = :borrower_name, 
                    borrower_email = :borrower_email, status = :status, updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id'             => $id,
            'slip_code'      => $data['slip_code'],
            'borrower_name'  => $data['borrower_name'],
            'borrower_email' => $data['borrower_email'] ?: null,
            'status'         => $data['status']
        ]);

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
