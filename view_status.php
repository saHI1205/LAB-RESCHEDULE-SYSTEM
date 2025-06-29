<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('db_connection.php');
$username = $_SESSION['username'];

// Fetch student details
$sql = "SELECT * FROM students WHERE username = '$username'";
$result = $conn->query($sql);
$student = $result->fetch_assoc();

// Fetch student's reschedule request status
$sql = "SELECT * FROM reschedule_requests WHERE student_id = " . $student['student_id'];
$result = $conn->query($sql);

?>

<h1>Reschedule Request Status</h1>

<table border="1">
    <tr>
        <th>Request ID</th>
        <th>Module</th>
        <th>Lab Name</th>
        <th>Status</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['request_id']; ?></td>
        <td><?php echo $row['module']; ?></td>
        <td><?php echo $row['lab_name']; ?></td>
        <td><?php echo $row['status']; ?></td>
    </tr>
    <?php } ?>
</table>
