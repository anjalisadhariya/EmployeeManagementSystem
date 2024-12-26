<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?php 
include("./include/db-connection.php");

$employee_id = $_SESSION['user_id'];

if ($employee_id) {
    $sql = "SELECT * FROM employee WHERE employee_id = '$employee_id'";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $password = $row['password'];
        $phoneno = $row['phoneno'];
        $address = $row['address'];
        $gender = $row['gender'];
        $birth_date = $row['birth_date'];
        $img = $row['img']; 
        print_r($img);
    }
}

if (isset($_POST['submit'])) {
    $id = $_SESSION['user_id'];

    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phoneno = mysqli_real_escape_string($conn, $_POST['phone_no']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $birth_date = mysqli_real_escape_string($conn, $_POST['birth_date']);

    $updated_img = $img; 
    if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['img']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file_name);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
        if (in_array($file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            if ($_FILES['img']['size'] <= 800 * 1024) { 
                if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
                    $updated_img = $file_name; 
                } else {
                    echo "Error uploading the file.";
                }
            } else {
                echo "File size exceeds 800KB.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } elseif (isset($_FILES['img']['error']) && $_FILES['img']['error'] != UPLOAD_ERR_NO_FILE) {
        echo "File upload error code: " . $_FILES['img']['error'];
    }
    

    $sql_update_user_role = "UPDATE users SET email = '$email', password = md5('$password') WHERE id = '$id'";
    $res = mysqli_query($conn, $sql_update_user_role);

    if ($res) {
        $sql_update_employee = "UPDATE employee SET 
            first_name = '$first_name', 
            last_name = '$last_name', 
            email = '$email', 
            phoneno = '$phoneno', 
            gender = '$gender', 
            address = '$address',
            birth_date = '$birth_date',
            img = '$updated_img'
            WHERE employee_id = '$id'";

        if (!mysqli_query($conn, $sql_update_employee)) {
            die("Employee Update Error: " . mysqli_error($conn));
        }
    }

    header("Location: emp-profile.php");
    exit;
}


include("header.php");
?>

<div class="card mb-6">
<form id="formAccountSettings" novalidate action="" method="POST" enctype="multipart/form-data">
    <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-6">
                <?php 
                $image_path = "uploads/" . $img;
                if (!empty($img) && file_exists($image_path)) { ?>
                    <img id="preview-image" src="<?php echo $image_path; ?>" alt="user-avatar" class="d-block w-px-150 h-px-150 rounded-4" />
                <?php } else { ?>
                    <img id="preview-image" src="uploads/default.jpg" alt="default-avatar" class="d-block w-px-150 h-px-150 rounded-4" />
                <?php } ?>
                <div class="button-wrapper">
                    <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                        <span class="d-none d-sm-block">Upload new photo</span>
                        <i class="ri-upload-2-line d-block d-sm-none"></i>
                        <input type="file" id="upload" name="img" class="account-file-input" hidden accept="image/*" />
                    </label>
                    <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                </div>
            </div>
        </div>
        

        <div class="card-body pt-0">
            <div class="row mt-1 g-5">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="first_name" name="first_name" value=<?php echo $first_name; ?> autofocus />
                        <label for="first_name">First Name</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="last_name" name="last_name" value=<?php echo $last_name; ?> autofocus />
                        <label for="last_name">Last Name</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="email" name="email" value=<?php echo $email; ?> autofocus />
                        <label for="email">Email</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="phone_no" name="phone_no" class="form-control" value=<?php echo $phoneno; ?> />
                            <label for="phone_no">Phone Number</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input type="text" class="form-control" id="address" name="address" value=<?php echo $address; ?> />
                        <label for="address">Address</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="d-block form-label">Gender</label>
                    <div class="form-floating form-floating-outline">
                        <div class="form-check mb-2">
                            <input type="radio" id="bs-validation-radio-male" name="gender" <?php if($gender==0) {echo "checked";}?> value='0' class="form-check-input" required  />
                            <label class="form-check-label" for="bs-validation-radio-male">Male</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" id="bs-validation-radio-female" name="gender" <?php if($gender==1) {echo "checked";}?> value='1' class="form-check-input" required />
                            <label class="form-check-label" for="bs-validation-radio-female">Female</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input name='birth_date' class="form-control" type="date" value='<?php echo $birth_date; ?>' id="bs-validation-hiredate" required/>
                        <label for="bs-validation-hiredate">Birth Date</label>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    <button type="button" id="reset_btn" class="btn btn-outline-secondary">Reset</button>
                </div>
            </div>
        </div>
    </form>
</div>




<script>
    $(document).ready(function () {
        $("#reset_btn").click(function () {
            $("#formAccountSettings").find("input[type='text'], input[type='email'], input[type='date']").val("");
            $("#formAccountSettings").find("input[type='radio']").prop("checked", false);
            $("#upload").val("");
        });
    });

    document.getElementById('upload').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('preview-image').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

</script>
<?php  include('./footer.php');?>