<?php
    include('./include/db-connection.php');

    class Department {
        private $conn;
        private $data = [];
    
        public function __construct($conn, $data) {
            $this->conn = $conn;
            $this->data = $data;
        }

    
        public function addDepartment() {
            $sql = "INSERT INTO department (department_name, description)
                    VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", 
                $this->data['department_name'], 
                $this->data['description'], 
            );
    
            if ($stmt->execute()) {
                header("Location: manage-department.php");
                exit;
            } else {
                return "Failed to add department: " . $stmt->error;
            }
        }
    
    }
    
    if (isset($_POST['submit'])) {
        $department = new Department($conn, $_POST);
    
        if (empty($errors)) {
            
            $result = $department->addDepartment();
            if ($result) {
                echo "<div class='error'>$result</div>";
            }
        }
    }
    include('./header.php');

?>

<div class="col-md" >
  <div class="card" style="width: 500px; height: max-content; margin: 0 300px; margin-top: 100px;">
    <h5 class="card-header" style="margin: 0 auto;">Add Department</h5>
    <div class="card-body">
      <form class="needs-validation" novalidate  action="" method="POST" >
        <div class="form-floating form-floating-outline mb-6">
          <input type="text" name="department_name" class="form-control"  placeholder="Enter Department" required />
          <label for="bs-validation-name"> Department Name </label>
          <div class="valid-feedback"></div>
          <div class="invalid-feedback">Please enter Department name.</div>
        </div>

        <div class="input-group input-group-merge">
          <div class="form-floating form-floating-outline">
            <textarea name ="description" class="form-control h-px-75" placeholder="type here..." required></textarea>
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

