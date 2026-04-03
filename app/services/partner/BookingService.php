<?php
// require_once __DIR__ . '/../../core/Service.php';

class BookingService extends Service {
    public function getBookingPageData($hotelId, $filters) {
        $bookingModel = $this->model('BookingModel');
        
        $limit = 10;
        $page = (int)($filters['page'] ?? 1);
        $offset = ($page - 1) * $limit;

        // 1. Lấy dữ liệu thô
        $bookings = $bookingModel->getBookings($hotelId, $filters, $limit, $offset) ?: [];
        $totalBookings = $bookingModel->getTotalBookingCount($hotelId, $filters) ?: 0;
        $roomTypes = $bookingModel->getRoomTypesByHotel($hotelId) ?: [];

        // 2. Xử lý Revenue Insight
        $monthlyRevenue = $bookingModel->getMonthlyRevenue($hotelId) ?: 0;
        $monthlyGoal = 200000; 
        $revenueProgress = ($monthlyGoal > 0) ? min(round(($monthlyRevenue / $monthlyGoal) * 100), 100) : 0;

        // 3. Xử lý Occupancy Insight
        $occData = $bookingModel->getRealtimeOccupancy($hotelId);
        $totalRooms = $occData['total'] ?? 0;
        $occupiedRooms = $occData['occupied'] ?? 0;
        $occupancyRate = ($totalRooms > 0) ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        $detailMap = [];

        // 4. Hậu xử lý dữ liệu cho View
        foreach ($bookings as &$b) {
            // Định dạng hiển thị trong Table
            $b['formatted_date'] = date('M d, Y • H:i A', strtotime($b['createdAt']));
            $b['formatted_amount'] = '$' . number_format($b['totalAmount'], 2);
            $b['avatar_text'] = strtoupper(substr($b['fullName'], 0, 2));
            $b['status_class'] = strtolower($b['paymentStatus'] ?? 'pending');

            // Map dữ liệu cho Modal Detail (Dùng ID làm key)
            $detailMap[$b['id']] = [
                'id'           => $b['id'],
                'fullName'     => $b['fullName'],
                'phone'        => $b['phone'],
                'roomTypeName' => $b['roomTypeName'],
                'nights'       => $b['nights'],
                'totalAmount'  => number_format($b['totalAmount'], 2),
                'status'       => strtoupper($b['bookingStatus']),
                'payment'      => strtoupper($b['paymentStatus'] ?? 'UNPAID'),
                'checkIn'      => date('d/m/Y', strtotime($b['checkInDate'] ?? $b['createdAt'])),
                'checkOut'     => !empty($b['checkOutDate']) ? date('d/m/Y', strtotime($b['checkOutDate'])) : 'N/A',
                'review_content' => $b['reviewContent'] ?? 'Khách hàng chưa để lại bình luận.',
                'rating' => $b['rating'] ?? 0
            ];
        }

        return [
            'bookings'      => $bookings,
            'detailMap'     => $detailMap,
            'totalCount'    => $totalBookings,
            'currentPage'   => $page,
            'totalPages'    => max(1, ceil($totalBookings / $limit)),
            'filters'       => $filters,
            'showingStart'  => ($totalBookings > 0) ? $offset + 1 : 0,
            'showingEnd'    => min($offset + $limit, $totalBookings),
            'roomTypes'     => $roomTypes,
            'insights'      => [
                'revenue' => [
                    'total' => '$' . number_format($monthlyRevenue, 2),
                    'progress' => $revenueProgress,
                    'goal_text' => $revenueProgress . '% of monthly goal reached'
                ],
                'occupancy' => [
                    'rate' => $occupancyRate . '%',
                    'trend' => '↑ 4.2%' 
                ]
            ]
        ];
    }

    public function updateStatus($id, $newStatus) {
        $bookingModel = $this->model('BookingModel');
        $result = $bookingModel->updateStatus($id, $newStatus);

        if ($result) {
            // Tự động cập nhật thời gian thực tế để làm báo cáo chính xác
            if ($newStatus === 'COMPLETED') {
                $bookingModel->updateActualCheckOut($id);
            }
            if ($newStatus === 'STAYING') {
                $bookingModel->updateActualCheckIn($id);
            }
        }
        return $result;
    }

    public function exportToCSV($hotelId, $filters) {
        $bookingModel = $this->model('BookingModel');
        
        // Lấy dữ liệu (không dùng Limit/Offset để xuất toàn bộ danh sách đã lọc)
        $bookings = $bookingModel->getBookings($hotelId, $filters, 999999, 0);

        // Tên file: Bookings_HotelName_NgayHienTai.csv
        $filename = "Bookings_Hotel" . $hotelId . "_" . date('Ymd_His') . ".csv";

        // Thiết lập Header để trình duyệt hiểu đây là file tải về
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // Tạo file pointer mở ở chế độ ghi ra output stream
        $output = fopen('php://output', 'w');

        // Xuất BOM để Excel hiển thị đúng tiếng Việt (UTF-8)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Đặt tiêu đề cột cho file CSV
        fputcsv($output, ['ID', 'Ngày Đặt', 'Khách Hàng', 'Số Điện Thoại', 'Loại Phòng', 'Số Đêm', 'Tổng Tiền', 'Trạng Thái', 'Thanh Toán']);

        // Đổ dữ liệu vào file
        if (!empty($bookings)) {
            foreach ($bookings as $b) {
                fputcsv($output, [
                    'BK-' . $b['id'],
                    date('d/m/Y H:i', strtotime($b['createdAt'])),
                    $b['fullName'],
                    " " . $b['phone'], // Thêm khoảng trắng để Excel không biến số điện thoại thành số khoa học
                    $b['roomTypeName'],
                    $b['nights'],
                    $b['totalAmount'],
                    strtoupper($b['bookingStatus']),
                    strtoupper($b['paymentStatus'] ?? 'UNPAID')
                ]);
            }
        }

        fclose($output);
        exit;
    }
}