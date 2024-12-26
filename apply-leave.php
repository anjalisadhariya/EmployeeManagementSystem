<?php include('./include/db-connection.php');

class ApplyLeave{
    private $conn;
    private $data = [];
    private $errors = [];

    public function __construct($conn, $data) {
        $this->conn = $conn;
        $this->data = $data;
    }

    public function applyLeave(){

        $employee_id = $_SESSION['user_id'];
        $sql = "INSERT INTO leaves (employee_id, start_date, end_date, reason , status) VALUES(?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", 
        $employee_id, 
        $this->data['start_date'], 
        $this->data['end_date'], 
        $this->data['reason'], 
        $this->data['status']
    );

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: apply-leave.php");
        exit;
    } else {
        $this->errors[] = "Failed to apply leave: " . $stmt->error;
        $stmt->close();
        return false;
    }
    }
    
}
if (isset($_POST['submit'])) {
    $apply_leave = new ApplyLeave($conn, $_POST);

    if (empty($errors)) {
        
        $result = $apply_leave->applyLeave();
        if ($result) {
            echo "<div class='error'>$result</div>";
        }
    }
}
include("./header.php");

?>

<div class="col-md">
    <div class="card" style="width: 500px; height: max-content; margin: 0 300px; margin-top: 100px;">
        <h5 class="card-header"style="margin: 0 auto;">Apply Leave Form</h5>
        <div class="card-body">
            <form class="needs-validation" novalidate   action="apply-leave.php" method="POST" enctype="multipart/form-data">
                <div class="form-floating form-floating-outline mb-6">
                    <input type="text" name='reason' class="form-control" id="bs-validation-reason" placeholder="Enter Reason" required />
                    <label for="bs-validation-reason">Reason</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please enter Reason.</div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                    <input name='start_date' class="form-control" type="date" id="bs-validation-start_date" required/>
                    <label for="bs-validation-start_date">Start Date</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please Enter start Date</div>
                </div>
                <div class="form-floating form-floating-outline mb-6">
                    <input name='end_date' class="form-control" type="date" id="bs-validation-end_date" required/>
                    <label for="bs-validation-end_date">End Date</label>
                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback">Please Enter End Date</div>
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
<?php  include('./footer.php');?>