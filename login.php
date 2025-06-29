<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db_connection.php');

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check for each role and fetch the corresponding user data
    if ($role == 'instructor') {
        // Fetch instructor details
        $sql = "SELECT * FROM instructors WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password); // Bind parameters to prevent SQL injection
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $instructor = $result->fetch_assoc();

            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'instructor';
            $_SESSION['instructor_id'] = $instructor['instructor_id'];  // Set instructor_id in session
            
            // Redirect to instructor dashboard
            header("Location: instructor_dashboard.php");
            exit();
        } else {
            echo "Invalid login credentials!";
        }
    } elseif ($role == 'coordinator') {
        // Fetch coordinator details
        $sql = "SELECT * FROM coordinators WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password); // Bind parameters to prevent SQL injection
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $coordinator = $result->fetch_assoc();

            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'coordinator';
            $_SESSION['coordinator_id'] = $coordinator['coordinator_id'];  // Set coordinator_id in session
            
            // Redirect to coordinator dashboard
            header("Location: coordinator_dashboard.php");
            exit();
        } else {
            echo "Invalid login credentials!";
        }
    } elseif ($role == 'student') {
        // Fetch student details
        $sql = "SELECT * FROM students WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password); // Bind parameters to prevent SQL injection
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();

            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'student';
            $_SESSION['student_id'] = $student['student_id'];  // Set student_id in session
            
            // Redirect to student dashboard
            header("Location: student_dashboard.php");
            exit();
        } else {
            echo "Invalid login credentials!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .login-container {
            background: #fff;
            border-radius: 12px;
            padding: 40px 30px;
            width: 350px;
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

    <div class="login-container">
        <h2>Login</h2>

        <form method="POST" action="">
            <!-- Username input -->
            <input type="text" name="username" placeholder="Username" required><br>

            <!-- Password input -->
            <input type="password" name="password" placeholder="Password" required><br>

            <!-- Role selection -->
            <select name="role" required>
                <option value="student">Student</option>
                <option value="coordinator">Coordinator</option>
                <option value="instructor">Instructor</option>
            </select><br>

            <!-- Submit button -->
            <button type="submit">Login</button>
        </form>
        
        <div class="footer">
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>

</body>
</html>
