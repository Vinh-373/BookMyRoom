<?php
namespace Controllers\customer;
use Controller;

require_once __DIR__ . '/../../services/historyBookingService.php';
class History extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new \HistoryBookingService();
    }

    public function index()
    {
        $history = $this->service->getHistoryByUser(11); // Thay 11 bằng userId thực tế từ session hoặc tham số
        $viewFile = './app/views/customer/historyPage.php';
        $this->view('layout/customer/client', [
            'viewFile' => $viewFile,
            'history' => $history
        ]);
    }

    public function getBookingDetails($bookingId)
    {
        $details = $this->service->getBookingDetails($bookingId);
        echo json_encode($details, JSON_UNESCAPED_UNICODE);
    }

    public function setReview()
    {

        $userId = $_POST['userId'];
        $bookingDetailId = $_POST['bookingDetailId'];
        $rating = $_POST['rating'];
        $content = $_POST['content'];
        $hotelId = $_POST['hotelId'];



        $result = $this->service->setReview($userId, $bookingDetailId, $rating, $content, $hotelId);
        echo json_encode(['success' => $result]);
    }

}