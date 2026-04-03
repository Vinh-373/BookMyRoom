<?php

namespace Models;

require_once __DIR__ . '/myModels.php';

class InformationModel extends \myModels
{
    protected $table = "users";

    public function getInformationUser($userId)
    {
        $sql = "
        SELECT
            u.fullName,
            u.email,
            u.phone,
            u.address,
            u.gender,
            u.birthDate,
            u.cityId AS cityId,
            u.wardId AS wardId,

            c.name AS cityName,

            w.name AS wardName

        FROM users u
        LEFT JOIN cities c ON u.cityId = c.id
        LEFT JOIN wards w ON u.wardId = w.id
        WHERE u.id = ?
        AND u.status = 'ACTIVE'
    ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        // bind tham số (i = integer)
        $stmt->bind_param("i", $userId);

        // execute (KHÔNG truyền tham số)
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // lấy dữ liệu
        $result = $stmt->get_result();

        // fetch tất cả
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    // Lấy danh sách thành phố
    public function getCity()
    {
        $sql = "SELECT c.id , c.name FROM cities c";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //lấy danh sách phường/xã theo cityId
    public function getWardByCityId($cityId)
    {
        $sql = "SELECT w.id, w.name FROM wards w WHERE w.cityId = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        // bind tham số (i = integer)
        $stmt->bind_param("i", $cityId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllPhoneNumber()
    {
        $sql = "SELECT phone FROM users";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        return array_column($result->fetch_all(MYSQLI_ASSOC), 'phone');
    }

    public function getAllEmail()
    {
        $sql = "SELECT email FROM users";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        return array_column($result->fetch_all(MYSQLI_ASSOC), 'email');
    }

    public function getEmailByUserId($userId)
    {
        $sql = "SELECT email FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['email'] ?? '';
    }
    
    public function getPhoneByUserId($userId)
    {
        $sql = "SELECT phone FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['phone'] ?? '';
    }

    public function isPhoneExist($phone)
    {
        $sql = "SELECT id FROM users WHERE phone = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $phone);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function isEmailExist($email)
    {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function updateAddress($address, $wardId, $cityId, $userId)
    {
        $sql = "UPDATE users 
            SET address = ?, wardId = ?, cityId = ?
            WHERE id = ?
            ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        // s = string, i = integer
        $stmt->bind_param("siii", $address, $wardId, $cityId, $userId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        return $stmt->affected_rows >= 0;// dùng để kiểm tra xem có thay đổi nào sau khi thực hiện truy vấn ( có thể 0 có gì khác ">=0" vì có thể người dùng chỉ cập nhật lại thông tin cũ)
    }

    public function updateFullName($fullName, $userId)
    {
        $sql = "UPDATE users 
            SET fullName = ?
            WHERE id = ?
            ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        // s = string, i = integer
        $stmt->bind_param("si", $fullName, $userId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        return $stmt->affected_rows >= 0;
    }

    public function updateGender($gender, $userId)
    {
        $sql = "UPDATE users
            SET gender = ?
            WHERE id = ?                    
            ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        // s = string, i = integer
        $stmt->bind_param("si", $gender, $userId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        return $stmt->affected_rows >= 0;

    }
    public function updateBirthDate($birthDate, $userId)
    {
        $sql = "UPDATE users 
            SET birthDate = ?
            WHERE id = ?
            ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        // s = string, i = integer
        $stmt->bind_param("si", $birthDate, $userId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        return $stmt->affected_rows >= 0;
    }

    public function updateEmail($email, $userId)
    {
        $sql = "UPDATE users 
            SET email = ?
            WHERE id = ?
            ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        // s = string, i = integer
        $stmt->bind_param("si", $email, $userId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        return $stmt->affected_rows >= 0;
    }

    public function updatePhoneNumber($phone, $userId)
    {
        $sql = "UPDATE users 
            SET phone = ?
            WHERE id = ?
            ";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        // s = string, i = integer
        $stmt->bind_param("si", $phone, $userId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        return $stmt->affected_rows >= 0;
    }

}