<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}

include('db_connection.php');
$username = $_SESSION['username'];

// Fetch coordinator details
$sql = "SELECT * FROM coordinators WHERE username = '$username'";
$result = $conn->query($sql);
$coordinator = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinator Dashboard</title>
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

        /* Container for the page */
        .container {
            background: #fff;
            border-radius: 12px;
            padding: 40px 30px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        /* Table styling */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            font-size: 16px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4e9ef1;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e3e3e3;
        }

        a {
            text-decoration: none;
            color: #4e9ef1;
            font-weight: bold;
        }

        a:hover {
            color: #3c8cd3;
        }

        /* Button Styling */
        .approve-btn {
            padding: 8px 16px;
            background-color: #4e9ef1;
            color: white;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .approve-btn:hover {
            background-color: #3c8cd3;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .container {
                width: 100%;
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }

            table th, table td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome <?php echo $coordinator['coordinator_name']; ?></h1>

        <!-- View Reschedule Requests -->
        <table>
            <tr>
                <th>Request ID</th>
                <th>Student Name</th>
                <th>Medical Form</th>
                <th>Status</th>
                <th>Approve</th>
            </tr>
            <?php
            $sql = "SELECT * FROM reschedule_requests WHERE coordinator_id = " . $coordinator['coordinator_id'];
            $result = $conn->query($sql);
            while ($request = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $request['request_id'] . "</td>";
                echo "<td>" . $request['student_id'] . "</td>";
                echo "<td><a href='uploads/" . $request['medical_form_id'] . "'>View</a></td>";
                echo "<td>" . $request['status'] . "</td>";
                echo "<td><a href='approve_request.php?id=" . $request['request_id'] . "'><button class='approve-btn'>Approve</button></a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>
