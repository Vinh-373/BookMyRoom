<?php
require_once __DIR__ . '/../core/Model.php';
class ReviewModel extends Model {
    public function getReviewsByHotel($hotelId, $filters = []) {
        $sql = "SELECT r.*, u.fullName, u.avatarUrl, rt.name as roomTypeName, bd.checkIn 
                FROM reviews r
                JOIN users u ON r.userId = u.id
                JOIN bookingdetails bd ON r.bookingDetailId = bd.id
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                JOIN roomtypes rt ON rc.roomTypeId = rt.id
                WHERE rc.hotelId = :hId";
        
        // Thêm filter theo số sao nếu có
        if (isset($filters['rating']) && $filters['rating'] == 'positive') $sql .= " AND r.rating >= 4";
        if (isset($filters['rating']) && $filters['rating'] == 'negative') $sql .= " AND r.rating <= 2";
        
        $sql .= " ORDER BY r.createdAt DESC";
        return $this->db->fetchAll($sql, [':hId' => $hotelId]);
    }

    public function getAverageRating($hotelId) {
        $sql = "SELECT AVG(r.rating) as avgRating, COUNT(r.id) as totalReviews 
                FROM reviews r
                JOIN bookingdetails bd ON r.bookingDetailId = bd.id
                JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                WHERE rc.hotelId = :hId";
        return $this->db->fetch($sql, [':hId' => $hotelId]);
    }

    public function updateReply($reviewId, $replyText) {
        $sql = "UPDATE reviews 
                SET replyContent = :reply, 
                    replyDate = NOW() 
                WHERE id = :id";
                
        return $this->db->query($sql, [
            ':reply' => $replyText,
            ':id'    => $reviewId
        ]);
    }
}