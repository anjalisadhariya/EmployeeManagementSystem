<?php 
include('./include/db-connection.php');

class AssignProject {
    private $conn;
    private $data = [];

    public function __construct($conn, $data) {
        $this->conn = $conn;
        $this->data = $data;
    }

    public function addProject() {
        $sql = "INSERT INTO project (project_name, due_date) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", 
            $this->data['project_name'], 
            $this->data['due_date']
        );
    
        if ($stmt->execute()) {
            $project_id = $this->conn->insert_id;
    
            if (!empty($this->data['employee_id'])) {
                foreach ($this->data['employee_id'] as $employee_id) {
                    $sql2 = "INSERT INTO project_employees (project_id, employee_id) VALUES (?, ?)";
                    $stmt2 = $this->conn->prepare($sql2);
                    $stmt2->bind_param("ii", $project_id, $employee_id);
                    $stmt2->execute();
                }
            }
    
            header("Location: project-managment.php");
            exit;
        } else {
            return "Failed to add project: " . $stmt->error;
        }
    }
    
}

if (isset($_POST['submit'])) {
    $project = new AssignProject($conn, $_POST);

    if (empty($errors)) {
        $result = $project->addProject();
        if ($result) {
            echo "<div class='error'>$result</div>";
        }
    }
}

include('./header.php');

$sql = 'SELECT * FROM employee';
$res = mysqli_query($conn, $sql);
?>

<div class="col-md">
    <div class="card" style="width: 500px; height: max-content; margin: 0 300px; margin-top: 100px;">
        <h5 class="card-header"style="margin: 0 auto;">Assign Project</h5>
        <div class="card-body">
            <form class="needs-validation" novalidate action="project-managment.php" method="POST" enctype="multipart/form-data">
                <div class="form-floating form-floating-outline mb-6">
                    <input type="text" name="project_name" class="form-control" id="bs-validation-project_name" placeholder="Enter Project Name" required />
                    <label for="bs-validation-project_name">Project Name</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter project name.</div>
                </div>
                
                <div class="form-floating form-floating-outline mb-6">
                    <input name="due_date" class="form-control" type="date" id="bs-validation-due-date" required />
                    <label for="bs-validation-due-date">Due Date</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please Enter Your Due Date</div>
                </div>
                
                <div class="form-floating form-floating-outline md-6">
                    <select name="employee_id[]" id="selectpickerSelectDeselect" class="selectpicker w-100" multiple data-actions-box="true" required>
                        <?php while ($row = mysqli_fetch_assoc($res)) { ?> 
                            <option value="<?php echo $row['id']; ?>" <?php if (isset($_POST['employee_id']) && in_array($row['id'], $_POST['employee_id'])) echo 'selected'; ?>>
                                <?php echo $row['first_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <label for="selectpickerSelectDeselect">Select Employee</label>
                </div>
            
                <div class="row" style="margin: 0 170px; margin-top: 20px;">
                    <div class="col-12">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('./footer.php'); ?>
