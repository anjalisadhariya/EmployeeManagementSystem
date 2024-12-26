<?php
include("./include/db-connection.php");
include("header.php");

class Department {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getDepartments() {
        $sql = "SELECT 
                    project.id, 
                    project.project_name, 
                    project.due_date, 
                    project.sub_date, 
                    project.status, 
                    project_employees.employee_id, 
                    employee.first_name 
                FROM 
                    project
                INNER JOIN project_employees ON project.id = project_employees.project_id
                INNER JOIN employee ON project_employees.employee_id = employee.id";
        $result = mysqli_query($this->conn, $sql);
        $project_status = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $project_status[] = $row;
            }
        }
        return $project_status;
    }
    

    public function renderDepartmentRows() {
        $project_status = $this->getDepartments();
        $html = "";
        $i = 1;
        foreach ($project_status as $status) {
            
            $html .= "<tr>
                        <td>{$i}</td>
                        <td>{$status['first_name']}</td>
                        <td>{$status['project_name']}</td>
                        <td>{$status['due_date']}</td>
                        <td>". ($status['sub_date'] === null ? "0000-00-00"  : $status['sub_date']) ."</td>
                        <td>" . ($status['status'] === '0' ? "Due"  : "Submitted") . "</td>
                    </tr>";
            $i++;
        }
        return $html;
    }
}

$departmentObj = new Department($conn);
?>

<div class="card" style="max-width: max-content; margin: 50px 100px;">

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>employee name</th>
                    <th>project name</th>
                    <th>due date</th>
                    <th>submitted date</th>
                    <th>status</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php echo $departmentObj->renderDepartmentRows(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php include("./footer.php"); ?>
