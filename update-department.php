<?php
include('./include/db-connection.php');

class Department {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getDepartmentById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM department WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public function updateDepartment($id, $department_name, $description) {
        $stmt = $this->conn->prepare("UPDATE department SET department_name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $department_name, $description, $id);
        $result = $stmt->execute();

        return $result;
    }
}


$department = new Department($conn);

if (isset($_POST['submit'])) {
    $id = $_GET['id'];
    $department_name = $_POST['department_name'];
    $description = $_POST['description'];

    if ($department->updateDepartment($id, $department_name, $description)) {
        echo "Department updated successfully";
        header("Location: manage-department.php");
        exit();
    } else {
        echo "Failed to update department";
    }
}

include('header.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $departmentData = $department->getDepartmentById($id);

    if ($departmentData) {
        $department_name = $departmentData['department_name'];
        $description = $departmentData['description'];
    }
}
?>

<div class="col-md" >
  <div class="card" style="width: 500px; height: max-content; margin: 0 300px; margin-top: 100px;">
    <h5 class="card-header" style="margin: 0 auto;">Update Department</h5>
    <div class="card-body">
      <form class="needs-validation" novalidate  action="" method="POST" >
        <div class="form-floating form-floating-outline mb-6">
          <input type="text" name="department_name" class="form-control"  placeholder="Enter Department" value="<?php echo $department_name ?>" required />
          <label for="bs-validation-name"> Department Name </label>
          <div class="valid-feedback"></div>
          <div class="invalid-feedback">Please enter Department name.</div>
        </div>

        <div class="input-group input-group-merge">
          <div class="form-floating form-floating-outline">
            <textarea name ="description" class="form-control h-px-75" placeholder="type here..."  required></textarea>
            <label> Description </label>
            <div class="valid-feedback"></div>
            <div class="invalid-feedback">Please enter description.</div>
          </div>
        </div>
        
        <div class="row" style="margin: 0 170px; margin-top: 20px;">
          <div class="col-12">
            <button type="submit" name="submit" class="btn btn-primary" >Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>
<?php include('./footer.php');?>

