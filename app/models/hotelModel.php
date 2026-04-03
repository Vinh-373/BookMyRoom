<?php

namespace Models;

require_once "./app/models/MyModels.php";

class HotelModel extends MyModels
{
    protected $table = "hotels";
    public function getAllHotels(
        $location = null,
        $limit = 10,
        $offset = 0,
        $orderBy = 'h.id',
        $orderDir = 'DESC',
        $dates = null
    ) {
        $allowedOrderBy = ['h.id', 'h.hotelName', 'h.rating', 'minPrice'];
        $allowedOrderDir = ['ASC', 'DESC'];

        if (!in_array($orderBy, $allowedOrderBy))   $orderBy  = 'h.id';
        if (!in_array($orderDir, $allowedOrderDir)) $orderDir = 'DESC';

        $params = [];
        $types  = "";

        $sql = "
        SELECT 
            h.*, 
            c.name AS cityName, 
            w.name AS wardName, 
            p.taxCode,
            hi.imageUrl,

            MIN(rc.basePrice) AS minPrice,

            COUNT(DISTINCT pr.id) AS totalRooms,

            SUM(
                CASE 
                    WHEN bd.id IS NULL THEN 1
                    ELSE 0
                END
            ) AS availableRooms

        FROM hotels h

        LEFT JOIN partners p ON h.partnerId = p.userId
        LEFT JOIN wards w ON h.wardId = w.id
        LEFT JOIN cities c ON h.cityId = c.id
        LEFT JOIN hotelimages hi 
            ON h.id = hi.hotelId AND hi.isPrimary = 1

        JOIN roomconfigurations rc 
            ON rc.hotelId = h.id

        JOIN physicalrooms pr 
            ON pr.roomConfigId = rc.id
    ";

        // =========================
        // 📅 DATE JOIN (QUAN TRỌNG)
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
                    AND (
                        ? < bd.checkOut
                        AND ? > bd.checkIn
                    )
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

        $sql .= " WHERE 1=1";

        // =========================
        // 🔍 LOCATION
        // =========================
        if (!empty(trim($location))) {
            $like = "%" . trim($location) . "%";
            $sql .= " AND (c.name LIKE ? OR w.name LIKE ? OR h.hotelName LIKE ?)";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types   .= "sss";
        }

        // =========================
        // 🔽 GROUP
        // =========================
        $sql .= " GROUP BY h.id";

        // =========================
        // 🔥 CHỈ LẤY HOTEL CÒN PHÒNG
        // =========================
        if (!empty($dates)) {
            $sql .= " HAVING availableRooms > 0";
        }

        // =========================
        // 🔽 SORT + PAGINATION
        // =========================
        $sql .= " ORDER BY $orderBy $orderDir";
        $sql .= " LIMIT ? OFFSET ?";

        $params[] = (int)$limit;
        $params[] = (int)$offset;
        $types   .= "ii";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new \Exception("SQL Error: " . $this->conn->error);
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    /**
     * Đếm tổng số khách sạn (phục vụ phân trang)
     */
    public function countHotels($location = null)
    {
        $sql = "
            SELECT COUNT(*) as total
            FROM hotels h
            LEFT JOIN wards w ON h.wardId = w.id
            LEFT JOIN cities c ON h.cityId = c.id
        ";

        $params = [];
        $types = "";

        if (!empty($location)) {
            $sql .= "
                WHERE 
                    h.hotelName LIKE ?
                    OR c.name LIKE ?
                    OR w.name LIKE ?
            ";

            $like = "%" . $location . "%";
            $params = [$like, $like, $like];
            $types = "sss";
        }

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getHotelById($hotelId)
    {
        return $this->join_multi(
            [
                [

                    'table' => 'partners',
                    'on'    => 'hotels.partnerId = partners.userId'
                ],
                [
                    'table' => 'wards',
                    'on'    => 'hotels.wardId = wards.id'
                ],
                [
                    'table' => 'cities',
                    'on'    => 'hotels.cityId = cities.id'
                ],


            ],
            'hotels.*,cities.name as cityName, wards.name as wardName,partners.taxCode',
            ['hotels.id' => $hotelId]
        );
    }
}
