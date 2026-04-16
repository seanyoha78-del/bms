<?php

class captainModel
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

    function addTerm($data)
    {
        $sql = "INSERT INTO term 
    (start_year, end_year)
    VALUES 
    (:start_year, :end_year)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':start_year' => $data['start_year'] ?? '',
            ':end_year' => $data['end_year'] ?? ''
        ]);
    }

    function updatePosition($id, $status)
    {
       $sql = "UPDATE barangay_official SET position = :status WHERE official_id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    function updateTermStatus($id, $status)
    {
        // if setting to CURRENT
        if ($status == 'Current') {
            // ❗ turn all others into Inactive first
            $this->conn->query("UPDATE term SET status = 'Inactive'");
        }

        // then update selected one
        $sql = "UPDATE term SET status = :status WHERE term_id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

     function deleteOfficial($id)
    {
        $sql = "DELETE FROM barangay_official WHERE official_id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
    
    function deleteTerm($id)
    {
        $sql = "DELETE FROM term WHERE term_id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    function getCurrentTerm()
    {
        $sql = "SELECT term_id FROM term WHERE status = 'Current' LIMIT 1"; // Note: 'term' table, 'Current' status
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getResidents()
    {
        $sql = "SELECT resident_id, first_name, last_name FROM resident ORDER BY last_name, first_name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function register($data = []) // Accept data parameter
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $term_id = trim($_POST['term_id']);
            $resident_id = trim($_POST['resident_id']);
            $position = trim($_POST['position']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            // Validate password confirmation
            if ($password !== $confirm_password) {
                $_SESSION['message'] = 'Passwords do not match!';
                $_SESSION['msg_type'] = 'danger';
                return false;
            }

            // Validate password length
            if (strlen($password) < 6) {
                $_SESSION['message'] = 'Password must be at least 6 characters long!';
                $_SESSION['msg_type'] = 'danger';
                return false;
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                // Check if resident already has position in current term
                $check_sql = "SELECT official_id FROM barangay_official WHERE resident_id = :resident_id AND term_id = :term_id";
                $check_stmt = $this->conn->prepare($check_sql);
                $check_stmt->execute([':resident_id' => $resident_id, ':term_id' => $term_id]);

                if ($check_stmt->rowCount() > 0) {
                    $_SESSION['message'] = "This resident already has a position in the current term!";
                    $_SESSION['msg_type'] = 'danger';
                    return false;
                }

                // Check if position already taken (except SK kagawad)
                if ($position != 'SK kagawad') {
                    $position_sql = "SELECT official_id FROM barangay_official WHERE position = :position AND term_id = :term_id";
                    $position_stmt = $this->conn->prepare($position_sql);
                    $position_stmt->execute([':position' => $position, ':term_id' => $term_id]);

                    if ($position_stmt->rowCount() > 0) {
                        $_SESSION['message'] = "Position '$position' is already taken in the current term!";
                        $_SESSION['msg_type'] = 'danger';
                        return false;
                    }
                }

                // Insert new official
                $insert_sql = "INSERT INTO barangay_official (term_id, resident_id, position, email, password) 
                           VALUES (:term_id, :resident_id, :position, :email, :password)";
                $insert_stmt = $this->conn->prepare($insert_sql);

                $result = $insert_stmt->execute([
                    ':term_id' => $term_id,
                    ':resident_id' => $resident_id,
                    ':position' => $position,
                    ':email' => $email ?: null,
                    ':password' => $hashed_password
                ]);

                if ($result) {
                    $_SESSION['message'] = "Barangay Official registered successfully!";
                    $_SESSION['msg_type'] = "success";
                    return true;
                } else {
                    $_SESSION['message'] = "Registration failed!";
                    $_SESSION['msg_type'] = "danger";
                    return false;
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = "Database error: " . $e->getMessage();
                $_SESSION['msg_type'] = "danger";
                return false;
            }
        }
        return false;
    }
}
