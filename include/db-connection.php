<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee-management";
    $conn = new mysqli($hostname, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>