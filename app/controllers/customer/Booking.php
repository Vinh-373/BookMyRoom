<?php

namespace Controllers\customer;

use Controller;
use Services\BedService;
use Services\HotelsImagesService;
use Services\HotelService;
use Services\RoomImageService;
use Services\RoomConfigService;
use Services\ReviewService;
use Middleware\AuthMiddleware;
use Exception;
use DateTime;
use Services\BookingService;
use Services\PaymentService;
use Services\VoucherService;

require_once "./app/services/hotelService.php";
require_once "./app/services/hotelImagesService.php";
require_once "./app/services/roomConfigService.php";
require_once "./app/services/roomImageService.php";
require_once "./app/services/bedService.php";
require_once "./app/services/reviewService.php";
require_once "./app/middleware/AuthMiddleware.php";
require_once "./app/services/BookingService.php";
require_once "./app/services/paymentService.php";
require_once "./app/services/voucherService.php";



class Booking extends Controller
{
    private $conn;
    private $hotelService;

    public function __construct()
    {
        $db = new \Database();
        $this->conn = $db->conn; // 🔥 QUAN TRỌNG

        $this->hotelService = new HotelService();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $hotels = $this->hotelService->getAllHotels(null, 10, 0, 'h.rating', 'DESC', null);

        $this->view('layout/customer/client', [
            'viewFile' => './app/views/customer/booking/homePage.php',
            'hotels' => $hotels
        ]);
    }

    public function hotel($hotelId)
    {
        // ✅ Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $dates = $_GET['dates'] ?? null;

        $imageService      = new HotelsImagesService();
        $roomConfigService = new RoomConfigService();
        $roomImageService  = new RoomImageService();
        $bedService        = new BedService();
        $reviewService     = new ReviewService();

        // 1. Hotel info
        $hotelData = $this->hotelService->getHotelById($hotelId);
        $images    = $imageService->getAllImagesByHotelId($hotelId);
        $reviews   = $reviewService->getReviewsByHotelId($hotelId);

        // 2. Rooms từ DB
        $roomConfigs = $roomConfigService->getRoomConfigsAvailableByHotel($hotelId, $dates);

        $roomData = [];

        foreach ($roomConfigs as $room) {

            $roomImages = $roomImageService->getImageByRoomConfigId($room['roomConfigId']);
            $bed        = $bedService->getBedByConfigId($room['roomConfigId']);

            $roomData[] = [
                'room'   => $room,
                'images' => $roomImages,
                'bed'    => $bed
            ];
        }

        // ================== XỬ LÝ NGÀY ==================
        $checkIn = null;
        $checkOut = null;

        if (!empty($dates)) {
            $parts = explode(' to ', $dates);

            if (count($parts) === 2) {
                $checkInObj  = DateTime::createFromFormat('d/m/Y', trim($parts[0]));
                $checkOutObj = DateTime::createFromFormat('d/m/Y', trim($parts[1]));

                if ($checkInObj && $checkOutObj) {
                    $checkIn  = $checkInObj->format('Y-m-d');
                    $checkOut = $checkOutObj->format('Y-m-d');
                }
            }
        }

        // ================== TRỪ PHÒNG TRONG GIỎ ==================
        $updatedRooms = [];

        foreach ($roomData as $roomItem) {

            $roomConfigId = $roomItem['room']['roomConfigId'];
            $available    = (int)$roomItem['room']['availableRooms'];

            $bookedQuantity = 0;

            if (!empty($_SESSION['booking'])) {

                foreach ($_SESSION['booking'] as $cartItem) {

                    if ($cartItem['roomConfigId'] != $roomConfigId) continue;

                    $cartCheckIn  = $cartItem['checkIn'];
                    $cartCheckOut = $cartItem['checkOut'];
                    $quantity     = (int)$cartItem['quantity'];

                    // ✅ convert DateTime
                    $cartIn  = new DateTime($cartCheckIn);
                    $cartOut = new DateTime($cartCheckOut);

                    $reqIn  = new DateTime($checkIn);
                    $reqOut = new DateTime($checkOut);

                    // ✅ check overlap chuẩn
                    if ($reqIn < $cartOut && $reqOut > $cartIn) {
                        $bookedQuantity += $quantity;
                    }
                }
            }

            // ✅ phòng còn lại
            $finalAvailable = $available - $bookedQuantity;

            if ($finalAvailable > 0) {
                $roomItem['room']['availableRoomss'] = $finalAvailable;
                $updatedRooms[] = $roomItem;
            }
        }

        // Gán lại danh sách phòng
        $roomData = $updatedRooms;

        // 3. Render view
        $this->view('layout/customer/client', [
            'viewFile' => './app/views/customer/booking/hotelPage.php',
            'hotelData' => $hotelData[0] ?? null,
            'images'    => $images,
            'rooms'     => $roomData,
            'reviews'   => $reviews,
            'filters'   => [
                'dates' => $dates
            ]
        ]);
    }

    public function confirm()
    {

        // ❌ nếu không có booking
        if (empty($_SESSION['booking'])) {
            header("Location: " . BASE_URL);
            exit;
        }

        // ✅ 2. Khởi tạo service 1 lần
        $roomConfigService = new RoomConfigService();
        $roomImageService  = new RoomImageService();
        $bedService        = new BedService();

        $bookingData = [];

        // ✅ 3. Build data
        foreach ($_SESSION['booking'] as $item) {

            $roomConfig = $roomConfigService->getById($item['roomConfigId']);
            $images     = $roomImageService->getImageByRoomConfigId($item['roomConfigId']);
            $bed        = $bedService->getBedByConfigId($item['roomConfigId']);

            // Thay vì dùng createFromFormat với định dạng sai
            // Hãy dùng trực tiếp 'new DateTime()' vì nó tự hiểu định dạng 'Y-m-d'
            $checkInDate  = new DateTime($item['checkIn']);
            $checkOutDate = new DateTime($item['checkOut']);

            // Tính số đêm (diff)
            $interval = $checkInDate->diff($checkOutDate);
            $nights   = $interval->days;

            // Đảm bảo số đêm ít nhất là 1 để tránh lỗi tính tiền bằng 0
            $nights = ($nights > 0) ? $nights : 1;

            // ✅ tính tiền
            $price = $roomConfig['basePrice'] ?? 0;
            $total = $price * $item['quantity'] * $nights;
            $availableRooms = $item['availableRooms'] ?? 0;
            $physicalRoomIds = $item['physicalRoomIds'] ?? [];



            $bookingData[] = array_merge($item, [
                'roomConfig' => $roomConfig,
                'images'     => $images,
                'bed'        => $bed,
                'nights'     => $nights,
                'total'      => $total,
                'availableRooms' => $availableRooms,
                'physicalRoomIds' => $physicalRoomIds

            ]);
        }

        // ✅ 4. Tổng tiền
        $totalAll = array_sum(array_column($bookingData, 'total'));
        $voucherService = new VoucherService();
        $vouchers = $voucherService->getAllVouchers();

        // ✅ 5. Render view
        $this->view('layout/customer/client', [
            'viewFile' => './app/views/customer/booking/confirmPage.php',
            'bookingData' => $bookingData,
            'totalAll' => $totalAll,
            'vouchers' => $vouchers
        ]);
    }
    public function create()
    {

        header('Content-Type: application/json');

        try {
            $auth = new AuthMiddleware();
            $user = $auth->check();

            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Không có dữ liệu gửi lên']);
                return;
            }

            // Validate
            if (empty($data['roomConfigId']) || empty($data['checkIn']) || empty($data['checkOut']) || !isset($data['quantity'])) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu']);
                return;
            }

            $roomConfigId = (int)$data['roomConfigId'];
            $quantity     = (int)$data['quantity'];           // Số lượng mới (quan trọng)
            $availableRooms = (int)($data['availableRooms'] ?? 0);
            $action       = $data['action'] ?? 'add';
            $checkInRaw   = $data['checkIn'];
            $checkOutRaw  = $data['checkOut'];
            $physicalRoomIds = $data['availablePhysicalRoomIds'] ?? []; // Danh sách physical room IDs có sẵn (nếu có)

            // Convert date
            if (strpos($checkInRaw, '/') !== false) {
                $checkInDate = DateTime::createFromFormat('d/m/Y', $checkInRaw);
                $checkOutDate = DateTime::createFromFormat('d/m/Y', $checkOutRaw);
            } else {
                $checkInDate = new DateTime($checkInRaw);
                $checkOutDate = new DateTime($checkOutRaw);
            }

            if (!$checkInDate || !$checkOutDate || $checkOutDate <= $checkInDate) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Ngày không hợp lệ']);
                return;
            }

            $checkIn = $checkInDate->format('Y-m-d');
            $checkOut = $checkOutDate->format('Y-m-d');

            if (!isset($_SESSION['booking'])) {
                $_SESSION['booking'] = [];
            }

            // Tìm item tồn tại
            $foundIndex = -1;
            foreach ($_SESSION['booking'] as $index => $item) {
                if (
                    $item['roomConfigId'] == $roomConfigId &&
                    $item['checkIn'] == $checkIn &&
                    $item['checkOut'] == $checkOut
                ) {
                    $foundIndex = $index;
                    break;
                }
            }

            // ==================== XỬ LÝ LOGIC ====================
            if ($action === 'add') {
                if ($quantity < 1) $quantity = 1;

                if ($foundIndex !== -1) {
                    $_SESSION['booking'][$foundIndex]['quantity'] = $quantity;  // Gán trực tiếp số lượng mới
                    if ($_SESSION['booking'][$foundIndex]['quantity'] > $availableRooms) {
                        $_SESSION['booking'][$foundIndex]['quantity'] = $availableRooms;
                    }
                } else {
                    $_SESSION['booking'][] = [
                        'roomConfigId' => $roomConfigId,
                        'checkIn'      => $checkIn,
                        'checkOut'     => $checkOut,
                        'quantity'     => min($quantity, $availableRooms),
                        'availableRooms' => $availableRooms,
                        'createdAt'    => date('Y-m-d H:i:s'),
                        'physicalRoomIds' => $physicalRoomIds
                    ];
                }
            } elseif ($action === 'minus') {
                if ($foundIndex !== -1) {
                    $_SESSION['booking'][$foundIndex]['quantity'] = $quantity;   // Gán số lượng mới

                    if ($_SESSION['booking'][$foundIndex]['quantity'] <= 0) {
                        array_splice($_SESSION['booking'], $foundIndex, 1);     // Xóa nếu <= 0
                    }
                }
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Cập nhật booking thành công',
                'data' => $_SESSION['booking']
            ]);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    

    // ================= API =================

    public function getImageByRoomConfigId($roomConfigId)
    {
        $roomImageService = new RoomImageService();
        $images = $roomImageService->getImageByRoomConfigId($roomConfigId);

        return $this->jsonResponse($images);
    }

    public function getBedTypeByConfigId($roomConfigId)
    {
        $bedService = new BedService();
        $bedData = $bedService->getBedByConfigId($roomConfigId);

        return $this->jsonResponse($bedData);
    }
    public function checkout()
    {
        $bookingService = new BookingService();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->jsonResponse(['status' => 'error'], 405);
        }

        $auth = new AuthMiddleware();
        $user = $auth->check();

        if (!$user || $user['role'] !== 'CUSTOMER') {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ'
            ], 400);
        }

        $bookingData   = $input['bookingData'] ?? [];
        $totalAll      = (float)($input['totalAll'] ?? 0);
        $paymentMethod = strtolower($input['payment'] ?? '');

        if (empty($bookingData)) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Không có phòng'
            ], 400);
        }

        $platformFee   = round($totalAll * 0.1, 2);
        $depositAmount = round(($totalAll * 1.01) * 0.3, 2);
        $partnerRevenue = $totalAll - $platformFee;

        $this->conn->begin_transaction();

        try {

            // ===== 1. BOOKING =====
            $bookingId = $bookingService->createBooking([
                'userId'         => $user['id'],
                'status'         => 'PENDING',
                'totalAmount'    => $totalAll,
                'platformFee'    => $platformFee,
                'partnerRevenue' => $partnerRevenue,
                'deposit'        => $depositAmount,
                'voucherId'     => $voucherCode ?? null
            ]);
            

            if (!$bookingId) {
                throw new Exception('Không tạo được booking');
            }

            // ===== 2. DETAIL =====
            foreach ($bookingData as $item) {

                $roomConfigId = $item['roomConfigId'];
                $checkIn      = $item['checkIn'];
                $checkOut     = $item['checkOut'];
                $quantity     = (int)$item['quantity'];
                $price        = (float)$item['roomConfig']['basePrice'];
                $nights       = (int)$item['nights'];

                $availableRooms = $bookingService->getAvailablePhysicalRooms($roomConfigId);

                if (count($availableRooms) < $quantity) {
                    throw new Exception('Không đủ phòng');
                }

                shuffle($availableRooms);
                $selectedRooms = array_slice($availableRooms, 0, $quantity);

                foreach ($selectedRooms as $roomId) {

                    $amount = $price * $nights;

                    $bookingService->createBookingDetail([
                        'bookingId'      => $bookingId,
                        'roomConfigId'   => $roomConfigId,
                        'physicalRoomId' => $roomId,
                        'checkIn'        => $checkIn,
                        'checkOut'       => $checkOut,
                        'quantity'       => 1,
                        'price'          => $price,
                        'amount'         => $amount
                    ]);
                }

               
            }

            // ===== 3. PAYMENT (chỉ tạo record) =====
            $paymentService = new PaymentService();
            $paymentService->createPayment([
                'bookingId'     => $bookingId,
                'amount'        => $depositAmount,
                'paymentMethod' => strtoupper($paymentMethod),
                'paymentStatus' => 'PENDING'
            ]);

            $this->conn->commit();
            unset($_SESSION['booking']);

            // 🔥 chỉ trả về booking + deposit
            return $this->jsonResponse([
                'status'    => 'success',
                'bookingId' => $bookingId,
                'deposit'   => $depositAmount,
                'method'    => $paymentMethod
            ]);
        } catch (Exception $e) {

            $this->conn->rollback();

            return $this->jsonResponse([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function cancel()
    {
        // Xóa toàn bộ session booking
        if (isset($_SESSION['booking'])) {
            unset($_SESSION['booking']);
        }

        // Trả về JSON để AJAX nhận
        echo json_encode([
            'success' => true,
            'message' => 'Đã hủy đặt phòng thành công'
        ]);
        exit();
    }
}
