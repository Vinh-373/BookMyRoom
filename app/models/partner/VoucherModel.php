<?php
// require_once __DIR__ . '/../core/Model.php';

class VoucherModel extends Model {

    /**
     * Lấy danh sách tất cả voucher (có lọc theo tìm kiếm)
     */
    public function getAllByHotelId($filters = [], $hotelId, $limit = 9, $offset = 0) {
        $params = [':hotelId' => $hotelId];
        $sql = "SELECT * FROM vouchers WHERE hotelId = :hotelId";

        if (!empty($filters['search'])) {
            $sql .= " AND code LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
        return $this->db->fetchAll($sql, $params);
    }

    public function countAllByHotelId($filters = [], $hotelId) {
        $params = [':hotelId' => $hotelId];
        $sql = "SELECT COUNT(*) as total FROM vouchers WHERE hotelId = :hotelId";

        if (!empty($filters['search'])) {
            $sql .= " AND code LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy thông tin chi tiết 1 voucher theo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM vouchers WHERE id = :id";
        return $this->db->fetch($sql, [':id' => $id]);
    }

    /**
     * Thêm voucher mới
     */
    public function createVoucher($data) {
        $sql = "INSERT INTO vouchers (code, quantity, type, amount, `condition`, startDate, endDate, hotelId) 
                VALUES (:code, :quantity, :type, :amount, :condition, :startDate, :endDate, :hotelId)";
        
        // Đảm bảo các key trong mảng $data khớp với placeholder :name
        return $this->db->query($sql, [
            ':code'      => $data['code'],
            ':quantity'  => $data['quantity'],
            ':type'      => $data['type'],
            ':amount'    => $data['amount'],
            ':condition' => $data['condition'],
            ':startDate' => $data['startDate'],
            ':endDate'   => $data['endDate'],
            ':hotelId'   => $data['hotelId']
        ]);
    }

    /**
     * Cập nhật thông tin voucher đã tồn tại
     */
    public function updateVoucher($data) {
        $sql = "UPDATE vouchers 
                SET code = :code, 
                    quantity = :quantity, 
                    type = :type, 
                    amount = :amount, 
                    `condition` = :condition, 
                    startDate = :startDate, 
                    endDate = :endDate 
                WHERE id = :id";

        return $this->db->query($sql, [
            ':id'        => $data['id'],
            ':code'      => $data['code'],
            ':quantity'  => $data['quantity'],
            ':type'      => $data['type'],
            ':amount'    => $data['amount'],
            ':condition' => $data['condition'],
            ':startDate' => $data['startDate'],
            ':endDate'   => $data['endDate']
        ]);
    }

    /**
     * Xóa voucher
     */
    public function delete($id) {
        $sql = "DELETE FROM vouchers WHERE id = :id";
        return $this->db->query($sql, [':id' => $id]);
    }

    /**
     * Cập nhật nhanh số lượng voucher (Dùng khi khách áp mã thành công)
     */
    public function decreaseQuantity($id) {
        $sql = "UPDATE vouchers SET quantity = quantity - 1 WHERE id = :id AND quantity > 0";
        return $this->db->query($sql, [':id' => $id]);
    }
}