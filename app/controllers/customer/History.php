<?php

namespace Controllers\customer;

use Controller;
use Middleware\AuthMiddleware;
use Services\BookingService;

require_once "./app/middleware/AuthMiddleware.php";
require_once "./app/services/bookingService.php";
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
        $user = $_SESSION['user'] ?? null;
        // echo "session user: " . json_encode($_SESSION); // Debug session user
        if (!$user) {
            header("Location: " . BASE_URL . "auth/login");
            exit();
        }
        $history = $this->service->getHistoryByUser($user['id']);
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
    public function cancelBooking($bookingId)
    {
        header('Content-Type: application/json');

        try {
            $bookingService = new BookingService();

            $result = $bookingService->updateBooking($bookingId, [
                'status' => 'CANCELLED'
            ]);

            echo json_encode(['success' => $result]);
        } catch (\Throwable $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
