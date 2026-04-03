<?php
namespace Models;
require_once './app/models/MyModels.php';

class RoomConfigModel extends MyModels
{
     protected $table = "roomconfigurations";
//        public function getRoomConfigsAvailableByHotel($hotelId, $dates = null)
// {
//     $params = [];
//     $types  = "";

//     $sql = "
//         SELECT 
//             rc.id AS roomConfigId,
//             rc.*,
//             rt.name AS roomTypeName,


//             COUNT(DISTINCT pr.id) AS totalRooms,

//             SUM(
//                 CASE 
//                     WHEN bd.id IS NULL THEN 1
//                     ELSE 0
//                 END
//             ) AS availableRooms

//         FROM roomconfigurations rc
//      JOIN roomtypes rt ON rc.roomTypeId = rt.id
//         JOIN physicalrooms pr 
//             ON pr.roomConfigId = rc.id
//             AND pr.status = 'AVAILABLE'
//     ";

//     // =========================
//     // 📅 DATE FILTER
//     // =========================
//     if (!empty($dates)) {
//         $parts = explode(' to ', $dates);

//         $checkIn  = $parts[0] ?? null;
//         $checkOut = $parts[1] ?? null;

//         $checkInObj  = \DateTime::createFromFormat('d/m/Y', $checkIn);
//         $checkOutObj = \DateTime::createFromFormat('d/m/Y', $checkOut);

//         $checkInSQL  = $checkInObj ? $checkInObj->format('Y-m-d') : null;
//         $checkOutSQL = $checkOutObj ? $checkOutObj->format('Y-m-d') : null;

//         if ($checkInSQL && $checkOutSQL) {

//             $sql .= "
//                 LEFT JOIN bookingdetails bd 
//                     ON bd.physicalRoomId = pr.id
//                     AND (
//                         ? < bd.checkOut
//                         AND ? > bd.checkIn
//                     )
//             ";

//             $params[] = $checkInSQL;
//             $params[] = $checkOutSQL;
//             $types   .= "ss";

//         } else {
//             $sql .= " LEFT JOIN bookingdetails bd ON bd.physicalRoomId = pr.id";
//         }

//     } else {
//         $sql .= " LEFT JOIN bookingdetails bd ON bd.physicalRoomId = pr.id";
//     }

//     $sql .= "
//         WHERE rc.hotelId = ?
//         GROUP BY rc.id
//         HAVING availableRooms > 0
//         ORDER BY rc.basePrice ASC
//     ";

//     $params[] = $hotelId;
//     $types   .= "i";

//     $stmt = $this->conn->prepare($sql);

//     if (!$stmt) {
//         throw new \Exception("SQL Error: " . $this->conn->error);
//     }

//     $stmt->bind_param($types, ...$params);
//     $stmt->execute();

//     return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
// }
public function getRoomConfigsAvailableByHotel($hotelId, $dates = null)
{
    $params = [];
    $types  = "";

    $sql = "
        SELECT 
            rc.id AS roomConfigId,
            rc.*,
            rt.name AS roomTypeName,

            COUNT(DISTINCT pr.id) AS totalRooms,

            -- Số phòng khả dụng
            SUM(CASE WHEN bd.id IS NULL THEN 1 ELSE 0 END) AS availableRooms,

            -- <<< DANH SÁCH physicalRoomId KHẢ DỤNG >>>
            GROUP_CONCAT(
                DISTINCT CASE 
                    WHEN bd.id IS NULL THEN pr.id 
                END 
                ORDER BY pr.roomNumber ASC 
                SEPARATOR ','
            ) AS availablePhysicalRoomIds

        FROM roomconfigurations rc
        JOIN roomtypes rt ON rc.roomTypeId = rt.id
        JOIN physicalrooms pr 
            ON pr.roomConfigId = rc.id 
            AND pr.status = 'AVAILABLE'
    ";

    // =========================
    // 📅 DATE FILTER (Overlap check)
    // =========================
    if (!empty($dates)) {
        $parts = explode(' to ', $dates);

        $checkIn  = $parts[0] ?? null;
        $checkOut = $parts[1] ?? null;

        $checkInObj  = \DateTime::createFromFormat('d/m/Y', $checkIn);
        $checkOutObj = \DateTime::createFromFormat('d/m/Y', $checkOut);

        $checkInSQL  = $checkInObj ? $checkInObj->format('Y-m-d') : null;
        $checkOutSQL = $checkOutObj ? $checkOutObj->format('Y-m-d') : null;

        if ($checkInSQL && $checkOutSQL) {
            $sql .= "
                LEFT JOIN bookingdetails bd 
                    ON bd.physicalRoomId = pr.id
                    AND (? < bd.checkOut AND ? > bd.checkIn)
            ";
            $params[] = $checkInSQL;
            $params[] = $checkOutSQL;
            $types   .= "ss";
        } else {
            $sql .= " LEFT JOIN bookingdetails bd ON bd.physicalRoomId = pr.id";
        }
    } else {
        $sql .= " LEFT JOIN bookingdetails bd ON bd.physicalRoomId = pr.id";
    }

    $sql .= "
        WHERE rc.hotelId = ?
        GROUP BY rc.id, rt.name
        HAVING availableRooms > 0
        ORDER BY rc.basePrice ASC
    ";

    $params[] = (int)$hotelId;
    $types   .= "i";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        throw new \Exception("SQL Error: " . $this->conn->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // === XỬ LÝ CHUYỂN STRING THÀNH MẢNG ===
    foreach ($results as &$row) {
        if (!empty($row['availablePhysicalRoomIds'])) {
            $row['availablePhysicalRoomIds'] = array_map(
                'intval', 
                explode(',', $row['availablePhysicalRoomIds'])
            );
        } else {
            $row['availablePhysicalRoomIds'] = [];
        }

        // Optional: Thêm thông tin chi tiết hơn nếu cần
        $row['availableRoomsCount'] = (int)$row['availableRooms']; // để dễ dùng
    }

    return $results;
}
public function getById($id)
    {
        $sql = "SELECT rc.*, rt.name AS roomTypeName, h.hotelName, c.name AS cityName, w.name AS wardName, h.address FROM roomconfigurations rc JOIN roomtypes rt ON rc.roomTypeId = rt.id JOIN hotels h ON rc.hotelId = h.id JOIN wards w ON h.wardId = w.id JOIN cities c ON w.cityId = c.id WHERE rc.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}