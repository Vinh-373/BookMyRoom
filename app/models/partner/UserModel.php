<?php
// require_once __DIR__ . '/../core/Model.php';
class UserModel extends Model {
    // Lấy danh sách nhân viên của một khách sạn cụ thể
    public function getStaffByHotel($hotelId) {
        $sql = "SELECT id, fullName, email, phone, role, status, avatarUrl 
                FROM users 
                WHERE hotel_id = :hId AND role IN ('Staff', 'Partner') 
                AND deletedAt IS NULL";
        return $this->db->fetchAll($sql, [':hId' => $hotelId]);
    }

    public function getOneBy($column, $value) {
        $sql = "SELECT * FROM users WHERE $column = :val AND deletedAt IS NULL LIMIT 1";
        return $this->db->fetch($sql, [':val' => $value]);
    }

    // Tạo tài khoản nhân viên mới
    public function createStaff($data) {
        $sql = "INSERT INTO users (fullName, email, password, phone, role, status, hotel_id, created_by, createdAt) 
                VALUES (:name, :email, :pass, :phone, :role, :status, :hId, :cBy, NOW())";
        return $this->db->query($sql, [
            ':name'   => $data['fullName'],
            ':email'  => $data['email'],
            ':pass'   => $data['password'],
            ':phone'  => $data['phone'],
            ':role'   => $data['role'],
            ':status' => 'ACTIVE',
            ':hId'    => $data['hotel_id'],
            ':cBy'    => $data['created_by']
        ]);
    }

    public function softDeleteStaff($id) {
        $sql = "UPDATE users SET deletedAt = NOW(), status = 'BLOCKED' WHERE id = :id";
        return $this->db->query($sql, [':id' => $id]);
    }

    // Cập nhật vai trò (Role)
    public function updateRole($id, $newRole) {
        $sql = "UPDATE users SET role = :role WHERE id = :id";
        return $this->db->query($sql, [':role' => $newRole, ':id' => $id]);
    }

    // Đổi trạng thái trực (Shift Status)
    public function toggleStatus($id, $newStatus) {
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        return $this->db->query($sql, [':status' => $newStatus, ':id' => $id]);
    }

    public function updatePassword($id, $hashedPassword) {
        $sql = "UPDATE users SET password = :pass WHERE id = :id";
        return $this->db->query($sql, [
            ':pass' => $hashedPassword,
            ':id'   => $id
        ]);
    }
}