<?php

class Database {
    private $conn;

    public function __construct() {
        // Load configuration
        $config = require 'config.php';

        try {
            // Create PDO instance
            $this->conn = new PDO($config['database_dsn'], $config['database_user'], $config['database_pass']);
            // Set PDO to throw exceptions on errors
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Catch any connection errors and output the error message
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Method to get the PDO connection instance
    public function getConnection() {
        return $this->conn;
    }

    // Close the database connection
    public function closeConnection() {
        $this->conn = null;
    }
}

?>
