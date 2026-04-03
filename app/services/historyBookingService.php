<?php
require_once __DIR__ . '/../models/histotyBookingModel.php';
use Models\HistoryBookingModel;

class HistoryBookingService
{
    private $model;
    public function __construct()
    {
        $this->model = new HistoryBookingModel();
    }

    public function getHistoryByUser($userId)
    {
        return $this->model->getHistoryByUser($userId);
    }

    public function getImageHotel($hotelId)
    {
        return $this->model->getImageHotel($hotelId);
    }
    public function getHotelName($hotelId)
    {
        return $this->model->getHotelName($hotelId);
    }

    public function getBookingDetails($bookingId)
    {
        return $this->model->getBookingDetails($bookingId);
    }

    public function setReview($userId, $bookingDetailId, $rating, $content, $hotelId)
    {
        return $this->model->setReview($userId, $bookingDetailId, $rating, $content, $hotelId);
    }
}
?>