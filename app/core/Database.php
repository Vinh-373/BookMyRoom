<?php 
class Database {
   public $host = "localhost";
    public $username = "root";
    public $password = "123456"; 
    public $database = "bookmyroom";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

}