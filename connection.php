<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "1234";
    private $dbname = "crazekicks";
    private $conn;

    // Constructor to establish the database connection
    public function __construct() {
        $this->connect();
    }

    // Method to establish connection
    private function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Method to execute prepared statements
    public function executeQuery($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // Assuming all params are strings
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt;
    }

    // Method to fetch a single record using prepared statements
    public function fetchOne($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : null;
    }

    // Method to fetch multiple records using prepared statements
    public function fetchAll($sql, $params = []) {
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Method to get the last inserted ID
    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

    // Destructor to close connection
    public function __destruct() {
        $this->conn->close();
    }
}