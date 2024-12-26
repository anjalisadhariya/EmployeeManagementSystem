<?php
include("./include/db-connection.php");
include("header.php");

class Department {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getDepartments() {
        $sql = "SELECT * FROM department";
        $result = mysqli_query($this->conn, $sql);
        $departments = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $departments[] = $row;
            }
        }
        return $departments;
    }

    public function renderDepartmentRows() {
        $departments = $this->getDepartments();
        $html = "";
        $i = 1;
        foreach ($departments as $department) {
            $html .= "<tr>
                        <td>{$i}</td>
                        <td>{$department['department_name']}</td>
                        <td>{$department['description']}</td>
                        <td class='d-flex'>
                            <a href='update-department.php?id={$department['id']}' class='d-block mx-1 btn btn-success'>Update</a>
                            <a href='delete-department.php?id={$department['id']}' class='d-block mx-1 btn btn-danger'>Delete</a>
                        </td>
                      </tr>";
            $i++;
        }
        return $html;
    }
}

$departmentObj = new Department($conn);
?>

<div class="card" style="max-width: max-content; margin: 50px 100px;">
    <h5 class="card-header" style="margin: 0 300px;">Manage Department</h5>
    <a href="add-department.php" class="d-block mx-1 btn" style="background-color: #666cff; color:white">Add Department</a>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>DESCRIPTION</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php echo $departmentObj->renderDepartmentRows(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("./footer.php"); ?>
