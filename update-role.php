<?php 
include('./include/db-connection.php');

if (isset($_GET['id']) && isset($_GET['role'])) {
    
    $id = intval($_GET['id']);
    $role = intval($_GET['role']);

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $user = $res->fetch_assoc();

        if ($role == 1) { 
            $sql_hr = "SELECT * FROM hr WHERE hr_id = ?";
        } elseif ($role == 2) { 
            $sql_hr = "SELECT * FROM manager WHERE manager_id = ?";
        } elseif ($role == 3) {
            $sql_hr = "SELECT * FROM employee WHERE employee_id = ?";
        } else {
            echo "Invalid role.";
            exit;
        }

        $stmt_role = $conn->prepare($sql_hr);
        $stmt_role->bind_param("i", $id);
        $stmt_role->execute();
        $res_role = $stmt_role->get_result();

        if ($res_role && $res_role->num_rows > 0) {
            $row = $res_role->fetch_assoc();
            extract($row);
        } else {
            echo "No details found for the given ID and role.";
            exit;
        }
    } else {
        echo "User not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id = intval($_GET['id']);

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phoneno = mysqli_real_escape_string($conn, $_POST['phoneno']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $img = mysqli_real_escape_string($conn, $_POST['image']);
    $hire_date = mysqli_real_escape_string($conn, $_POST['hire_date']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $role = $_GET['role'];
    $department_id = $_POST['department_id'] ?? null;
    $job_title = $_POST['job_title'] ?? null;

    $sql_update_user_role = "UPDATE users SET email = '$email', password = md5('$password') WHERE id = '$id'";
    $res = mysqli_query($conn, $sql_update_user_role);

    if($res){
        if ($role == '1') { 
            $sql_update_hr = "UPDATE hr SET 
                first_name = '$first_name', 
                last_name = '$last_name', 
                email = '$email', 
                password = md5('$password'), 
                phoneno = '$phoneno', 
                gender = '$gender', 
                hire_date = '$hire_date', 
                salary = '$salary' 
                WHERE hr_id = '$id'";
        
            if (!mysqli_query($conn, $sql_update_hr)) {
                die("HR Update Error: " . mysqli_error($conn));
            }
        }
        elseif ($role == '2') {
            $sql_update_manager = "UPDATE manager SET 
                first_name = '$first_name', 
                last_name = '$last_name', 
                email = '$email', 
                password = md5('$password'), 
                phoneno = '$phoneno', 
                gender = '$gender', 
                hire_date = '$hire_date', 
                salary = '$salary', 
                department_id = '$department_id' 
                WHERE manager_id = '$id'";
            if (!mysqli_query($conn, $sql_update_manager)) {
                die("Manager Update Error: " . mysqli_error($conn));
            }
        } elseif ($role == 3) {
            $sql_update_employee = "UPDATE employee SET 
                first_name = '$first_name', 
                last_name = '$last_name', 
                email = '$email', 
                password = md5('$password'), 
                phoneno = '$phoneno', 
                gender = '$gender', 
                address = '$address',
                birth_date = '$birth_date',
                img = '$img',
                hire_date = '$hire_date', 
                job_title = '$job_title',
                salary = '$salary', 
                department_id = '$department_id'
                WHERE employee_id = '$id'";
                print_r($sql_update_employee);
            if (!mysqli_query($conn, $sql_update_employee)) {
                die("Employee Update Error: " . mysqli_error($conn));
            }
        }
    }

        header("Location: manage-role.php");
        exit;
}


include("header.php");

$sql = "SELECT * FROM department";
$res = $conn->query($sql);
?>

<div class="col-md">
    <div class="card" style="width: 500px; height: max-content; margin: 0 300px; margin-top: 100px;">
        <h5 class="card-header"style="margin: 0 auto;">Update Role</h5>
        <div class="card-body">
            <form class="needs-validation" novalidate   action="" method="POST">
                <div class="form-floating form-floating-outline mb-6">
                    <input type="text" name='first_name' class="form-control" value='<?php echo $first_name;?>' id="bs-validation-firstname" placeholder="Enter First Name" required />
                    <label for="bs-validation-firstname">First Name</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter first name.</div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                    <input type="text" name='last_name' class="form-control" value='<?php echo $last_name; ?>' id="bs-validation-lastname" placeholder="Enter Last Name" required />
                    <label for="bs-validation-lastname">Last Name</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter last name.</div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                    <input type="email" name='email' id="bs-validation-email" value='<?php echo $email; ?>' class="form-control" placeholder="Test@gmail.com" aria-label="john.doe" required />
                    <label for="bs-validation-email">Email</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter a valid email</div>
                </div>
                <div class="mb-6 form-password-toggle">
                    <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                        <input type="password" name='password' id="bs-validation-password" value='<?php echo $password; ?>' class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
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
                        <option value="1" <?php if ($role == 1) echo 'selected'; ?>>HR</option>
                        <option value="2" <?php if ($role == 2) echo 'selected'; ?>>Manager</option>
                        <option value="3" <?php if ($role == 3) echo 'selected'; ?>>Employee</option>
                    </select>
                        <label class="form-label" for="bs-validation-role">role</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback">Please select role</div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                    <input type="number" name='phoneno' class="form-control" id="bs-validation-phoneno" value='<?php echo $phoneno; ?>' placeholder="Enter Phone No" required />
                    <label for="bs-validation-phoneno">Phone No</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter Phone No.</div>
                </div>
                <div class="mb-6">
                    <label class="d-block form-label">Gender</label>
                    <div class="form-check mb-2">
                        <input type="radio" id="bs-validation-radio-male" name="gender" <?php if($gender==0) {echo "checked";}?> value='0' class="form-check-input" required  />
                        <label class="form-check-label" for="bs-validation-radio-male">Male</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="bs-validation-radio-female" name="gender" <?php if($gender==1) {echo "checked";}?> value='1' class="form-check-input" required />
                        <label class="form-check-label" for="bs-validation-radio-female">Female</label>
                    </div>
                </div>
                <div id="employee_fields" style="display:none;">
                    <div class="form-floating form-floating-outline mb-6">
                        <input type="text" name='address' id="bs-validation-address" class="form-control" placeholder="Enter Address" value="<?php echo $address ; ?>" required />
                        <label for="bs-validation-address">Address</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback">Please enter a valid address</div>
                    </div>
                    <div class="form-floating form-floating-outline mb-6">
                        <input name='birth_date' class="form-control" type="date" id="bs-validation-birthdate" value="<?php echo $birth_date ;?>" required/>
                        <label for="bs-validation-birthdate">Birth Date</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback">Please Enter Your Birth Date</div>
                    </div>
                    <div class="form-floating form-floating-outline mb-6">
                        <label for="image" class="form-label"></label>
                        <input type="file" name="image" class="form-control" id="image" value="<?php echo $img ;?>" required>
                    </div>

                    <div class="form-floating form-floating-outline mb-6">
                        <input type="text" name='job_title' class="form-control" id="bs-validation-jobtitle" value="<?php echo $job_title ;?>" placeholder="Enter Job Title" required />
                        <label for="bs-validation-jobtitle">Job Title</label>
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback">Please enter Job Title.</div>
                    </div>

                </div>
                <div class="form-floating form-floating-outline mb-6">
                        <input name='hire_date' class="form-control" type="date" value='<?php echo $hire_date; ?>' id="bs-validation-hiredate" required/>
                        <label for="bs-validation-hiredate">Joining Date</label>
                        <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please Enter Your Hire Date</div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                    <input type="number" name='salary' class="form-control" value='<?php echo $salary; ?>' id="bs-validation-salary" placeholder="Enter Salary" required />
                    <label for="bs-validation-salary">Salary</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter Salary.</div>
                </div>
                
                <div id="manager_fields" style="display:none;">
                    <div class="form-floating form-floating-outline mb-6">
                        <select name="department_id" class="form-select" id="bs-validation-department" required>
                            <option value="">Select Department</option>
                            <?php while ($row = mysqli_fetch_assoc($res)) { ?> 
                                <option value="<?php echo $department_id ?>" <?php if (isset($department_id) && $department_id == $row['id']) echo 'selected'; ?>> 
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
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
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

    if (role === '2') { 
        managerFields.style.display = 'block';
        departmentField.setAttribute('required', 'true');
    } else if (role === '3') { 
        managerFields.style.display = 'block';
        employeeFields.style.display = 'block';
        departmentField.setAttribute('required', 'true');
        jobTitleField.setAttribute('required', 'true');
    }
});

document.getElementById('role').dispatchEvent(new Event('change'));
</script>

<?php include('./footer.php');?>
