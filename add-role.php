<?php
include('./include/db-connection.php');

class Role {
    private $conn;
    private $data = [];
    private $errors = [];

    public function __construct($conn, $data) {
        $this->conn = $conn;
        $this->data = $data;
        error_log("Data received: " . print_r($this->data, true)); 
    }
    
    public function uploadImage($file) {
        
        if (isset($file['name']) && !empty($file['name'])) {
            $folderPath = "C:/xampp/htdocs/php-example/employee-management/uploads/";
            if (!file_exists($folderPath)) {
                if (!mkdir($folderPath, 0777, true)) {
                    $this->errors['img'] = "Failed to create folder: $folderPath";
                    return '';
                }
            }
    
            $img = basename($file['name']);
            $target_file = $folderPath . $img;
    
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowed_types)) {
                $this->errors['img'] = "Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.";
                return '';
            }
    
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                return $img;
            } else {
                $this->errors['img'] = "Failed to upload image.";
            }
        }
        return '';
    }
    
    public function addRole($img) {
        if (empty($img)) {
            $this->errors[] = "Image upload failed.";
            return false;
        }
        error_log("Role received in PHP: " . $_POST['role']);

        $hashed_password = md5($this->data['password']);
    
        $sql1 = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->bind_param("ssi", $this->data['email'], $hashed_password, $this->data['role']);
        
        if (!$stmt1->execute()) {
            $this->errors[] = "Failed to add user: " . $stmt1->error;
            $stmt1->close();
            return false;
        }
        $user_id = $stmt1->insert_id;
        $stmt1->close();
    
        if (!isset($this->data['gender']) || !in_array($this->data['gender'], ['0', '1'])) {
            $this->errors[] = "Invalid gender selected.";
            return false;
        }        
    
        $department_id = null;
        if (isset($this->data['department_id']) && !empty($this->data['department_id'])) {
            $department_id = $this->data['department_id'];
            $sql_check_department = "SELECT id FROM department WHERE id = ?";
            $stmt_check = $this->conn->prepare($sql_check_department);
            $stmt_check->bind_param("i", $department_id);
            $stmt_check->execute();
            $result = $stmt_check->get_result();
            if ($result->num_rows === 0) {
                $this->errors[] = "Invalid department ID.";
                $stmt_check->close();
                return false;
            }
            $stmt_check->close();
        }
    
        $sql2 = "";
        if ($this->data['role'] == '2') { 
            $sql2 = "INSERT INTO hr (hr_id, first_name, last_name, email, password, phoneno, gender, hire_date, salary)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } elseif ($this->data['role'] == '3') { 
            $sql2 = "INSERT INTO manager (manager_id, department_id, first_name, last_name, email, password, phoneno, gender, hire_date, salary)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } elseif ($this->data['role'] == '4') { 
            $sql2 = "INSERT INTO employee (employee_id, department_id, first_name, last_name, email, password, phoneno, address, gender, birth_date, img, hire_date, job_title, salary)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }
    
        $stmt2 = $this->conn->prepare($sql2);
        
        if ($this->data['role'] == '2') { 
            $stmt2->bind_param("isssssssi", 
                $user_id, 
                $this->data['first_name'], 
                $this->data['last_name'], 
                $this->data['email'], 
                $hashed_password, 
                $this->data['phoneno'], 
                $this->data['gender'], 
                $this->data['hire_date'], 
                $this->data['salary']
            );
        }
         elseif ($this->data['role'] == '3') { 
            $stmt2->bind_param("iisssssssi", 
                $user_id, 
                $department_id, 
                $this->data['first_name'], 
                $this->data['last_name'], 
                $this->data['email'], 
                $hashed_password, 
                $this->data['phoneno'], 
                $this->data['gender'], 
                $this->data['hire_date'], 
                $this->data['salary']
            );
        } elseif ($this->data['role'] == '4') {
            $stmt2->bind_param("iisssssssssssi", 
                $user_id, 
                $department_id, 
                $this->data['first_name'], 
                $this->data['last_name'], 
                $this->data['email'], 
                $hashed_password, 
                $this->data['phoneno'],
                $this->data['address'] ,
                $this->data['gender'], 
                $this->data['birth_date'],
                $img,
                $this->data['hire_date'], 
                $this->data['job_title'], 
                $this->data['salary']
            );
        }
    
        if ($stmt2->execute()) {
            $stmt2->close();
            header("Location: manage-role.php");
            exit;
        } else {
            $this->errors[] = "Failed to add role: " . $stmt2->error;
            $stmt2->close();
            return false;
        }
    }
    

    public function getErrors() {
        return $this->errors;
    }
}


if (isset($_POST['submit'])) {
    if (!isset($_POST['role']) || !in_array($_POST['role'], ['2', '3', '4'])) {
        die("Invalid role selected");
    }

    
    $employee = new Role($conn, $_POST);
    error_log("FILES array: " . print_r($_FILES, true));

    $img = $employee->uploadImage($_FILES['image']);
    
    if (empty($employee->getErrors()) && $employee->addRole($img)) {
        echo "<div class='success'>Role added successfully</div>";
    } else {
        $errors = $employee->getErrors();
        foreach ($errors as $error) {
            echo "<div class='error'>$error</div>";
        }
    }
}

include('./header.php');

$sql = "SELECT * FROM department";
$res = mysqli_query($conn, $sql);
?>



<div class="col-md">
    <div class="card" style="width: 500px; height: max-content; margin: 0 300px; margin-top: 100px;">
        <h5 class="card-header"style="margin: 0 auto;">Add Role</h5>
        <div class="card-body">
            <form class="needs-validation" novalidate   action="add-role.php" method="POST" enctype="multipart/form-data">
                <div class="form-floating form-floating-outline mb-6">
                    <input type="text" name='first_name' class="form-control" id="bs-validation-firstname" placeholder="Enter First Name" required />
                    <label for="bs-validation-firstname">First Name</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter first name.</div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                    <input type="text" name='last_name' class="form-control" id="bs-validation-lastname" placeholder="Enter Last Name" required />
                    <label for="bs-validation-lastname">Last Name</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter last name.</div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                    <input type="email" name='email' id="bs-validation-email" class="form-control" placeholder="Test@gmail.com" aria-label="john.doe" required />
                    <label for="bs-validation-email">Email</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter a valid email</div>
                </div>
                <div class="mb-6 form-password-toggle">
                    <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                        <input type="password" name='password' id="bs-validation-password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                        <label for="bs-validation-password">Password</label>
                    </div>
                    <span class="input-group-text rounded-end cursor-pointer" id="basic-default-password4"><i class="ri-eye-off-line"></i></span>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter password.</div>
                    </div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                <select name="role" class="form-select" id="role" required>
                    <option disabled selected>Select role</option>
                    <option value="2">HR</option>
                    <option value="3">Manager</option>
                    <option value="4">Employee</option>
                </select>
                    <label class="form-label" for="bs-validation-role">role</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please select role</div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                    <input type="number" name='phoneno' class="form-control" id="bs-validation-phoneno" placeholder="Enter Phone No" required />
                    <label for="bs-validation-phoneno">Phone No</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter Phone No.</div>
                </div>
                <div class="mb-6">
                    <label class="d-block form-label">Gender</label>
                    <div class="form-check mb-2">
                        <input type="radio" id="bs-validation-radio-male" name="gender" value="0" class="form-check-input" required checked />
                        <label class="form-check-label" for="bs-validation-radio-male">Male</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="bs-validation-radio-female" name="gender" value="1" class="form-check-input" required />
                        <label class="form-check-label" for="bs-validation-radio-female">Female</label>
                    </div>
                </div>
                <div id="employee_fields" style="display:none;">
                    <div class="form-floating form-floating-outline mb-6">
                        <input type="text" name='address' id="bs-validation-address" class="form-control" placeholder="Enter Address" aria-label="john.doe" required />
                        <label for="bs-validation-address">Address</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback">Please enter a valid address</div>
                    </div>
                    <div class="form-floating form-floating-outline mb-6">
                        <input name='birth_date' class="form-control" type="date" id="bs-validation-birthdate" required/>
                        <label for="bs-validation-birthdate">Birth Date</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback">Please Enter Your Birth Date</div>
                    </div>
                    <div class="form-floating form-floating-outline mb-6">
                        <label for="image" class="form-label"></label>
                        <input type="file" name="image" class="form-control" id="image" required>
                    </div>

                    <div class="form-floating form-floating-outline mb-6">
                        <input type="text" name='job_title' class="form-control" id="bs-validation-jobtitle" placeholder="Enter Job Title" required />
                        <label for="bs-validation-jobtitle">Job Title</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback">Please enter Job Title.</div>
                    </div>

                </div>                
                <div class="form-floating form-floating-outline mb-6">
                    <input name='hire_date' class="form-control" type="date" id="bs-validation-hiredate" required/>
                    <label for="bs-validation-hiredate">Joining Date</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please Enter Your Hire Date</div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                    <input type="number" name='salary' class="form-control" id="bs-validation-salary" placeholder="Enter Salary" required />
                    <label for="bs-validation-salary">Salary</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter Salary.</div>
                </div>
                <div id="manager_fields" style="display:none;">
                    <div class="form-floating form-floating-outline mb-6">
                        <select name="department_id" class="form-select" id="bs-validation-department" required>
                            <option value="">Select Department</option>
                            <?php while ($row = mysqli_fetch_assoc($res)) { ?> 
                                <option value="<?php echo $row['id']; ?>" <?php if (isset($_POST['department_id']) && $_POST['department_id'] == $row['id']) echo 'selected'; ?>> 
                                    <?php echo $row['department_name']; ?> 
                                </option> 
                            <?php } ?> 
                        </select>
                        <label class="form-label" for="bs-validation-department">Department</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback">Please select department</div>
                    </div>
                </div>
                <div class="row" style="margin: 0 170px; margin-top: 20px;">
                    <div class="col-12">
                    <button type="submit" name='submit' class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

document.getElementById('role').addEventListener('change', function () {
    const role = this.value;
    const managerFields = document.getElementById('manager_fields');
    const employeeFields = document.getElementById('employee_fields');
    const departmentField = document.getElementById('bs-validation-department');
    const jobTitleField = document.getElementById('bs-validation-jobtitle');

    managerFields.style.display = 'none';
    employeeFields.style.display = 'none';

    departmentField.removeAttribute('required');
    jobTitleField.removeAttribute('required');

    if (role === '3') { 
        managerFields.style.display = 'block';
        departmentField.setAttribute('required', 'true');
    } else if (role === '4') { 
        managerFields.style.display = 'block';
        employeeFields.style.display = 'block';
        departmentField.setAttribute('required', 'true');
        jobTitleField.setAttribute('required', 'true');
    }
});

document.getElementById('role').dispatchEvent(new Event('change'));

</script>

<?php include("./footer.php");?>
