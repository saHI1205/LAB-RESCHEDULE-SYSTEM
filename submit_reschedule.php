<?php
session_start();

// Check if the student is logged in and is a student
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

include('db_connection.php');

// Fetch available coordinators for selection
$sql = "SELECT * FROM coordinators";
$coordinators_result = $conn->query($sql);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data
    $coordinator_id = $_POST['coordinator_id'];
    $module = $_POST['module'];
    $lab_id = $_POST['lab_id'];
    $lab_name = $_POST['lab_name'];
    $instructor_name = $_POST['instructor_name'];

    // Ensure student_id is set in session
    if (!isset($_SESSION['student_id'])) {
        echo "Student ID not found in session. Redirecting to login...";
        header("Location: login.php");
        exit();
    }
    $student_id = $_SESSION['student_id']; // Get student ID from session

    // File upload for medical certificate
    $medical_certificate = '';
    if (isset($_FILES['medical_certificate']) && $_FILES['medical_certificate']['error'] == 0) {
        $file_name = $_FILES['medical_certificate']['name'];
        $file_tmp = $_FILES['medical_certificate']['tmp_name'];
        $file_path = 'uploads/' . $file_name;

        // Ensure the upload directory exists
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true); // Create directory if it doesn't exist
        }

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($file_tmp, $file_path)) {
            $medical_certificate = $file_name;
        } else {
            echo "Error moving the uploaded file.";
            exit();
        }
    } else {
        echo "No file uploaded or an error occurred.";
        exit();
    }

    // Insert the medical form into the medical_forms table and retrieve the ID
    $stmt = $conn->prepare("INSERT INTO medical_forms (student_id, medical_certificate) VALUES (?, ?)");
    $stmt->bind_param("is", $student_id, $medical_certificate);
    $stmt->execute();
    $medical_form_id = $stmt->insert_id;  // Get the ID of the inserted medical form

    // Corrected SQL query with matching bind_param() for 7 variables (removed 'status')
    $sql = "INSERT INTO reschedule_requests (student_id, coordinator_id, module, lab_id, lab_name, instructor_name, status, medical_form_id)
            VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)";

    // Prepare and execute the reschedule request insertion
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssssi", $student_id, $coordinator_id, $module, $lab_id, $lab_name, $instructor_name, $medical_form_id);
    $stmt->execute();

    echo "Reschedule request submitted successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Reschedule Request</title>
    <style>
        /* General body styling */
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

        /* Container for the form */
        .container {
            background: #fff;
            border-radius: 12px;
            padding: 40px 30px;
            width: 400px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
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

        .footer {
            text-align: center;
            font-size: 14px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Submit Reschedule Request</h2>

        <form method="POST" enctype="multipart/form-data">
            <!-- Coordinator selection -->
            <label for="coordinator_id">Coordinator:</label>
            <select name="coordinator_id" required>
                <?php while ($row = $coordinators_result->fetch_assoc()) { ?>
                    <option value="<?php echo $row['coordinator_id']; ?>"><?php echo $row['coordinator_name']; ?></option>
                <?php } ?>
            </select><br>

            <!-- Module input -->
            <label for="module">Module:</label>
            <input type="text" name="module" required><br>

            <!-- Lab ID input -->
            <label for="lab_id">Lab ID:</label>
            <input type="number" name="lab_id" required><br>

            <!-- Lab Name input -->
            <label for="lab_name">Lab Name:</label>
            <input type="text" name="lab_name" required><br>

            <!-- Instructor Name input -->
            <label for="instructor_name">Instructor Name:</label>
            <input type="text" name="instructor_name" required><br>

            <!-- Medical Certificate upload -->
            <label for="medical_certificate">Upload Medical Certificate (PDF):</label>
            <input type="file" name="medical_certificate" accept="application/pdf" required><br>

            <!-- Submit button -->
            <button type="submit">Submit Request</button>
        </form>

        <div class="footer">
            <p>Need help? <a href="help.php">Contact support</a></p>
        </div>
    </div>

</body>
</html>
