<?php
include('./include/db-connection.php');

class Department {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function deleteDepartment($id) {
        $managerId = $this->getManagerIdByDepartment($id);
        if ($managerId) {
            $this->deleteUserById($managerId);
            $this->deleteManagerByDepartment($id);
        }

        return $this->deleteDepartmentById($id);
    }

    private function getManagerIdByDepartment($departmentId) {
        $stmt = $this->conn->prepare("SELECT manager_id FROM manager WHERE department_id = ?");
        $stmt->bind_param("i", $departmentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['manager_id'];
        }

        return null;
    }

    private function deleteUserById($userId) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    private function deleteManagerByDepartment($departmentId) {
        $stmt = $this->conn->prepare("DELETE FROM manager WHERE department_id = ?");
        $stmt->bind_param("i", $departmentId);
        $stmt->execute();
    }

    private function deleteDepartmentById($departmentId) {
        $stmt = $this->conn->prepare("DELETE FROM department WHERE id = ?");
        $stmt->bind_param("i", $departmentId);
        return $stmt->execute();
    }
}

$department = new Department($conn);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($department->deleteDepartment($id)) {
        header("Location: manage-department.php");
        exit;
    } else {
        echo "Failed to delete department.";
    }
}
?>
