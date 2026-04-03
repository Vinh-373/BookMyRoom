<?php
namespace Models;
require_once './app/models/MyModels.php';

class PaymentModel extends MyModels
{
     protected $table = "payments";

       function delete($bookingId) {
        $sql = "DELETE FROM payments WHERE bookingId = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception("Prepare lỗi: " . $this->conn->error);
        }
        $stmt->bind_param("i", $bookingId);
        if (!$stmt->execute()) {
            throw new \Exception("Delete payment lỗi: " . $stmt->error);
        }
        return true;
       }

}