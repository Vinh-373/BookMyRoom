<?php
require_once 'C:\xampp\htdocs\BookMyRoom\app\core\Database.php';
abstract class myModels extends Database
{
    protected $table; // ⚠️ BẮT BUỘC: class con phải khai báo tên bảng

    public function __construct()
    {
        parent::__construct();

        // Kiểm tra nếu chưa set table thì báo lỗi
        if (empty($this->table)) {
            die("Model chưa khai báo table");
        }
    }
    //myModels->select_array("categories", "*", ['status' => 1]);
    function select_array($data = '*', $where = NULL, $order = NULL, $limit = NULL)
    {
        // 1. Kiểm tra đầu vào tối thiểu
        if (empty($this->table)) {
            return [];
        }

        $sql = "SELECT $data FROM $this->table";
        $params = [];
        $types = "";

        // 2. Xử lý điều kiện WHERE (Giữ nguyên logic linh hoạt của bạn)
        if ($where !== NULL && is_array($where)) {
            $conditions = [];
            foreach ($where as $field => $value) {
                if (is_array($value)) {
                    // Xử lý điều kiện IN (ví dụ: id IN (1,2,3))
                    if (!empty($value)) {
                        $placeholders = implode(',', array_fill(0, count($value), '?'));
                        $conditions[] = "$field IN ($placeholders)";
                        foreach ($value as $item) {
                            $params[] = $item;
                            $types .= is_int($item) ? 'i' : 's';
                        }
                    }
                } else {
                    // Xử lý điều kiện = 
                    $conditions[] = "$field = ?";
                    $params[] = $value;
                    $types .= is_int($value) ? 'i' : 's';
                }
            }
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
        }

        // 3. BỔ SUNG: Xử lý ORDER BY (Sắp xếp)
        // Ví dụ truyền vào: "id DESC" hoặc "price ASC, id DESC"
        if ($order !== NULL) {
            $sql .= " ORDER BY " . $order;
        }

        // 4. BỔ SUNG: Xử lý LIMIT (Phân trang hoặc giới hạn số lượng)
        // Ví dụ truyền vào: 10 hoặc "0, 10"
        if ($limit !== NULL) {
            $sql .= " LIMIT " . $limit;
        }

        // 5. Chuẩn bị và thực thi truy vấn
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed: " . $this->conn->error . " | SQL: $sql");
            return [];
        }

        // Bind parameters động
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // 6. Lấy dữ liệu trả về
        $res_data = [];
        while ($row = $result->fetch_assoc()) {
            $res_data[] = $row;
        }

        $stmt->close();
        return $res_data;
    }
    /*
    $model->join_multi(
    joins: [
        [
            'table' => 'customers',
            'type'  => 'LEFT',
            'on'    => 'orders.customer_id = customers.id'
        ],
        [
            'table' => 'districts',
            'type'  => 'LEFT',
            'on'    => 'orders.district_id = districts.id'
        ]
    ],
    select: 'orders.id, customers.name, districts.name as district_name',
    where: [
        'orders.id' => [1,2,3]
    ]
);
    */
    public function join_multi($joins = [], $select = '*', $where = [], $orderBy = null, $limit = null)
    {
        $sql = "SELECT $select FROM $this->table";
        $params = [];
        $types = '';

        // 1. JOIN
        foreach ($joins as $join) {
            $table = $join['table'] ?? '';
            $type = strtoupper($join['type'] ?? 'INNER');
            $on   = $join['on'] ?? '';

            // Validate cơ bản để tránh SQL Injection
            if (!preg_match('/^[a-zA-Z0-9_\.= ]+$/', $on)) {
                die("JOIN condition không hợp lệ");
            }

            $sql .= " $type JOIN $table ON $on";
        }

        // 2. WHERE
        if (!empty($where)) {
            $conditions = [];

            foreach ($where as $field => $value) {
                if (is_array($value)) {
                    // IN (...)
                    $placeholders = implode(',', array_fill(0, count($value), '?'));
                    $conditions[] = "$field IN ($placeholders)";

                    foreach ($value as $v) {
                        $params[] = $v;
                        $types .= is_int($v) ? 'i' : 's';
                    }
                } else {
                    // =
                    $conditions[] = "$field = ?";
                    $params[] = $value;
                    $types .= is_int($value) ? 'i' : 's';
                }
            }

            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // 3. ORDER BY
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }

        // 4. LIMIT
        if ($limit) {
            $sql .= " LIMIT $limit";
        }

        // 5. Prepare & Execute
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("SQL Error: " . $this->conn->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    function insert($table, $data = NULL)
    {
        $fields = array_keys($data);
        $field_list = implode(',', $fields);
        $values = array_values($data);
        $qr = str_repeat('?,', count($values) - 1) . '?';
        $sql = "INSERT INTO $table ($field_list) VALUES ($qr)";

        $stmt = $this->conn->prepare($sql);
        if ($stmt->execute($values)) {
            return json_encode([
                "type" => "success",
                "message" => "isert success",
                "data" => $this->conn->insert_id
            ]);
        } else {
            return  json_encode([
                "type" => "error",
                "message" => "insert failed: " . $this->conn->error
            ]);
        }
    }

    function update($data = NULL, $where = NULL)
    {
        // Kiểm tra đầu vào
        if (empty($this->table) || !is_array($data) || empty($data) || $where === NULL) {
            return json_encode([
                "type" => "error",
                "message" => "Dữ liệu không hợp lệ hoặc thiếu điều kiện WHERE"
            ]);
        }

        // Xây dựng danh sách cột và giá trị cần cập nhật
        $set = [];
        $params = [];
        foreach ($data as $field => $value) {
            $set[] = "$field = ?";
            $params[] = $value;
        }
        $set_clause = implode(", ", $set);

        // Xây dựng điều kiện WHERE
        $where_conditions = [];
        foreach ($where as $field => $value) {
            $where_conditions[] = "$field = ?";
            $params[] = $value;
        }
        $where_clause = implode(" AND ", $where_conditions);

        // Chuẩn bị câu SQL
        $sql = "UPDATE $this->table SET $set_clause WHERE $where_clause";
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            return json_encode([
                "type" => "error",
                "message" => "Prepare failed: " . $this->conn->error
            ]);
        }

        // Bind parameters
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // Giả định tất cả là string, điều chỉnh nếu cần
            $bind_params = array_merge([$types], $params);
            $ref_params = [];
            foreach ($params as $key => $value) {
                $ref_params[$key] = &$params[$key];
            }
            call_user_func_array([$stmt, 'bind_param'], array_merge([$types], $ref_params));
        }

        // Thực thi và trả kết quả
        if ($stmt->execute()) {
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            if ($affected_rows === 0) {
                return json_encode([
                    "type" => "warning",
                    "message" => "No rows updated"
                ]);
            }
            return json_encode([
                "type" => "success",
                "message" => "Update success",
                "data" => $affected_rows
            ]);
        } else {
            $stmt->close();
            return json_encode([
                "type" => "error",
                "message" => "Update failed: " . $stmt->error
            ]);
        }
    }
}
