<?php
require_once __DIR__ . '/../core/Service.php';

class InventoryService extends Service {
    public function getInventoryTimeline($hotelId, $startDate = null, $roomTypeId = null, $viewDays = 14) {
        $invModel = $this->model('InventoryModel');
        $roomModel = $this->model('RoomModel');

        // 1. Khởi tạo mảng ngày
        $start = $startDate ? new DateTime($startDate) : new DateTime();
        $days = [];
        for ($i = 0; $i < $viewDays; $i++) {
            $curr = clone $start; $curr->modify("+$i day");
            $days[] = [
                'full' => $curr->format('Y-m-d'),
                'day_num' => $curr->format('d'),
                'day_name' => strtoupper($curr->format('D')),
                'is_weekend' => in_array($curr->format('N'), [6, 7])
            ];
        }

        $sDate = $days[0]['full'];
        $eDate = end($days)['full'];

        // 2. Lấy dữ liệu từ Model
        $roomConfigs = $roomModel->getRoomTypes($hotelId, ['roomTypeId' => $roomTypeId]);
        $bookedData = $invModel->getBookedCount($hotelId, $sDate, $eDate);
        $customPrices = $invModel->getPricesInRange($hotelId, $sDate, $eDate);
        
        // QUAN TRỌNG: Lấy dữ liệu tạm đóng thủ công từ roominventory
        $manualData = $invModel->getManualInventory($hotelId, $sDate, $eDate);

        $grid = [];
        foreach ($roomConfigs as $rc) {
            $configId = $rc['id'];
            $totalRooms = $roomModel->countPhysicalRooms($configId); 

            $grid[$configId] = [
                'info' => ['name' => $rc['name'], 'total' => $totalRooms],
                'days' => []
            ];

            foreach ($days as $day) {
                $date = $day['full'];
                
                // 3. Kiểm tra xem có bản ghi "Tạm đóng" (availableCount = 0) trong database không
                $mEntry = array_filter($manualData, function($m) use ($configId, $date) {
                    return $m['roomConfigId'] == $configId && $m['date'] == $date && $m['availableCount'] == 0;
                });
                $isManualClosed = !empty($mEntry);

                // 4. Tính số phòng đã đặt thực tế
                $bookedQty = 0;
                foreach ($bookedData as $booking) {
                    if ($booking['roomConfigId'] == $configId) {
                        if ($date >= $booking['checkIn'] && $date < $booking['checkOut']) {
                            $bookedQty += (int)$booking['quantity'];
                        }
                    }
                }

                // 5. Logic tính toán hiển thị:
                // Nếu bị "Tạm đóng" thủ công -> available = 0
                // Nếu không -> available = Tổng - Đã đặt
                $available = $isManualClosed ? 0 : ($totalRooms - $bookedQty);
                
                // 6. Lấy giá (ưu tiên giá tùy chỉnh)
                $pEntry = array_filter($customPrices, fn($p) => $p['roomConfigId'] == $configId && $p['date'] == $date);
                $price = !empty($pEntry) ? array_shift($pEntry)['price'] : $rc['basePrice'];

                $grid[$configId]['days'][$date] = [
                    'price' => (float)$price,
                    'available' => max(0, $available),
                    'is_sold_out' => ($available <= 0 || $isManualClosed),
                    // QUAN TRỌNG: Gửi biến này về để JavaScript trong Modal biết là đang "Tạm đóng"
                    'is_manual_closed' => $isManualClosed 
                ];
            }
        }
        return ['days' => $days, 'grid' => $grid];
    }

    public function updateDailyInventory($configId, $date, $price, $status) {
        $invModel = $this->model('InventoryModel');
        
        // 1. Cập nhật giá
        $invModel->updateDailyPrice($configId, $date, $price);

        // 2. Cập nhật trạng thái
        if ($status === 'closed') {
            return $invModel->setManualClose($configId, $date);
        } else {
            // Khi mở lại (Hoạt động), ta xóa dòng ghi đè trong roominventory 
            // để hệ thống tự động tính theo (Tổng phòng - Đã đặt)
            return $invModel->removeManualOverride($configId, $date);
        }
    }

    public function processBulkUpdate($data) {
        $startDate = new DateTime($data['startDate']);
        $endDate = new DateTime($data['endDate']);
        $endDate->modify('+1 day'); // Bao gồm cả ngày kết thúc

        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($startDate, $interval, $endDate);

        foreach ($dateRange as $date) {
            $currentDate = $date->format('Y-m-d');
            $dayOfWeek = $date->format('N'); // 1 (T2) đến 7 (CN)

            // Kiểm tra xem ngày này có nằm trong danh sách Thứ được chọn không
            if (in_array($dayOfWeek, $data['weekdays'])) {
                foreach ($data['roomConfigIds'] as $configId) {
                    // Chỉ cập nhật nếu người dùng nhập giá hoặc chọn trạng thái mới
                    if (!empty($data['bulkPrice']) || !empty($data['bulkStatus'])) {
                        
                        // Nếu để trống giá, ta lấy giá hiện tại (hoặc giá gốc) để giữ nguyên
                        $price = !empty($data['bulkPrice']) ? $data['bulkPrice'] : null;
                        $status = !empty($data['bulkStatus']) ? $data['bulkStatus'] : null;

                        // Gọi lại hàm update lẻ đã có sẵn
                        $this->updateDailyInventory($configId, $currentDate, $price, $status);
                    }
                }
            }
        }
        return true;
    }
}