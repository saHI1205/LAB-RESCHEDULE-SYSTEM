<?php
session_start();

// Ensure the user is logged in and is an instructor
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'instructor' || !isset($_SESSION['instructor_id'])) {
    header("Location: login.php");
    exit();
}

include('db_connection.php');

// Fetch instructor's ID from session
$instructor_id = $_SESSION['instructor_id'];  // Now it is safe to use

// Handle form submission for adding a new time slot
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lab_id = $_POST['lab_id'];
    $available_date = $_POST['available_date'];
    $available_time = $_POST['available_time'];

    // Insert new time slot into the database
    $sql = "INSERT INTO time_slots (instructor_id, lab_id, available_date, available_time) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $instructor_id, $lab_id, $available_date, $available_time);
    $stmt->execute();

    echo "Time slot added successfully!";
}

// Fetch available time slots for the instructor
$sql = "SELECT * FROM time_slots WHERE instructor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Time Slots</title>
    <style>
        /* Add the CSS you need here, similar to previous examples */
        body {
            font-family: 'Roboto', sans-serif;
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
            width: 80%;
            max-width: 900px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 30px;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #4e9ef1;
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #4e9ef1;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #3c8cd3;
        }

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

    </style>
</head>
<body>

    <div class="container">
        <h1>Manage Your Time Slots</h1>

        <!-- Add new time slot -->
        <form method="POST">
            <label for="lab_id">Lab ID:</label>
            <input type="text" name="lab_id" required><br>

            <label for="available_date">Date:</label>
            <input type="date" name="available_date" required><br>

            <label for="available_time">Time:</label>
            <input type="time" name="available_time" required><br>

            <button type="submit">Add Time Slot</button>
        </form>

        <h2>Existing Time Slots</h2>

        <!-- Table to display existing time slots -->
        <table>
            <tr>
                <th>Lab ID</th>
                <th>Available Date</th>
                <th>Available Time</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['lab_id']; ?></td>
                <td><?php echo $row['available_date']; ?></td>
                <td><?php echo $row['available_time']; ?></td>
                <td>
                    <?php echo $row['is_booked'] ? 'Booked' : 'Available'; ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
