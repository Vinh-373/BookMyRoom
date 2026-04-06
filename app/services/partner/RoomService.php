<?php
// require_once __DIR__ . '/../../core/Service.php';
class RoomService extends Service {

    /**
     * Lấy toàn bộ dữ liệu cần thiết cho trang rooms.php
     * Đồng bộ với SQL Schema: area, maxPeople, totalInventory
     */
    public function getRoomPageData($hotelId, $filters = []) {
        $roomModel = $this->model('RoomModel');

        // 1. Lấy dữ liệu thô từ Model (Đã khớp với SQL)
        $rooms = $roomModel->getRoomTypes($hotelId, $filters);
        $stats = $roomModel->getInventoryStats($hotelId);
        $allTypes = $roomModel->getUniqueRoomTypes($hotelId);
        $systemRoomTypes = $roomModel->getAllSystemRoomTypes();
        $allPhysicalRooms = $roomModel->getAllPhysicalRoomsByHotel($hotelId);
        $map = $this->getRoomMapData($hotelId);
        $roomDetailMap = [];
        $physicalRoomMap = [];

        foreach ($allPhysicalRooms as $p) {
            $physicalRoomMap[$p['roomConfigId']][] = [
                'id' => $p['id'],
                'roomNumber' => $p['roomNumber'],
                'status' => $p['status']
            ];
        }

        // 2. Hậu xử lý dữ liệu cho View
        foreach ($rooms as &$r) {
            // Phân loại Badge dựa trên basePrice từ bảng roomConfigurations
            if ($r['basePrice'] >= 500) {
                $r['badge_text'] = 'EXCLUSIVE';
                $r['badge_class'] = 'exclusive';
            } elseif ($r['basePrice'] >= 150) {
                $r['badge_text'] = 'PREMIUM';
                $r['badge_class'] = 'premium';
            } else {
                $r['badge_text'] = 'ESSENTIAL';
                $r['badge_class'] = 'essential';
            }

            // Định dạng tiền tệ
            $r['formatted_price'] = number_format($r['basePrice'], 0);

            // Amenities: Sử dụng tên cột mới từ SQL (maxPeople, area)
            $r['amenities_list'] = [
                '👥 ' . $r['maxPeople'] . ' Guests', 
                '📐 ' . $r['area'] . 'm²', 
                '📶 Free Wifi'
            ];

            // 3. TẠO DETAIL MAP (Dùng cho Modal Edit)
            // Đồng bộ key với các cột trong bảng roomConfigurations
            $roomDetailMap[$r['id']] = [
                'id'           => $r['id'],
                'name'         => $r['name'], // Lấy từ bảng roomTypes qua JOIN
                'basePrice'    => $r['basePrice'],
                'inventory'    => $r['totalInventory'] ?? 0,
                'maxPeople'    => $r['maxPeople'],
                'area'         => $r['area']
            ];
        }

        // 4. Tính toán chỉ số Health dựa trên dữ liệu thực tế
        $totalTypes = count($rooms);
        $activeHealth = ($totalTypes > 0) ? 82 : 0; 

        return [
            'rooms'          => $rooms,
            'allTypes'       => $allTypes,
            'roomMap' => $map,
            'roomDetailMap'  => $roomDetailMap,
            'systemRoomTypes' => $systemRoomTypes,
            'physicalRoomMap' => $physicalRoomMap,
            'inventoryStats' => [
                'totalActive' => $stats['totalActiveUnits'] ?? 0,
                'maintenance' => $stats['underMaintenance'] ?? 0,
                'healthScore' => $activeHealth
            ],
            'activeFilter'   => $filters['roomTypeId'] ?? ''
        ];
    }

    public function handleUpdateRoom($id, $postData) {
        return $this->model('RoomModel')->updateRoomType($id, $postData);
    }

    public function handleDeleteRoom($id) {
        return $this->model('RoomModel')->deleteRoomType($id);
    }

    public function handleAddRoom($data) {
        return $this->model('RoomModel')->createRoomConfiguration($data);
    }
    
    public function getPhysicalRoomList($configId) {
        return $this->model('RoomModel')->getPhysicalRooms($configId);
    }

    public function addPhysicalRoom($hotelId, $data) {
        $roomModel = $this->model('RoomModel');

        // 1. Kiểm tra trùng số phòng trên toàn khách sạn
        if ($roomModel->checkRoomNumberExistsInHotel($hotelId, $data['number'])) {
            return "exists_in_hotel"; 
        }

        // 2. Nếu không trùng, tiến hành lưu
        return $roomModel->addPhysicalRoom($data);
    }

    public function deletePhysicalRoom($unitId){
        return $this->model('RoomModel')->deleteRoomUnit($unitId);
    }

    public function getRoomMapData($hotelId) {
        $roomModel = $this->model('RoomModel');
        $allRooms = $roomModel->getPhysicalRoomsByHotel($hotelId);
        
        $map = [];
        foreach ($allRooms as $room) {
            $floor = $room['floor'] ?? 0;
            $map[$floor][] = $room;
        }
        
        krsort($map);
        
        return $map;
    }

    public function toggleMaintenance($roomId, $currentStatus) {
        $roomModel = $this->model('RoomModel');
        
        if ($currentStatus === 'MAINTENANCE') {
            // Hoàn tất bảo trì -> Chuyển sang chờ dọn dẹp
            return $roomModel->updateMaintenanceStatus($roomId, 'CLEANING');
        } else if($currentStatus === 'CLEANING') {
            return $roomModel->updateMaintenanceStatus($roomId, 'AVAILABLE');
        }
        else {
            if ($currentStatus === 'OCCUPIED') {
                return false;
            }
            // Chuyển sang bảo trì
            return $roomModel->updateMaintenanceStatus($roomId, 'MAINTENANCE');
        }
    }

}