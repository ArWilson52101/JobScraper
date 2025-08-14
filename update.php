<?php
$conn = new mysqli("localhost", "root", "mysql", "final");

$job_id = $_POST['job_id'];
$status = $_POST['status'];
$notes = $_POST['notes'];

// update the applications table
$conn->query("
    INSERT INTO saved_jobs (job_id, date_applied, status, notes)
    SELECT $job_id, CURDATE(), '$status', '$notes'
    FROM DUAL
    WHERE NOT EXISTS (
        SELECT 1 FROM saved_jobs WHERE job_id = $job_id AND status = '$status'
    )
");


header("Location: index.php");
?>
