<?php
namespace Services;
use Models\ReviewModel;
require_once "./app/models/reviewModel.php";
class ReviewService {
    private $reviewModel;

    public function __construct() {
        $this->reviewModel = new ReviewModel();
    }
    function getReviewsByHotelId($hotelId){
        return $this->reviewModel->getReviewsByHotelId($hotelId);
    }

}