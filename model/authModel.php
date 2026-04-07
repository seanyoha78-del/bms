<?php

class authModel {
    private $conn;

    function __construct() {
        try {
            $this->conn = new PDO("mysql:host=localhost;dbname=barangay_system", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // REGISTER USER
    function register($data) {

        // Ensure all required keys exist, otherwise set default
        $first_name = trim($data['first_name'] ?? '');
        $middle_name = trim($data['middle_name'] ?? '');
        $last_name = trim($data['last_name'] ?? '');
        $position = trim($data['position'] ?? '');
        $contact_number = trim($data['contact_number'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $status = trim($data['status'] ?? 'inactive');

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO barangay_official
                (first_name, middle_name, last_name, position, contact_number, email, password, status)
                VALUES 
                (:first_name, :middle_name, :last_name, :position, :contact, :email, :password, :status)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'position' => $position,
            'contact' => $contact_number,
            'email' => $email,
            'password' => $hashed_password,
            'status' => $status
        ]);
    }

    // LOGIN CHECK
    function getEmail($email) {
        $sql = "SELECT * FROM barangay_official WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}