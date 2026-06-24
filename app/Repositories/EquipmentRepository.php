<?php

class EquipmentRepository
{
    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = ''): int
    {
        $sql = "SELECT COUNT(*) AS total FROM equipments";
        $params = [];
        if ($keyword !== '') {
            $sql .= " WHERE equipment_code LIKE :keyword OR name LIKE :keyword OR category LIKE :keyword";
            $params['keyword'] = '%' . $keyword . '%';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $direction): array
    {
        $allowedSorts = ['id', 'equipment_code', 'name', 'category', 'status', 'created_at'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'created_at';
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        $sql = "SELECT id, equipment_code, name, category, status, note, created_at FROM equipments";
        $params = [];
        if ($keyword !== '') {
            $sql .= " WHERE equipment_code LIKE :keyword OR name LIKE :keyword OR category LIKE :keyword";
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

    public function getAllAvailable(): array
    {
        // Lấy danh sách thiết bị đang rảnh để đưa vào dropdown mượn
        $stmt = $this->db->query("SELECT id, equipment_code, name FROM equipments WHERE status = 'available' ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO equipments (equipment_code, name, category, status, note) 
                VALUES (:equipment_code, :name, :category, :status, :note)";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'equipment_code' => $data['equipment_code'],
                'name' => $data['name'],
                'category' => $data['category'] ?: null,
                'status' => $data['status'],
                'note' => $data['note'] ?: null,
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Equipment code already exists.');
            }
            throw $e;
        }
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM equipments WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE equipments 
                SET equipment_code = :equipment_code, name = :name, category = :category, status = :status, note = :note 
                WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'equipment_code' => $data['equipment_code'],
                'name' => $data['name'],
                'category' => $data['category'] ?: null,
                'status' => $data['status'],
                'note' => $data['note'] ?: null,
            ]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                throw new DuplicateRecordException('Equipment code already exists.');
            }
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM equipments WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            // 23000 hoặc 1451 là mã lỗi vi phạm khóa ngoại của MySQL
            if (($e->errorInfo[1] ?? null) === 1451 || $e->getCode() == 23000) {
                throw new Exception("foreign_key_constraint");
            }
            throw $e; // Ném các lỗi khác ra ngoài
        }
    }
}
