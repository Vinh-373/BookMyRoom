<?php

namespace Controllers\customer;

use Controller;
use Services\BookingService;
use Services\PaymentService;

require_once "./app/services/bookingService.php";
require_once "./app/services/paymentService.php";

class Payment extends Controller
{
    private $bookingService;
    private $paymentService;

    private $momoConfig = [
        'endpoint'     => 'https://test-payment.momo.vn/v2/gateway/api/create',
        'partnerCode'  => 'MOMOBKUN20180529',
        'accessKey'    => 'klm05TvNBzhg7h7j',
        'secretKey'    => 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa',
        'redirectUrl'  => 'http://localhost/BookMyRoom/payment/momoReturn',
        'ipnUrl'       => 'https://example.com/ipn', // ✅ FIX

    ];
    private $vnpayConfig = [
        'endpoint'   => 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
        'returnUrl'  => 'http://localhost/BookMyRoom/payment/vnpayReturn',
        'tmnCode'    => 'X0OMC87E',
        'hashSecret' => 'HCRIZME1L9P0AN9NP1V2ZULSHSLN6JFA',
    ];

    public function __construct()
    {
        $this->bookingService = new BookingService();
        $this->paymentService = new PaymentService();

        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }
    }

    /* ======================================================
     *  TẠO LINK THANH TOÁN MOMO
     * ====================================================== */
    public function createMomoPayment($bookingId, $amount)
    {
        if (!$bookingId || !$amount) return null;

        $orderId   = "BOOK_" . time();
        $requestId = time();

        $extraData = base64_encode(json_encode([
            'bookingId' => $bookingId
        ]));

        $rawHash = "accessKey={$this->momoConfig['accessKey']}"
            . "&amount={$amount}"
            . "&extraData={$extraData}"
            . "&ipnUrl={$this->momoConfig['ipnUrl']}"
            . "&orderId={$orderId}"
            . "&orderInfo=Thanh toan booking"
            . "&partnerCode={$this->momoConfig['partnerCode']}"
            . "&redirectUrl={$this->momoConfig['redirectUrl']}"
            . "&requestId={$requestId}"
            . "&requestType=captureWallet";

        $signature = hash_hmac('sha256', $rawHash, $this->momoConfig['secretKey']);

        // ✅ lưu payment trước khi đi thanh toán
        // $this->paymentService->createPayment([
        //     'bookingId'     => $bookingId,
        //     'amount'        => $amount,
        //     'paymentMethod' => 'MOMO',
        //     'paymentStatus' => 'PENDING'
        // ]);

        $payload = [
            'partnerCode' => $this->momoConfig['partnerCode'],
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => 'Thanh toan booking',
            'redirectUrl' => $this->momoConfig['redirectUrl'],
            'ipnUrl'      => $this->momoConfig['ipnUrl'],
            'requestType' => 'captureWallet',
            'extraData'   => $extraData,
            'signature'   => $signature
        ];

        $result = $this->execPostRequest(
            $this->momoConfig['endpoint'],
            json_encode($payload)
        );

        $data = json_decode($result, true);

        return $data['payUrl'] ?? null;
    }

    /* ======================================================
     *  API GỌI TỪ FRONTEND
     * ====================================================== */
    public function createPayment()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $bookingId = $input['bookingId'] ?? 0;
        $amount    = $input['amount'] ?? 0;
        $method    = strtolower($input['method'] ?? '');

        if ($method === 'momo') {
            $payUrl = $this->createMomoPayment($bookingId, $amount);
        } elseif ($method === 'vnpay') {
            $payUrl = $this->createVnpayPayment($bookingId, $amount);
        } else {
            $payUrl = null;
        }

        return $this->jsonResponse([
            'payUrl' => $payUrl
        ]);
    }

    /* ======================================================
     *  MOMO RETURN (QUAN TRỌNG NHẤT)
     * ====================================================== */
    public function momoReturn()
    {
        $resultCode = $_GET['resultCode'] ?? -1;
        $extraData  = $_GET['extraData'] ?? '';

        $decoded = json_decode(base64_decode($extraData), true);
        $bookingId = $decoded['bookingId'] ?? 0;

        if (!$bookingId) {
            echo "Booking not found";
            return;
        }

        try {

            // ===== SUCCESS =====
            if ($resultCode == 0) {

                // update payment
                $this->paymentService->updatePaymentByBooking($bookingId, [
                    'paymentStatus' => 'PAID'
                ]);

                // update booking
                $this->bookingService->updateBooking($bookingId, [
                    'status' => 'CONFIRMED'
                ]);

               
            }

            // ===== FAILED =====
            else {

                $rooms = $this->bookingService->getRoomsByBooking($bookingId);

                if (!empty($rooms)) {
                    $this->bookingService->markRoomsAvailable($rooms);
                }

                $this->bookingService->deleteBookingDetails($bookingId);
                $this->paymentService->deletePaymentByBooking($bookingId);

                $this->bookingService->updateBooking($bookingId, [
                    'status' => 'CANCELLED'
                ]);
            }
        } catch (\Exception $e) {
            error_log("RETURN ERROR: " . $e->getMessage());
        }

        // 👉 redirect về trang lịch sử
        header("Location: /BookMyRoom/history");
        exit;
    }

    /* ======================================================
     *  CURL REQUEST
     * ====================================================== */
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function createVnpayPayment($bookingId, $amount)
    {
        if (!$bookingId || !$amount) {
            return null;
        }

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $orderId = "BOOK_" . time();

        // lưu payment PENDING
        // $this->paymentService->createPayment([
        //     'bookingId'     => $bookingId,
        //     'amount'        => $amount,
        //     'paymentMethod' => 'VNPAY',
        //     'paymentStatus' => 'PENDING'
        // ]);

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $this->vnpayConfig['tmnCode'],
            "vnp_Amount"     => $amount * 100, // VNPay nhân 100
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $_SERVER['REMOTE_ADDR'],
            "vnp_Locale"     => "vn",
            "vnp_OrderInfo"  => "Thanh toan booking",
            "vnp_OrderType"  => "billpayment",
            "vnp_ReturnUrl"  => $this->vnpayConfig['returnUrl'],
            "vnp_TxnRef"     => $orderId,
        ];

        // truyền bookingId qua
        $inputData['vnp_OrderInfo'] .= "|bookingId={$bookingId}";

        ksort($inputData);

        $query = "";
        $hashdata = "";

        foreach ($inputData as $key => $value) {
            $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . "=" . urlencode($value);
            $query    .= urlencode($key) . "=" . urlencode($value) . "&";
        }

        $secureHash = hash_hmac('sha512', $hashdata, $this->vnpayConfig['hashSecret']);

        $payUrl = $this->vnpayConfig['endpoint'] . "?" . $query . "vnp_SecureHash=" . $secureHash;

        return $payUrl;
    }
    public function vnpayReturn()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $inputData = $_GET;
        $vnpSecureHash = $inputData['vnp_SecureHash'] ?? '';

        unset($inputData['vnp_SecureHash']);
        unset($inputData['url']);

        ksort($inputData);

        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . "=" . urlencode($value);
        }

        $secureHash = hash_hmac('sha512', $hashdata, $this->vnpayConfig['hashSecret']);

        if ($secureHash !== $vnpSecureHash) {
            die("Sai chữ ký");
        }

        $orderInfo = $_GET['vnp_OrderInfo'] ?? '';
        $resultCode = $_GET['vnp_ResponseCode'] ?? '-1';

        // lấy bookingId từ orderInfo
        preg_match('/bookingId=(\d+)/', $orderInfo, $matches);
        $bookingId = $matches[1] ?? 0;

        if (!$bookingId) {
            die("Không tìm thấy booking");
        }

        // ===== SUCCESS =====
        if ($resultCode == '00') {

            $this->paymentService->updatePaymentByBooking($bookingId, [
                'paymentStatus' => 'PAID'
            ]);

            $this->bookingService->updateBooking($bookingId, [
                'status' => 'CONFIRMED'
            ]);
           
        }
        // ===== FAILED =====
        else {

            $rooms = $this->bookingService->getRoomsByBooking($bookingId);

            if (!empty($rooms)) {
                $this->bookingService->markRoomsAvailable($rooms);
            }

            $this->bookingService->deleteBookingDetails($bookingId);
            $this->paymentService->deletePaymentByBooking($bookingId);

            $this->bookingService->updateBooking($bookingId, [
                'status' => 'CANCELLED'
            ]);
        }

        header("Location: /BookMyRoom/history");
        exit;
    }
}
