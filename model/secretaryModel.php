<?php

class secretaryModel
{
    private $conn;

    function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=localhost;dbname=barangay_system", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // REGISTER USER
    function register($data)
    {

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

    function addResident($data)
    {
        $sql = "INSERT INTO resident 
        (first_name, middle_name, last_name, birthdate, gender, civil_status, address, contact_number, voter_status, occupation)
        VALUES 
        (:first_name, :middle_name, :last_name, :birthdate, :gender, :civil_status, :address, :contact_number, :voter_status, :occupation)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'first_name' => $data['first_name'] ?? '',
            'middle_name' => $data['middle_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'birthdate' => $data['birthdate'] ?? '',
            'gender' => $data['gender'] ?? '',
            'civil_status' => $data['civil_status'] ?? '',
            'address' => $data['address'] ?? '',
            'contact_number' => $data['contact_number'] ?? '',
            'voter_status' => $data['voter_status'] ?? '',
            'occupation' => $data['occupation'] ?? ''
        ]);
    }

    function addBlotter($data)
    {
        $sql = "INSERT INTO blotter 
        (bltr_official_id , bltr_incident_date, bltr_incident_time, bltr_incident_location, bltr_compl_name, bltr_compl_age, bltr_compl_address,
        bltr_resp_name, bltr_resp_age, bltr_resp_address, incident_type, description, action_taken)
        VALUES 
        (:official_id, :bltr_incident_date, :bltr_incident_time, :bltr_incident_location, :bltr_compl_name, :bltr_compl_age, :bltr_compl_address,
        :bltr_resp_name, :bltr_resp_age, :bltr_resp_address, :incident_type, :description, :action_taken)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'official_id' => $_SESSION['official_id'],
            'bltr_incident_date' => $data['bltr_incident_date'] ?? '',
            'bltr_incident_time' => $data['bltr_incident_time'] ?? '',
            'bltr_incident_location' => $data['bltr_incident_location'] ?? '',
            'bltr_compl_name' => $data['bltr_compl_name'] ?? '',
            'bltr_compl_age' => $data['bltr_compl_age'] ?? '',
            'bltr_compl_address' => $data['bltr_compl_address'] ?? '',
            'bltr_resp_name' => $data['bltr_resp_name'] ?? '',
            'bltr_resp_age' => $data['bltr_resp_age'] ?? '',
            'bltr_resp_address' => $data['bltr_resp_address'] ?? '',
            'incident_type' => $data['incident_type'] ?? '',
            'description' => $data['description'] ?? '',
            'action_taken' => $data['action_taken'] ?? ''
        ]);
    }

    // LOGIN CHECK
    function getEmail($email)
    {
        $sql = "SELECT * FROM barangay_official WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
