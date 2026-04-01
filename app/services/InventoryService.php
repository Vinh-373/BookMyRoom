<?php
require_once __DIR__ . '/../core/Service.php';

class InventoryService extends Service {
    public function getInventoryTimeline($hotelId, $startDate, $roomTypeId, $viewDays) {
        $invModel = $this->model('InventoryModel');
        $roomModel = $this->model('RoomModel');

        $start = new DateTime($startDate);
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

        $sDate = $days[0]['full']; $eDate = end($days)['full'];
        $roomConfigs = $roomModel->getRoomTypes($hotelId, ['roomTypeId' => $roomTypeId]);
        $customPrices = $invModel->getPricesInRange($hotelId, $sDate, $eDate);
        $manualInv = $invModel->getManualInventory($hotelId, $sDate, $eDate);
        $bookedData = $invModel->getBookedCount($hotelId, $sDate, $eDate);

        // Ánh xạ dữ liệu theo Key "roomConfigId_date"
        $priceMap = []; foreach ($customPrices as $p) { $priceMap[$p['roomConfigId'].'_'.$p['date']] = $p['price']; }
        $manualMap = []; foreach ($manualInv as $m) { if($m['availableCount'] == 0) $manualMap[$m['roomConfigId'].'_'.$m['date']] = true; }
        
        $bookedMap = [];
        foreach ($bookedData as $booking) {
            $period = new DatePeriod(new DateTime($booking['checkIn']), new DateInterval('P1D'), new DateTime($booking['checkOut']));
            foreach ($period as $dt) {
                $dStr = $dt->format('Y-m-d');
                if ($dStr >= $sDate && $dStr <= $eDate) {
                    $key = $booking['roomConfigId'] . '_' . $dStr;
                    $bookedMap[$key] = ($bookedMap[$key] ?? 0) + (int)$booking['quantity'];
                }
            }
        }

        $grid = [];
        foreach ($roomConfigs as $rc) {
            $configId = $rc['id'];
            $totalRooms = $roomModel->countPhysicalRooms($configId); // Đếm số phòng thực tế của loại này
            $grid[$configId] = ['info' => ['name' => $rc['name'], 'total' => $totalRooms], 'days' => []];
            foreach ($days as $day) {
                $key = $configId . '_' . $day['full'];
                $isManualClosed = isset($manualMap[$key]);
                $available = $isManualClosed ? 0 : ($totalRooms - ($bookedMap[$key] ?? 0));
                $grid[$configId]['days'][$day['full']] = [
                    'price' => (float)($priceMap[$key] ?? $rc['basePrice']),
                    'available' => max(0, $available),
                    'is_manual_closed' => $isManualClosed
                ];
            }
        }
        return ['days' => $days, 'grid' => $grid];
    }

    public function updateDailyInventory($configId, $date, $price, $status) {
        $invModel = $this->model('InventoryModel');
        if ($price !== null && $price !== '') { $invModel->updateDailyPrice($configId, $date, (float)$price); }
        if ($status === 'closed') { return $invModel->setManualClose($configId, $date); }
        else { return $invModel->removeManualOverride($configId, $date); }
    }

    public function processBulkUpdate($data) {
        $invModel = $this->model('InventoryModel');
        $start = new DateTime($data['startDate']);
        $end = (new DateTime($data['endDate']))->modify('+1 day');
        $validDates = [];
        foreach (new DatePeriod($start, new DateInterval('P1D'), $end) as $dt) {
            if (in_array($dt->format('N'), $data['weekdays'])) $validDates[] = $dt->format('Y-m-d');
        }

        if (empty($validDates) || empty($data['roomConfigIds'])) return true;

        if (!empty($data['bulkPrice'])) {
            $invModel->bulkUpdatePrices($data['roomConfigIds'], $validDates, (float)str_replace(['.', ','], '', $data['bulkPrice']));
        }
        if (!empty($data['bulkStatus'])) {
            if ($data['bulkStatus'] === 'closed') $invModel->bulkSetClose($data['roomConfigIds'], $validDates);
            else $invModel->bulkRemoveOverride($data['roomConfigIds'], $validDates);
        }
        return true;
    }
}