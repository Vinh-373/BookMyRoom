<?php
// Load Database class (class myModels ở global namespace để các model *Model.php require được)
require_once __DIR__ . '/../core/Database.php';

class myModels {
    
    protected $table;
    protected $conn;

    /**
     * Constructor - Initialize database connection
     */
    public function __construct() {
        $db = new \Database();
        $this->conn = $db->conn;
    }

    /**
     * Lấy mảng dữ liệu đơn giản
     * Ví dụ: select_array('*', ['status' => 'active'])
     */
    public function select_array(string $select = '*', array $where = [], ?string $orderBy = null, ?int $limit = null): array {
        try {
            $sql = "SELECT {$select} FROM {$this->table}";
            
            // Xây dựng WHERE clause
            if (!empty($where)) {
                $conditions = [];
                foreach ($where as $column => $value) {
                    if ($value === null) {
                        $conditions[] = "{$column} IS NULL";
                    } else {
                        $conditions[] = "{$column} = ?";
                    }
                }
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
            
            // ORDER BY
            if ($orderBy) {
                $sql .= " ORDER BY {$orderBy}";
            }
            
            // LIMIT
            if ($limit) {
                $sql .= " LIMIT {$limit}";
            }
            
            // Prepare statement
            $stmt = $this->conn->prepare($sql);
            
            // Bind params
            if (!empty($where)) {
                $params = [];
                $types = '';
                foreach ($where as $column => $value) {
                    if ($value !== null) {
                        $params[] = $value;
                        $types .= is_int($value) ? 'i' : 's';
                    }
                }
                
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
            
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Lấy tất cả bản ghi (có thể truyền điều kiện / order / limit như select_array)
     */
    public function findAll(array $where = [], ?string $orderBy = null, ?int $limit = null): array {
        return $this->select_array('*', $where, $orderBy, $limit);
    }

    /**
     * Join nhiều bảng
     * @param string|int|null $limit SQL LIMIT: số hoặc "offset, count"
     */
    public function join_multi(array $joins = [], string $select = '*', array $where = [], ?string $orderBy = null, $limit = null): array {
        try {
            $sql = "SELECT {$select} FROM {$this->table}";
            
            if (!empty($joins)) {
                foreach ($joins as $join) {
                    $type = $join['type'] ?? 'LEFT';
                    $table = $join['table'] ?? '';
                    $on = $join['on'] ?? '';
                    
                    if ($table && $on) {
                        $sql .= " {$type} JOIN {$table} ON {$on}";
                    }
                }
            }
            
            if (!empty($where)) {
                $conditions = [];
                $values = [];
                $types = '';
                
                foreach ($where as $column => $value) {
                    if ($value === null) {
                        $conditions[] = "{$column} IS NULL";
                    } else {
                        $conditions[] = "{$column} = ?";
                        $values[] = $value;
                        $types .= is_int($value) ? 'i' : 's';
                    }
                }
                
                if (!empty($conditions)) {
                    $sql .= " WHERE " . implode(" AND ", $conditions);
                }
            }
            
            if ($orderBy) {
                $sql .= " ORDER BY {$orderBy}";
            }
            
            if ($limit !== null && $limit !== '') {
                $sql .= ' LIMIT ' . (is_int($limit) ? (string) $limit : $limit);
            }
            
            $stmt = $this->conn->prepare($sql);
            
            if (!empty($values)) {
                $stmt->bind_param($types, ...$values);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
            
        } catch (\Exception $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Insert dữ liệu
     */
    public function insert(array $data = []): array {
        try {
            if (empty($data) || !is_array($data)) {
                return ['success' => false, 'error' => 'No data provided'];
            }
            
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $values = array_values($data);
            
            $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
            
            $stmt = $this->conn->prepare($sql);
            
            $types = '';
            foreach ($values as $value) {
                $types .= is_int($value) ? 'i' : 's';
            }
            
            $stmt->bind_param($types, ...$values);
            $stmt->execute();
            
            return ['success' => true, 'id' => $this->conn->insert_id, 'affectedRows' => $stmt->affected_rows];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Update dữ liệu
     */
    public function update(array $data = [], array $where = []): array {
        try {
            if (empty($data) || !is_array($data) || empty($where) || !is_array($where)) {
                return ['success' => false, 'error' => 'Missing data or conditions'];
            }
            
            $sets = [];
            $values = [];
            $types = '';
            
            foreach ($data as $column => $value) {
                $sets[] = "{$column} = ?";
                $values[] = $value;
                $types .= is_int($value) ? 'i' : 's';
            }
            
            $conditions = [];
            foreach ($where as $column => $value) {
                if ($value === null) {
                    $conditions[] = "{$column} IS NULL";
                } else {
                    $conditions[] = "{$column} = ?";
                    $values[] = $value;
                    $types .= is_int($value) ? 'i' : 's';
                }
            }
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $sets);
            $sql .= " WHERE " . implode(" AND ", $conditions);
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($types, ...$values);
            $stmt->execute();
            
            return ['success' => true, 'affectedRows' => $stmt->affected_rows];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Delete dữ liệu
     */
    public function delete(array $where = []): array {
        try {
            if (empty($where) || !is_array($where)) {
                return ['success' => false, 'error' => 'Missing conditions'];
            }
            
            $conditions = [];
            $values = [];
            $types = '';
            
            foreach ($where as $column => $value) {
                if ($value === null) {
                    $conditions[] = "{$column} IS NULL";
                } else {
                    $conditions[] = "{$column} = ?";
                    $values[] = $value;
                    $types .= is_int($value) ? 'i' : 's';
                }
            }
            
            $sql = "DELETE FROM {$this->table} WHERE " . implode(" AND ", $conditions);
            
            $stmt = $this->conn->prepare($sql);
            if (!empty($values)) {
                $stmt->bind_param($types, ...$values);
            }
            $stmt->execute();
            
            return ['success' => true, 'affectedRows' => $stmt->affected_rows];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Lấy kết nối database
     */
    public function getConnection(): \mysqli {
        return $this->conn;
    }

    /**
     * Search hotels
     */
    public function searchHotels(string $pattern): array {
        try {
            $sql = "SELECT hotels.id, hotels.hotelName, hotels.rating, hotels.address,
                           cities.name as cityName, users.email as partnerEmail
                    FROM hotels
                    LEFT JOIN cities ON hotels.cityId = cities.id
                    LEFT JOIN users ON hotels.partnerId = users.id
                    WHERE (hotels.hotelName LIKE ? OR hotels.address LIKE ?)
                    AND hotels.deletedAt IS NULL
                    ORDER BY hotels.hotelName
                    LIMIT 20";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $pattern, $pattern);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
            
        } catch (\Exception $e) {
            return [];
        }
    }
}
