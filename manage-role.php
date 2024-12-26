<?php
include('./include/db-connection.php');
include('header.php');
$role = $_SESSION['role'];
?>

<div class="card" style="max-width: max-content; margin: 50px 100px;">
    <?php if ($role == 0) { ?>
        <h5 class="card-header" style="margin: 0 300px;">Manage Role</h5>
    <?php } ?>

    <?php if ($role == 1) { ?>
        <h5 class="card-header" style="margin: 0 340px;">Manage Employee</h5>
    <?php } ?>

    <a href="add-role.php" class="d-block mx-1 btn" style="background-color: #666cff; color:white">
        <?php echo $role == 0 ? 'Add Role' : 'Add Employee'; ?>
    </a>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <?php if ($role == 0) { ?>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    <?php } ?>

                    <?php if ($role == 1) { ?>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone No</th>
                        <th>Gender</th>
                        <th>Hire Date</th>
                        <th>Department</th>
                        <th>Job Title</th>
                        <th>Salary</th>
                        <th>Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php

                if ($role == 0) {
                    $sql = "SELECT * FROM users WHERE role in (2,3,4)";
                } if ($role == 1) {
                    $sql = "SELECT employee.*, department.department_name AS department_name 
                            FROM employee 
                            LEFT JOIN department ON employee.department_id = department.id";
                }

                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res);

                if ($count > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($res)) {
                        if ($role == 0) {
                            $email = $row['email'];
                            $userRole = $row['role'];
                ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $email; ?></td>
                                <td>
                                    <?php
                                    if ($userRole == "0") echo "Admin";
                                    if ($userRole == "1") echo "HR";
                                    if ($userRole == "2") echo "Manager";
                                    if ($userRole == "3") echo "Employee";
                                    ?>
                                </td>
                                <td class="d-flex">
                                    <a href="update-role.php?id=<?php echo $row['id']; ?>&role=<?php echo $row['role']; ?>" class="d-block mx-1 btn btn-success">Update</a>
                                    <a href="delete-role.php?id=<?php echo $row['id']; ?>" class="d-block mx-1 btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php
                        } elseif ($role == 1) {
                            $firstName = $row['first_name'];
                            $lastName = $row['last_name'];
                            $email = $row['email'];
                            $phoneNo = $row['phoneno'];
                            $gender = $row['gender'] == 0 ? 'Male' : 'Female';
                            $hireDate = $row['hire_date'];
                            $department = $row['department_name']; 
                            $jobTitle = $row['job_title'];
                            $salary = $row['salary'];
                        ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $firstName; ?></td>
                                <td><?php echo $lastName; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $phoneNo; ?></td>
                                <td><?php echo $gender; ?></td>
                                <td><?php echo $hireDate; ?></td>
                                <td><?php echo $department; ?></td>
                                <td><?php echo $jobTitle; ?></td>
                                <td><?php echo $salary; ?></td>
                                <td class="d-flex">
                                    <a href="update-role.php?id=<?php echo $row['employee_id']; ?>&role=3" class="d-block mx-1 btn btn-success">Update</a>
                                    <a href="delete-role.php?id=<?php echo $row['employee_id']; ?>" class="d-block mx-1 btn btn-danger">Delete</a>
                                </td>
                            </tr>
                <?php
                        }
                    }
                } else {
                    echo '<tr><td colspan="10">No records found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include("./footer.php"); ?>
