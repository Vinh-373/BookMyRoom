<?php
namespace Models;

// Load Database class
require_once __DIR__ . '/../core/Database.php';

class bookingsModel {
    private $conn;

    public function __construct() {
        $db = new \Database();
        $this->conn = $db->conn;
    }

    /**
     * Get paginated bookings with details
     * @param int $page Page number
     * @param int $limit Results per page
     * @return array Bookings with pagination info
     */
    public function getBookings(int $page = 1, int $limit = 10): array {
        try {
            $offset = ($page - 1) * $limit;

            $sql = "SELECT 
                        b.id,
                        b.userId,
                        b.status,
                        b.source,
                        b.totalAmount,
                        b.createdAt,
                        u.fullName as customerName,
                        u.email as customerEmail,
                        u.phone as customerPhone,
                        GROUP_CONCAT(bd.id) as detailIds,
                        GROUP_CONCAT(h.hotelName SEPARATOR '|') as hotelNames,
                        GROUP_CONCAT(rt.name SEPARATOR '|') as roomTypes,
                        MIN(bd.checkIn) as checkInDate,
                        MAX(bd.checkOut) as checkOutDate,
                        SUM(bd.quantity) as totalRooms
                    FROM bookings b
                    LEFT JOIN users u ON b.userId = u.id
                    LEFT JOIN bookingdetails bd ON b.id = bd.bookingId
                    LEFT JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                    LEFT JOIN roomtypes rt ON rc.roomTypeId = rt.id
                    LEFT JOIN hotels h ON rc.hotelId = h.id
                    GROUP BY b.id
                    ORDER BY b.createdAt DESC
                    LIMIT ? OFFSET ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ii', $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            $bookings = $result->fetch_all(\MYSQLI_ASSOC);

            // Get total count
            $countSql = "SELECT COUNT(*) as total FROM bookings";
            $countStmt = $this->conn->prepare($countSql);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();

            return [
                'bookings' => $bookings,
                'total' => $countRow['total'],
                'page' => $page,
                'limit' => $limit,
                'totalPages' => ceil($countRow['total'] / $limit)
            ];
        } catch (\Exception $e) {
            error_log("Error in getBookings: " . $e->getMessage());
            return ['bookings' => [], 'total' => 0, 'page' => $page, 'limit' => $limit, 'totalPages' => 0];
        }
    }

    /**
     * Get booking statistics
     * @return array Statistics
     */
    public function getBookingStats(): array {
        try {
            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'PENDING' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN status = 'CONFIRMED' THEN 1 ELSE 0 END) as confirmed,
                        SUM(CASE WHEN status = 'CANCELLED' THEN 1 ELSE 0 END) as cancelled,
                        SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) as completed,
                        SUM(totalAmount) as totalRevenue
                    FROM bookings";

            $result = $this->conn->query($sql);
            $row = $result->fetch_assoc();

            return [
                'total' => intval($row['total'] ?? 0),
                'pending' => intval($row['pending'] ?? 0),
                'confirmed' => intval($row['confirmed'] ?? 0),
                'cancelled' => intval($row['cancelled'] ?? 0),
                'completed' => intval($row['completed'] ?? 0),
                'totalRevenue' => floatval($row['totalRevenue'] ?? 0)
            ];
        } catch (\Exception $e) {
            error_log("Error in getBookingStats: " . $e->getMessage());
            return [
                'total' => 0,
                'pending' => 0,
                'confirmed' => 0,
                'cancelled' => 0,
                'completed' => 0,
                'totalRevenue' => 0
            ];
        }
    }

    /**
     * Search bookings by customer name, email, booking ID, dates, or amount
     * @param string $query Search query
     * @return array Search results
     */
    public function searchBookings(string $query): array {
        try {
            $pattern = "%{$query}%";
            $numericQuery = intval($query);
            
            $sql = "SELECT 
                        b.id,
                        b.userId,
                        b.status,
                        b.source,
                        b.totalAmount,
                        b.createdAt,
                        u.fullName as customerName,
                        u.email as customerEmail,
                        u.phone as customerPhone,
                        GROUP_CONCAT(h.hotelName SEPARATOR '|') as hotelNames,
                        GROUP_CONCAT(rt.name SEPARATOR '|') as roomTypes,
                        MIN(bd.checkIn) as checkInDate,
                        MAX(bd.checkOut) as checkOutDate,
                        SUM(bd.quantity) as totalRooms
                    FROM bookings b
                    LEFT JOIN users u ON b.userId = u.id
                    LEFT JOIN bookingdetails bd ON b.id = bd.bookingId
                    LEFT JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                    LEFT JOIN roomtypes rt ON rc.roomTypeId = rt.id
                    LEFT JOIN hotels h ON rc.hotelId = h.id
                    WHERE 
                        u.fullName LIKE ? 
                        OR u.email LIKE ? 
                        OR b.id = ?
                        OR u.phone LIKE ?
                        OR h.hotelName LIKE ?
                        OR rt.name LIKE ?
                        OR DATE(b.createdAt) LIKE ?
                        OR DATE(bd.checkIn) LIKE ?
                    GROUP BY b.id
                    ORDER BY b.createdAt DESC
                    LIMIT 50";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssisssss', $pattern, $pattern, $numericQuery, $pattern, $pattern, $pattern, $pattern, $pattern);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(\MYSQLI_ASSOC);
        } catch (\Exception $e) {
            error_log("Error in searchBookings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Filter bookings by status, source, and date range
     * @param string $status Booking status
     * @param string $source Booking source
     * @param string $fromDate From date
     * @param string $toDate To date
     * @param int $page Page number
     * @param int $limit Results per page
     * @return array Filtered bookings
     */
    public function filterBookings(
        ?string $status = null,
        ?string $source = null,
        ?string $fromDate = null,
        ?string $toDate = null,
        int $page = 1,
        int $limit = 10
    ): array {
        try {
            $offset = ($page - 1) * $limit;
            $conditions = [];
            $params = [];
            $types = '';

            // Add status filter
            if ($status && in_array($status, ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])) {
                $conditions[] = 'b.status = ?';
                $params[] = $status;
                $types .= 's';
            }

            // Add source filter
            if ($source && in_array($source, ['WEBSITE', 'BOOKING_DOT_COM', 'EXPEDIA', 'DIRECT'])) {
                $conditions[] = 'b.source = ?';
                $params[] = $source;
                $types .= 's';
            }

            // Add date range filter
            if ($fromDate) {
                $conditions[] = 'DATE(b.createdAt) >= ?';
                $params[] = $fromDate;
                $types .= 's';
            }

            if ($toDate) {
                $conditions[] = 'DATE(b.createdAt) <= ?';
                $params[] = $toDate;
                $types .= 's';
            }

            $whereClause = empty($conditions) ? '1=1' : implode(' AND ', $conditions);

            $sql = "SELECT 
                        b.id,
                        b.userId,
                        b.status,
                        b.source,
                        b.totalAmount,
                        b.createdAt,
                        u.fullName as customerName,
                        u.email as customerEmail,
                        u.phone as customerPhone,
                        GROUP_CONCAT(h.hotelName SEPARATOR '|') as hotelNames,
                        GROUP_CONCAT(rt.name SEPARATOR '|') as roomTypes,
                        MIN(bd.checkIn) as checkInDate,
                        MAX(bd.checkOut) as checkOutDate,
                        SUM(bd.quantity) as totalRooms
                    FROM bookings b
                    LEFT JOIN users u ON b.userId = u.id
                    LEFT JOIN bookingdetails bd ON b.id = bd.bookingId
                    LEFT JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                    LEFT JOIN roomtypes rt ON rc.roomTypeId = rt.id
                    LEFT JOIN hotels h ON rc.hotelId = h.id
                    WHERE {$whereClause}
                    GROUP BY b.id
                    ORDER BY b.createdAt DESC
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
            $bookings = $result->fetch_all(\MYSQLI_ASSOC);

            // Get total count for pagination
            $countSql = "SELECT COUNT(DISTINCT b.id) as total 
                        FROM bookings b
                        LEFT JOIN bookingdetails bd ON b.id = bd.bookingId
                        WHERE {$whereClause}";

            $countParams = [];
            $countTypes = '';
            
            if ($status && in_array($status, ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])) {
                $countParams[] = $status;
                $countTypes .= 's';
            }
            if ($source && in_array($source, ['WEBSITE', 'BOOKING_DOT_COM', 'EXPEDIA', 'DIRECT'])) {
                $countParams[] = $source;
                $countTypes .= 's';
            }
            if ($fromDate) {
                $countParams[] = $fromDate;
                $countTypes .= 's';
            }
            if ($toDate) {
                $countParams[] = $toDate;
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
                'bookings' => $bookings,
                'total' => $countRow['total'],
                'page' => $page,
                'limit' => $limit,
                'totalPages' => ceil($countRow['total'] / $limit)
            ];
        } catch (\Exception $e) {
            error_log("Error in filterBookings: " . $e->getMessage());
            return ['bookings' => [], 'total' => 0, 'page' => $page, 'limit' => $limit, 'totalPages' => 0];
        }
    }

    /**
     * Get booking detail with all room details
     * @param int $bookingId Booking ID
     * @return array Booking details
     */
    public function getBookingDetail(int $bookingId): array {
        try {
            $sql = "SELECT 
                        b.id,
                        b.userId,
                        b.status,
                        b.source,
                        b.totalAmount,
                        b.platformFee,
                        b.partnerRevenue,
                        b.createdAt,
                        u.fullName as customerName,
                        u.email as customerEmail,
                        u.phone as customerPhone,
                        u.address as customerAddress
                    FROM bookings b
                    LEFT JOIN users u ON b.userId = u.id
                    WHERE b.id = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $bookingId);
            $stmt->execute();
            $result = $stmt->get_result();
            $booking = $result->fetch_assoc();

            if (!$booking) {
                return [];
            }

            // Get booking details
            $detailSql = "SELECT 
                            bd.id,
                            bd.checkIn,
                            bd.checkOut,
                            bd.quantity,
                            bd.price,
                            bd.amount,
                            h.hotelName,
                            rt.name as roomType,
                            pr.roomNumber
                        FROM bookingdetails bd
                        LEFT JOIN roomconfigurations rc ON bd.roomConfigId = rc.id
                        LEFT JOIN roomtypes rt ON rc.roomTypeId = rt.id
                        LEFT JOIN hotels h ON rc.hotelId = h.id
                        LEFT JOIN physicalrooms pr ON bd.physicalRoomId = pr.id
                        WHERE bd.bookingId = ?";

            $detailStmt = $this->conn->prepare($detailSql);
            $detailStmt->bind_param('i', $bookingId);
            $detailStmt->execute();
            $detailResult = $detailStmt->get_result();
            $booking['details'] = $detailResult->fetch_all(\MYSQLI_ASSOC);

            return $booking;
        } catch (\Exception $e) {
            error_log("Error in getBookingDetail: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update booking status
     * @param int $bookingId Booking ID
     * @param string $status New status
     * @return array Result with success flag
     */
    public function updateBookingStatus(int $bookingId, string $status): array {
        try {
            if (!in_array($status, ['PENDING', 'CONFIRMED', 'CANCELLED', 'COMPLETED'])) {
                return ['success' => false, 'message' => 'Invalid status'];
            }

            $sql = "UPDATE bookings SET status = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('si', $status, $bookingId);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Status updated successfully'];
            }

            return ['success' => false, 'message' => 'Failed to update status'];
        } catch (\Exception $e) {
            error_log("Error in updateBookingStatus: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
