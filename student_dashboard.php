<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

include('db_connection.php');
$username = $_SESSION['username'];

// Fetch student details
$sql = "SELECT * FROM students WHERE username = '$username'";
$result = $conn->query($sql);
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        /* General body styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fb;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, rgba(98, 163, 255, 1) 0%, rgba(77, 143, 255, 1) 100%);
        }

        .container {
            background: #fff;
            border-radius: 12px;
            padding: 40px 30px;
            width: 400px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4e9ef1;
            color: #fff;
            border-radius: 8px;
            font-size: 16px;
            margin: 15px 0;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #3c8cd3;
        }

        /* Styling for the links to buttons */
        a {
            text-decoration: none;
        }

        /* Responsive design adjustments */
        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome <?php echo $student['student_name']; ?></h1>
        <p>Email: <?php echo $student['student_email']; ?></p>
        <p>ID: <?php echo $student['student_id']; ?></p>

        <!-- Submit Reschedule Request -->
        <a href="submit_reschedule.php"><button class="button">Submit Your Reschedule Request</button></a>

        <!-- View Reschedule Status -->
        <a href="view_status.php"><button class="button">View Reschedule Request Status</button></a>

        <!-- View Rescheduled Lab Details -->
        <a href="view_rescheduled_labs.php"><button class="button">View Rescheduled Lab Details</button></a>
    </div>

</body>
</html>

