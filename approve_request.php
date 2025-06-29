<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'coordinator') {
    header("Location: login.php");
    exit();
}

include('db_config.php');

if (isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // Get request details
    $sql = "SELECT * FROM reschedule_requests WHERE request_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $status = $_POST['status'];

        // Update the status of the request
        $sql = "UPDATE reschedule_requests SET status = ? WHERE request_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $request_id);
        $stmt->execute();

        // If approved, insert the rescheduled lab details
        if ($status == 'approved') {
            $student_id = $request['student_id'];
            $student_name = $request['student_name'];
            $lab_name = $request['lab_name'];
            $module = $request['module'];
            $rescheduled_date = date('Y-m-d');
            $venue = 'Room 202';

            $sql = "INSERT INTO rescheduled_labs (student_id, student_name, lab_name, module, rescheduled_date, venue)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssss", $student_id, $student_name, $lab_name, $module, $rescheduled_date, $venue);
            $stmt->execute();

            // Send email to the student about the approval
            $to = $request['student_email'];
            $subject = "Your Reschedule Request has been Approved";
            $message = "Dear " . $student_name . ",\n\nYour reschedule request for the " . $module . " has been approved.\n\nNew Lab Details:\nLab: " . $lab_name . "\nDate: " . $rescheduled_date . "\nVenue: " . $venue;
            $headers = "From: no-reply@labreschedule.com";

            // Send the email
            mail($to, $subject, $message, $headers);

            // You can also send email to the coordinator and instructor if needed
        }

        echo "Reschedule request status updated successfully.";
    }
} else {
    echo "No request ID provided.";
    exit();
}
?>
