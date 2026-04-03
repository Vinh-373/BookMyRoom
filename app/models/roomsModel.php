<?php
namespace Models;

// Load Database class
require_once __DIR__ . '/../core/Database.php';

class roomsModel {
    
    protected $conn;

    /**
     * Constructor - Initialize database connection
     */
    public function __construct() {
        $db = new \Database();
        $this->conn = $db->conn;
    }

    /**
     * Get all rooms with related data
     * @param int $page Page number for pagination
     * @param int $limit Records per page
     * @return array Rooms data with pagination info
     */
    public function getRooms(int $page = 1, int $limit = 10): array {
        try {
            $offset = ($page - 1) * $limit;

            $sql = "SELECT 
                        pr.id,
                        pr.roomNumber,
                        pr.floor,
                        pr.status,
                        rc.basePrice as price,
                        rc.area,
                        rc.maxPeople,
                        rt.name as roomType,
                        h.hotelName,
                        h.id as hotelId
                    FROM physicalrooms pr
                    LEFT JOIN roomconfigurations rc ON pr.roomConfigId = rc.id
                    LEFT JOIN roomtypes rt ON rc.roomTypeId = rt.id
                    LEFT JOIN hotels h ON rc.hotelId = h.id
                    WHERE h.deletedAt IS NULL
                    ORDER BY h.hotelName, pr.roomNumber
                    LIMIT ? OFFSET ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ii', $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            $rooms = $result->fetch_all(\MYSQLI_ASSOC);

            // Get total count
            $countSql = "SELECT COUNT(*) as total 
                        FROM physicalrooms pr
                        LEFT JOIN roomconfigurations rc ON pr.roomConfigId = rc.id
                        LEFT JOIN hotels h ON rc.hotelId = h.id
                        WHERE h.deletedAt IS NULL";
            
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();
            
            return [
                'rooms' => $rooms,
                'total' => $countRow['total'],
                'page' => $page,
                'limit' => $limit,
                'totalPages' => ceil($countRow['total'] / $limit)
            ];
        } catch (\Exception $e) {
            error_log("Error in getRooms: " . $e->getMessage());
            return ['rooms' => [], 'total' => 0, 'page' => $page, 'limit' => $limit, 'totalPages' => 0];
        }
    }

    /**
     * Get room statistics
     * @return array Statistics data
     */
    public function getRoomStats(): array {
        try {
            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'AVAILABLE' THEN 1 ELSE 0 END) as available,
                        SUM(CASE WHEN status = 'BOOKED' THEN 1 ELSE 0 END) as booked,
                        SUM(CASE WHEN status = 'MAINTENANCE' THEN 1 ELSE 0 END) as maintenance
                    FROM physicalrooms pr
                    LEFT JOIN roomconfigurations rc ON pr.roomConfigId = rc.id
                    LEFT JOIN hotels h ON rc.hotelId = h.id
                    WHERE h.deletedAt IS NULL";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $stats = $result->fetch_assoc();

            return [
                'total' => $stats['total'] ?? 0,
                'available' => $stats['available'] ?? 0,
                'booked' => $stats['booked'] ?? 0,
                'maintenance' => $stats['maintenance'] ?? 0
            ];
        } catch (\Exception $e) {
            error_log("Error in getRoomStats: " . $e->getMessage());
            return ['total' => 0, 'available' => 0, 'booked' => 0, 'maintenance' => 0];
        }
    }

    /**
     * Get distinct hotels for filter
     * @return array List of hotels
     */
    public function getHotelsForFilter(): array {
        try {
            $sql = "SELECT DISTINCT h.id, h.hotelName
                    FROM roomconfigurations rc
                    LEFT JOIN hotels h ON rc.hotelId = h.id
                    WHERE h.deletedAt IS NULL
                    ORDER BY h.hotelName";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(\MYSQLI_ASSOC);
        } catch (\Exception $e) {
            error_log("Error in getHotelsForFilter: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get distinct room types for filter
     * @return array List of room types
     */
    public function getRoomTypesForFilter(): array {
        try {
            $sql = "SELECT DISTINCT rt.id, rt.name as typeName
                    FROM roomtypes rt
                    ORDER BY rt.name";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(\MYSQLI_ASSOC);
        } catch (\Exception $e) {
            error_log("Error in getRoomTypesForFilter: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Search rooms by hotel or room number
     * @param string $query Search query
     * @param int $limit Maximum results
     * @return array Search results
     */
    public function searchRooms(string $query, int $limit = 20): array {
        try {
            $pattern = "%{$query}%";
            $sql = "SELECT 
                        pr.id,
                        pr.roomNumber,
                        pr.floor,
                        pr.status,
                        rc.basePrice as price,
                        rc.area,
                        rc.maxPeople,
                        rt.name as roomType,
                        h.hotelName,
                        h.id as hotelId
                    FROM physicalrooms pr
                    LEFT JOIN roomconfigurations rc ON pr.roomConfigId = rc.id
                    LEFT JOIN roomtypes rt ON rc.roomTypeId = rt.id
                    LEFT JOIN hotels h ON rc.hotelId = h.id
                    WHERE (h.hotelName LIKE ? OR pr.roomNumber LIKE ?)
                    AND h.deletedAt IS NULL
                    ORDER BY h.hotelName, pr.roomNumber
                    LIMIT ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssi', $pattern, $pattern, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(\MYSQLI_ASSOC);
        } catch (\Exception $e) {
            error_log("Error in searchRooms: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Filter rooms by conditions
     * @param int $hotelId Hotel ID filter
     * @param int $roomTypeId Room type filter
     * @param string $status Status filter
     * @param int $page Pagination
     * @param int $limit Records per page
     * @return array Filtered rooms
     */
    public function filterRooms(?int $hotelId = null, ?int $roomTypeId = null, ?string $status = null, int $page = 1, int $limit = 10): array {
        try {
            $offset = ($page - 1) * $limit;
            $conditions = ['h.deletedAt IS NULL'];
            $params = [];
            $types = '';

            if ($hotelId) {
                $conditions[] = 'rc.hotelId = ?';
                $params[] = $hotelId;
                $types .= 'i';
            }

            if ($roomTypeId) {
                $conditions[] = 'rc.roomTypeId = ?';
                $params[] = $roomTypeId;
                $types .= 'i';
            }

            if ($status && in_array($status, ['AVAILABLE', 'BOOKED', 'MAINTENANCE'])) {
                $conditions[] = 'pr.status = ?';
                $params[] = $status;
                $types .= 's';
            }

            $whereClause = implode(' AND ', $conditions);

            $sql = "SELECT 
                        pr.id,
                        pr.roomNumber,
                        pr.floor,
                        pr.status,
                        rc.basePrice as price,
                        rc.area,
                        rc.maxPeople,
                        rt.name as roomType,
                        h.hotelName,
                        h.id as hotelId
                    FROM physicalrooms pr
                    LEFT JOIN roomconfigurations rc ON pr.roomConfigId = rc.id
                    LEFT JOIN roomtypes rt ON rc.roomTypeId = rt.id
                    LEFT JOIN hotels h ON rc.hotelId = h.id
                    WHERE {$whereClause}
                    ORDER BY h.hotelName, pr.roomNumber
                    LIMIT ? OFFSET ?";

            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';

            $stmt = $this->conn->prepare($sql);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $rooms = $result->fetch_all(\MYSQLI_ASSOC);

            // Get total count for pagination
            $countSql = "SELECT COUNT(*) as total 
                        FROM physicalrooms pr
                        LEFT JOIN roomconfigurations rc ON pr.roomConfigId = rc.id
                        LEFT JOIN hotels h ON rc.hotelId = h.id
                        WHERE {$whereClause}";

            $countParams = [];
            $countTypes = '';
            
            if ($hotelId) {
                $countParams[] = $hotelId;
                $countTypes .= 'i';
            }
            if ($roomTypeId) {
                $countParams[] = $roomTypeId;
                $countTypes .= 'i';
            }
            if ($status && in_array($status, ['AVAILABLE', 'BOOKED', 'MAINTENANCE'])) {
                $countParams[] = $status;
                $countTypes .= 's';
            }

            $countStmt = $this->conn->prepare($countSql);
            if (!empty($countParams)) {
                $countStmt->bind_param($countTypes, ...$countParams);
            }
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();

            return [
                'rooms' => $rooms,
                'total' => $countRow['total'],
                'page' => $page,
                'limit' => $limit,
                'totalPages' => ceil($countRow['total'] / $limit)
            ];
        } catch (\Exception $e) {
            error_log("Error in filterRooms: " . $e->getMessage());
            return ['rooms' => [], 'total' => 0, 'page' => $page, 'limit' => $limit, 'totalPages' => 0];
        }
    }

    /**
     * Get room detail
     * @param int $roomId Room ID
     * @return array Room detail with booking info
     */
    public function getRoomDetail(int $roomId): array {
        try {
            $sql = "SELECT 
                        pr.id,
                        pr.roomNumber,
                        pr.floor,
                        pr.status,
                        pr.roomConfigId,
                        rc.basePrice as price,
                        rc.area,
                        rc.maxPeople,
                        rt.name as roomType,
                        h.hotelName,
                        h.id as hotelId,
                        h.rating as hotelRating
                    FROM physicalrooms pr
                    LEFT JOIN roomconfigurations rc ON pr.roomConfigId = rc.id
                    LEFT JOIN roomtypes rt ON rc.roomTypeId = rt.id
                    LEFT JOIN hotels h ON rc.hotelId = h.id
                    WHERE pr.id = ? AND h.deletedAt IS NULL";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $roomId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_assoc() ?: [];
        } catch (\Exception $e) {
            error_log("Error in getRoomDetail: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update room status
     * @param int $roomId Room ID
     * @param string $status New status
     * @return array Result with success flag
     */
    public function updateRoomStatus(int $roomId, string $status): array {
        try {
            if (!in_array($status, ['AVAILABLE', 'BOOKED', 'MAINTENANCE'])) {
                return ['success' => false, 'error' => 'Invalid status'];
            }

            $sql = "UPDATE physicalrooms SET status = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('si', $status, $roomId);
            $stmt->execute();

            return ['success' => true, 'affectedRows' => $stmt->affected_rows];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
