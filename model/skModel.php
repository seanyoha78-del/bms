<?php

class skModel
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

    function addProgram($data)
    {
        $sql = "INSERT INTO program 
    (program_name, program_description, program_date, program_budget, fund_id)
    VALUES 
    (:program_name, :program_description, :program_date, :program_budget, :fund_id)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':program_name' => $data['program_name'] ?? '',
            ':program_description' => $data['program_description'] ?? '',
            ':program_date' => $data['program_date'] ?? '',
            ':program_budget' => $data['program_budget'] ?? '',
            ':fund_id' => $data['fund_id'] ?? null
        ]);
    }

    function addFund($data)
    {
        $sql = "INSERT INTO sk_funds 
    (fund_name, allocated_amount, date_released)
    VALUES 
    (:fund_name, :allocated_amount, :date_released)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':fund_name' => $data['fund_name'],
            ':allocated_amount' => $data['allocated_amount'],
            ':date_released' => $data['date_released'] ?? date('Y-m-d')
        ]);
    }

    function updatePrograms($id, $status)
    {
        $sql = "UPDATE program SET program_status = :status WHERE program_id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    function deleteProgram($id)
    {
        $sql = "DELETE FROM program WHERE program_id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
