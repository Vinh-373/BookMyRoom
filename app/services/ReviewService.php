<?php
require_once __DIR__ . '/../core/Service.php';
class ReviewService extends Service {
    public function getReviewPageData($hotelId, $filters) {
        $reviewModel = $this->model('ReviewModel');
        $data = $reviewModel->getAverageRating($hotelId);
        $data['reviews'] = $reviewModel->getReviewsByHotel($hotelId, $filters);
        $data['breakdown'] = $this->getRatingBreakdown($hotelId);
        return $data;
    }

    public function getRatingBreakdown($hotelId) {
        $reviewModel = $this->model('ReviewModel');
        $reviews = $reviewModel->getReviewsByHotel($hotelId, 'all');
        $total = count($reviews);
        
        $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($reviews as $r) {
            $rating = (int)$r['rating'];
            if (isset($counts[$rating])) $counts[$rating]++;
        }

        $breakdown = [];
        foreach ($counts as $star => $count) {
            $percentage = ($total > 0) ? round(($count / $total) * 100) : 0;
            $breakdown[] = [
                'label' => $star . ' ★',
                'pc' => $percentage,
                'color' => $this->getStarColor($star)
            ];
        }
        return $breakdown; // Để 5 sao lên đầu
    }

    private function getStarColor($star) {
        if ($star >= 4) return '#12B76A'; // Xanh lá
        if ($star == 3) return '#FDB022'; // Vàng
        return '#F04438'; // Đỏ
    }

    public function updateReviewReply($replyId, $reply){
        return $this->model('ReviewModel')->updateReply($replyId, $reply);
    }
}