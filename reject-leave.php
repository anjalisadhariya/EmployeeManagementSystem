<?php
include("./include/db-connection.php");

$id = $_GET['id'];

$sql = "UPDATE leaves SET status='2' WHERE  employee_id = $id";
$res = mysqli_query($conn, $sql);

if($res == true){

    header("Location:emp-leave.php");
}

?>

