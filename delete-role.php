<?php
include('./include/db-connection.php');
$role = $_SESSION['role'];

class RoleManager {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function deleteRoleById($id) {
        $success = true;

        if (!$this->deleteFromTableById('employee', 'employee_id', $id)) {
            $success = false;
        }

        if (!$this->deleteFromTableById('hr', 'hr_id', $id)) {
            $success = false;
        }

        if (!$this->deleteFromTableById('manager', 'manager_id', $id)) {
            $success = false;
        }

        if (!$this->deleteFromTableById('users', 'id', $id)) {
            $success = false;
        }

        return $success;
    }

    private function deleteFromTableById($table, $column, $id) {
        $stmt = $this->conn->prepare("DELETE FROM $table WHERE $column = ?");
        $stmt->bind_param("i", $id);
        print_r($stmt);
        return $stmt->execute();
    }
}


$roleManager = new RoleManager($conn);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($roleManager->deleteRoleById($id)) {
        header("Location: manage-role.php");
        exit;
    } else {
        echo "Failed to delete role.";
    }
}
?>
