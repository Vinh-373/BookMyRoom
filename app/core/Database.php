<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // Database Handler
    private $error;

    public function __construct() {
        // Thiết lập DSN (Data Source Name)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true, // Tăng hiệu suất bằng cách giữ kết nối mở
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );

        // Tạo instance PDO
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Lỗi kết nối Database: " . $this->error);
        }
    }

    // Hàm thực thi câu lệnh SQL trả về mảng kết quả (SELECT)
    public function query($sql, $params = []) {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Lấy tất cả kết quả
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    // Lấy 1 kết quả duy nhất
    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
}