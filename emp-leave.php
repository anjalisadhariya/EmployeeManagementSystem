<?php include('./include/db-connection.php');
include('./header.php');?>

<div class="card" style="max-width: max-content; margin: 50px 100px;">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee ID</th>
                <th>first NAME</th>
                <th>last NAME</th>
                <th>start date</th>
                <th>end date</th>
                <th>total days</th>
                <th>reason</th>
                <th>status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            <?php
                    $sql = "Select employee.id, employee.first_name, employee.last_name, leaves.employee_id, leaves.start_date, leaves.end_date, leaves.reason, leaves.status From employee, leaves Where employee.employee_id = leaves.employee_id ";                   
                    $res = mysqli_query($conn,$sql);
                    $count = mysqli_num_rows($res);

                    if($count > 0){
                        $i=1;

                        while ( $row = mysqli_fetch_assoc($res)){

                            $date1 = new DateTime($row['start_date']);
                            $date2 = new DateTime($row['end_date']);
                            $interval = $date1->diff($date2);
                            $interval = $date1->diff($date2);

                            $employee_id = $row['employee_id'];
                            $first_name = $row['first_name'];  
                            $last_name = $row['last_name'];  
                            $start_date = $row['start_date'];                                      
                            $end_date = $row['end_date'];                    
                            $total_days = $row['end_date'];                    
                            $reason = $row['reason'];                    
                            $status = $row['status'];                    
                ?>

                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $employee_id; ?></td>
                    <td><?php echo $first_name; ?></td>
                    <td><?php echo $last_name; ?></td>
                    <td><?php echo $start_date; ?></td>
                    <td><?php echo $end_date; ?></td>
                    <td><?php echo $interval->days; ?></td>
                    <td><?php echo $reason; ?></td>
                    <td><?php   if($status == 0) echo "Pending";
                                elseif($status == 1) echo "Approved"; 
                                elseif ($status == 2) echo "Rejected"; ?>
                    </td>   
                    <td class="d-flex">
                        <a href="approve-leave.php?id=<?php echo $employee_id; ?>" class="d-block  mx-1 btn btn-success" onClick="return confirm('Are you sure you want to Approve the request?')">Approve</a>  
                        <a href="reject-leave.php?id=<?php echo $employee_id; ?>" class="d-block  mx-1 btn btn-danger" onClick="return confirm('Are you sure you want to Reject the request?')">Reject</a>
                    </td>
                </tr>
                <?php
                        }
                    }
                ?>
        </tbody>
        </table>
    </div>
</div>
<?php include("./footer.php");?>
