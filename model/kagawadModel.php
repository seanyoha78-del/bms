<?php

class kagawadModel
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
        $sql = "INSERT INTO project 
        (project_name, description, start_date, end_date, assigned_official_id, concern_id)
        VALUES 
        (:project_name, :description, :start_date, :end_date, :official_id, :concern_id)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':project_name' => $data['project_name'] ?? '',
            ':description' => $data['description'] ?? '',
            ':start_date' => $data['start_date'] ?? '',
            ':end_date' => $data['end_date'] ?? '',
            ':official_id' => $_SESSION['official_id'] ?? '',
            ':concern_id' => $data['concern_id'] ?? null   // ✅ new field
        ]);
    }

    function addBudget($data)
    {
        // ✅ START SESSION
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // ✅ CHECK LOGIN
        if (!isset($_SESSION['official_id'])) {
            die("User not logged in.");
        }

        $official_id = $_SESSION['official_id'];

        // ✅ CHECK REQUIRED: budget must belong to a project
        if (empty($data['project_id'])) {
            die("Budget must be linked to an existing project.");
        }

        $sql = "INSERT INTO budget 
        (budget_id, budget_official_id, budget_date, budget_payee, budget_employee_no, budget_fund, budget_tin, budget_particulars, 
        budget_amount, budget_cert_a_name, budget_cert_a_date, budget_cert_b_name, budget_cert_date, budget_cert_c_name, budget_cert_cdate, 
        budget_account, budget_account_code, budget_debit, budget_credit)
        VALUES 
        (:budget_id, :official_id, :budget_date, :budget_payee, :budget_employee_no, :budget_fund, :budget_tin, :budget_particulars,
        :budget_amount, :budget_cert_a_name, :budget_cert_a_date, :budget_cert_b_name, :budget_cert_date, :budget_cert_c_name, :budget_cert_cdate,
        :budget_account, :budget_account_code, :budget_debit, :budget_credit)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':budget_id' => $data['project_id'],           // ✅ link to existing project
            ':official_id' => $official_id,
            ':budget_date' => $data['budget_date'] ?? null,
            ':budget_payee' => $data['budget_payee'] ?? null,
            ':budget_employee_no' => $data['budget_employee_no'] ?? null,
            ':budget_fund' => $data['budget_fund'] ?? null,
            ':budget_tin' => $data['budget_tin'] ?? null,
            ':budget_particulars' => $data['budget_particulars'] ?? null,
            ':budget_amount' => $data['budget_amount'] ?? null,
            ':budget_cert_a_name' => $data['budget_cert_a_name'] ?? null,
            ':budget_cert_a_date' => $data['budget_cert_a_date'] ?? null,
            ':budget_cert_b_name' => $data['budget_cert_b_name'] ?? null,
            ':budget_cert_date' => $data['budget_cert_date'] ?? null,
            ':budget_cert_c_name' => $data['budget_cert_c_name'] ?? null,
            ':budget_cert_cdate' => $data['budget_cert_cdate'] ?? null,
            ':budget_account' => $data['budget_account'] ?? null,
            ':budget_account_code' => $data['budget_account_code'] ?? null,
            ':budget_debit' => $data['budget_debit'] ?? null,
            ':budget_credit' => $data['budget_credit'] ?? null
        ]);
    }

    function getBudgetById($id)
    {
        $sql = "SELECT * FROM budget WHERE budget_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateStatus($id, $status)
    {
        $sql = "UPDATE budget SET budget_status = :status WHERE budget_id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    function addConcern($data)
    {
        session_start();

        $official_id = $_SESSION['official_id'] ?? null;

        if (!$official_id) {
            die("No official ID found. Please login.");
        }

        $sql = "INSERT INTO concern 
            (cnrn_official_id, concern_name)
            VALUES 
            (:official_id, :concern_name)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':official_id' => $official_id,
            ':concern_name' => $data['concern_name'] ?? ''
        ]);
    }

    function updateStatuses($id, $status, $updated_by)
    {
        $sql = "UPDATE concern 
            SET concern_status = :status,
                updated_by = :updated_by
            WHERE concern_id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status' => $status,
            ':updated_by' => $updated_by,
            ':id' => $id
        ]);
    }

    function updateProgram($id, $status)
    {
        $sql = "UPDATE project SET status = :status WHERE project_id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    function deleteConcern($id)
    {
        $sql = "DELETE FROM concern WHERE concern_id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    function deleteProgram($id)
    {
        $sql = "DELETE FROM project WHERE project_id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
